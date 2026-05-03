<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceDetail;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('فاتورة مشتريات جديدة')] class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:100')]
    public string $invoice_number = '';

    #[Validate('required|date')]
    public string $purchase_invoice_date = '';

    #[Validate('required|exists:suppliers,id')]
    public ?int $supplier_id = null;

    #[Validate('nullable|image|max:5120')]
    public $product_image = null;

    public ?string $imagePreview = null;

    #[Validate('required|numeric|min:0')]
    public float $paid_amount = 0;

    public string $payment_method = 'cash';

    /** @var array<int, array<string, mixed>> */
    public array $rows = [];

    /** @var array<int, string> */
    public array $searchQueries = [];

    /** @var array<int, array<int, mixed>> */
    public array $searchResults = [];

    /** @var array<int, bool> */
    public array $openDropdowns = [];

    public bool $showModal = false;

    public ?int $pendingRowIndex = null;

    #[Validate('required|string|max:191', as: 'اسم المنتج', onUpdate: false)]
    public string $newProductName = '';

    #[Validate('required|string|max:100|unique:products,product_code', as: 'كود المنتج', onUpdate: false)]
    public string $newProductCode = '';

    #[Validate('required|exists:categories,id', as: 'التصنيف', onUpdate: false)]
    public ?int $newProductCategoryId = null;

    #[Validate('required|numeric|min:0', as: 'سعر التكلفة', onUpdate: false)]
    public float $newProductCost = 0;

    #[Validate('nullable|numeric|min:0', as: 'سعر البيع', onUpdate: false)]
    public ?float $newProductSell = null;

    public bool $saving = false;

    public function mount(): void
    {
        $this->purchase_invoice_date = now()->format('Y-m-d');
        $this->invoice_number = $this->generateInvoiceNumber();
        $this->addRow();
    }

    #[Computed]
    public function suppliers(): \Illuminate\Database\Eloquent\Collection
    {
        return Supplier::query()->orderBy('supplier_name')->get(['id', 'supplier_name']);
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()->orderBy('category_name')->get(['id', 'category_name']);
    }

    #[Computed]
    public function invoiceTotal(): float
    {
        return collect($this->rows)->sum(fn ($r) => ($r['qty'] ?? 0) * ($r['cost'] ?? 0));
    }

    #[Computed]
    public function remainingAmount(): float
    {
        return max(0, $this->invoiceTotal - $this->paid_amount);
    }

    public function addRow(): void
    {
        $id = uniqid('row_', true);
        $this->rows[] = [
            'id' => $id,
            'product_id' => null,
            'product_name' => '',
            'product_code' => '',
            'qty' => 1,
            'cost' => 0,
            'sell' => null,
            'total' => 0,
        ];
        $idx = (int) array_key_last($this->rows);
        $this->searchQueries[$idx] = '';
        $this->searchResults[$idx] = [];
        $this->openDropdowns[$idx] = false;
    }

    public function removeRow(int $index): void
    {
        if (count($this->rows) <= 1) {
            $this->dispatch('toast', message: 'يجب أن تحتوي الفاتورة على صنف واحد على الأقل', type: 'warning');

            return;
        }

        array_splice($this->rows, $index, 1);
        array_splice($this->searchQueries, $index, 1);
        array_splice($this->searchResults, $index, 1);
        array_splice($this->openDropdowns, $index, 1);
    }

    public function updateRowQty(int $index, mixed $value): void
    {
        $this->rows[$index]['qty'] = max(0, (float) $value);
        $this->rows[$index]['total'] = $this->rows[$index]['qty'] * $this->rows[$index]['cost'];
    }

    public function updateRowCost(int $index, mixed $value): void
    {
        $this->rows[$index]['cost'] = max(0, (float) $value);
        $this->rows[$index]['total'] = $this->rows[$index]['qty'] * $this->rows[$index]['cost'];
    }

    public function updateRowSell(int $index, mixed $value): void
    {
        $this->rows[$index]['sell'] = $value !== '' ? max(0, (float) $value) : null;
    }

    public function updatedSearchQueries(mixed $value, string $key): void
    {
        $index = (int) $key;
        $query = is_string($value) ? trim($value) : '';

        if (strlen($query) < 1) {
            $this->searchResults[$index] = [];
            $this->openDropdowns[$index] = false;

            return;
        }

        $this->searchResults[$index] = Product::query()
            ->where(function ($q) use ($query) {
                $q->where('product_name', 'like', '%'.$query.'%')
                    ->orWhere('product_code', 'like', '%'.$query.'%');
            })
            ->with('category:id,category_name')
            ->select('id', 'product_name', 'product_code', 'product_cost', 'product_price', 'category_id')
            ->limit(8)
            ->get()
            ->toArray();

        $this->openDropdowns[$index] = true;
    }

    public function selectProduct(int $rowIndex, int $productId): void
    {
        $product = Product::query()->with('category:id,category_name')->find($productId);
        if (! $product) {
            return;
        }

        $alreadyUsed = collect($this->rows)
            ->filter(fn ($r, $i) => (int) $i !== $rowIndex && $r['product_id'] === $productId)
            ->isNotEmpty();

        if ($alreadyUsed) {
            $this->dispatch('toast', message: 'هذا المنتج مضاف بالفعل في الفاتورة', type: 'warning');

            return;
        }

        $displayCode = $product->product_code ?: 'PRD-'.str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);

        $this->rows[$rowIndex] = array_merge($this->rows[$rowIndex], [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $displayCode,
            'cost' => (float) $product->product_cost,
            'sell' => $product->product_price ? (float) $product->product_price : null,
            'total' => $this->rows[$rowIndex]['qty'] * (float) $product->product_cost,
        ]);

        $this->searchQueries[$rowIndex] = $product->product_name;
        $this->searchResults[$rowIndex] = [];
        $this->openDropdowns[$rowIndex] = false;
    }

    public function closeDropdown(int $index): void
    {
        $this->openDropdowns[$index] = false;
    }

    public function updatedProductImage(): void
    {
        $this->validateOnly('product_image');
        $this->imagePreview = $this->product_image?->temporaryUrl();
    }

    public function removeImage(): void
    {
        $this->product_image = null;
        $this->imagePreview = null;
    }

    public function openAddProductModal(int $rowIndex, string $prefill = ''): void
    {
        $this->pendingRowIndex = $rowIndex;
        $this->newProductName = $prefill;
        $this->newProductCode = '';
        $this->newProductCategoryId = null;
        $this->newProductCost = 0;
        $this->newProductSell = null;
        $this->resetValidation(['newProductName', 'newProductCode', 'newProductCategoryId', 'newProductCost', 'newProductSell']);
        $this->showModal = true;
        $this->openDropdowns[$rowIndex] = false;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->pendingRowIndex = null;
    }

    public function saveNewProduct(): void
    {
        $this->validate([
            'newProductName' => 'required|string|max:191',
            'newProductCode' => 'required|string|max:100|unique:products,product_code',
            'newProductCategoryId' => 'required|exists:categories,id',
            'newProductCost' => 'required|numeric|min:0',
            'newProductSell' => 'nullable|numeric|min:0',
        ], [
            'newProductName.required' => 'اسم المنتج مطلوب',
            'newProductCode.required' => 'كود المنتج مطلوب',
            'newProductCode.unique' => 'كود المنتج مستخدم مسبقاً',
            'newProductCategoryId.required' => 'التصنيف مطلوب',
            'newProductCategoryId.exists' => 'التصنيف غير صحيح',
            'newProductCost.required' => 'سعر التكلفة مطلوب',
            'newProductCost.numeric' => 'سعر التكلفة يجب أن يكون رقماً',
            'newProductCost.min' => 'سعر التكلفة يجب أن يكون أكبر من أو يساوي 0',
        ]);

        $product = Product::create([
            'product_name' => $this->newProductName,
            'product_code' => mb_strtoupper($this->newProductCode),
            'product_quantity' => 0,
            'product_cost' => $this->newProductCost,
            'product_price' => $this->newProductSell ?? 0,
            'category_id' => $this->newProductCategoryId,
            'branch_id' => auth()->user()?->branch_id ?? 1,
        ]);

        $displayCode = $product->product_code ?: 'PRD-'.str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);

        if ($this->pendingRowIndex !== null && isset($this->rows[$this->pendingRowIndex])) {
            $this->rows[$this->pendingRowIndex] = array_merge($this->rows[$this->pendingRowIndex], [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'product_code' => $displayCode,
                'cost' => (float) $product->product_cost,
                'sell' => $product->product_price ? (float) $product->product_price : null,
                'total' => $this->rows[$this->pendingRowIndex]['qty'] * (float) $product->product_cost,
            ]);
            $this->searchQueries[$this->pendingRowIndex] = $product->product_name;
        }

        $this->closeModal();
        $this->dispatch('toast', message: 'تم حفظ المنتج "'.$product->product_name.'" وإضافته للفاتورة', type: 'success');
    }

    public function saveInvoice(): void
    {
        $this->saving = true;

        $this->validate([
            'invoice_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('purchase_invoices', 'invoice_number')->whereNull('deleted_at'),
            ],
            'purchase_invoice_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_image' => 'nullable|image|max:5120',
            'paid_amount' => 'required|numeric|min:0',
        ], [
            'invoice_number.required' => 'رقم الفاتورة مطلوب',
            'invoice_number.unique' => 'رقم الفاتورة مستخدم بالفعل',
            'purchase_invoice_date.required' => 'تاريخ الفاتورة مطلوب',
            'supplier_id.required' => 'المورد مطلوب',
            'supplier_id.exists' => 'المورد غير موجود',
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
        ]);

        if (empty($this->rows)) {
            $this->addError('rows', 'يجب إضافة صنف واحد على الأقل');
            $this->saving = false;

            return;
        }

        foreach ($this->rows as $i => $row) {
            if (empty($row['product_id'])) {
                $this->addError("rows.{$i}.product_id", 'يجب اختيار منتج للصف '.($i + 1));
                $this->saving = false;

                return;
            }
            if (($row['qty'] ?? 0) <= 0) {
                $this->addError("rows.{$i}.qty", 'الكمية يجب أن تكون أكبر من صفر للصف '.($i + 1));
                $this->saving = false;

                return;
            }
            if (($row['cost'] ?? 0) <= 0) {
                $this->addError("rows.{$i}.cost", 'سعر التكلفة يجب أن يكون أكبر من صفر للصف '.($i + 1));
                $this->saving = false;

                return;
            }
        }

        try {
            DB::transaction(function () {
                $imagePath = null;
                if ($this->product_image) {
                    $imagePath = $this->product_image->store('purchase-invoices', 'public');
                }

                $total = $this->invoiceTotal;
                $remaining = max(0, $total - $this->paid_amount);

                $invoice = PurchaseInvoice::create([
                    'invoice_number' => $this->invoice_number,
                    'purchase_invoice_date' => $this->purchase_invoice_date,
                    'total_amount' => $total,
                    'paid_amount' => $this->paid_amount,
                    'remaining_amount' => $remaining,
                    'payment_method' => $this->payment_method,
                    'product_image' => $imagePath,
                    'supplier_id' => $this->supplier_id,
                    'branch_id' => auth()->user()?->branch_id ?? 1,
                    'user_id' => auth()->id(),
                ]);

                foreach ($this->rows as $row) {
                    PurchaseInvoiceDetail::create([
                        'purchase_invoice_id' => $invoice->id,
                        'product_id' => $row['product_id'],
                        'product_quantity' => $row['qty'],
                        'cost_per_piece' => $row['cost'],
                    ]);

                    $product = Product::query()->find($row['product_id']);
                    if ($product) {
                        $product->increment('product_quantity', $row['qty']);
                        $product->update(['product_cost' => $row['cost']]);
                        if (! empty($row['sell'])) {
                            $product->update(['product_price' => $row['sell']]);
                        }
                    }
                }
            });

            $this->dispatch('toast', message: 'تم حفظ الفاتورة بنجاح!', type: 'success');
            $this->redirect(route('createpurchaseInvoices'), navigate: true);
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'حدث خطأ أثناء الحفظ: '.$e->getMessage(), type: 'error');
        } finally {
            $this->saving = false;
        }
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'INV-'.$year.'-';

        $latest = PurchaseInvoice::withTrashed()
            ->where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $nextSeq = 1;
        if ($latest && preg_match('/^INV-\d{4}-(\d{5})$/', $latest, $m)) {
            $nextSeq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);
    }

};
?>
<div>


    {{-- resources/views/livewire/purchase-invoices/create-purchase-invoice.blade.php --}}
<div
    x-data="{}"
    @toast.window="
        const ev = $event.detail[0] ?? $event.detail;
        const msg = ev.message ?? '';
        const type = ev.type ?? 'success';
        window.__showToast && window.__showToast(msg, type);
    "
    class="pi-root"
>

{{-- ══════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════ --}}
<div class="pi-page-header">
    <div class="pi-page-header-inner">
        <div class="pi-page-header-left">
            <div class="pi-header-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="pi-page-title">فاتورة مشتريات جديدة</h1>
                <p class="pi-page-sub">المشتريات &larr; فاتورة جديدة</p>
            </div>
        </div>
        <span class="pi-inv-badge">{{ $invoice_number }}</span>
    </div>
</div>

<div class="pi-body">

{{-- ══════════════════════════════════════════════════════════
     SECTION 1 — INVOICE BASIC INFO
══════════════════════════════════════════════════════════ --}}
<div class="pi-card">
    <div class="pi-sec-hd">
        <span class="pi-step">١</span>
        <div>
            <div class="pi-sec-title">بيانات الفاتورة الأساسية</div>
            <div class="pi-sec-sub">معلومات الفاتورة والمورد</div>
        </div>
    </div>

    <div class="pi-grid4">

        {{-- Invoice Number --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                رقم الفاتورة <span class="pi-req">*</span>
            </label>
            <input
                type="text"
                wire:model.live="invoice_number"
                class="pi-input @error('invoice_number') pi-input-err @enderror"
                placeholder="INV-{{ now()->format('Y') }}-00001"
                dir="ltr"
            >
            @error('invoice_number')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Date --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                تاريخ الفاتورة <span class="pi-req">*</span>
            </label>
            <input
                type="date"
                wire:model.live="purchase_invoice_date"
                class="pi-input @error('purchase_invoice_date') pi-input-err @enderror"
                dir="ltr"
            >
            @error('purchase_invoice_date')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Supplier --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3"/></svg>
                المورد <span class="pi-req">*</span>
            </label>
            <select
                wire:model.live="supplier_id"
                class="pi-input pi-select @error('supplier_id') pi-input-err @enderror"
            >
                <option value="">-- اختر المورد --</option>
                @foreach($this->suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Invoice Image --}}
        <div class="pi-field">
            <label class="pi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                صورة الفاتورة
                <span class="pi-optional">(اختياري)</span>
            </label>

            @if($imagePreview)
                <div class="pi-img-preview-wrap">
                    <img src="{{ $imagePreview }}" class="pi-img-preview" alt="صورة الفاتورة">
                    <button type="button" wire:click="removeImage" class="pi-img-remove" title="إزالة الصورة">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            @else
                <label class="pi-upload-zone" for="invoice-image-input">
                    <input
                        type="file"
                        id="invoice-image-input"
                        wire:model="product_image"
                        accept="image/*"
                        class="pi-upload-input"
                    >
                    <svg width="24" height="24" fill="none" stroke="var(--tx3)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:auto">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                    </svg>
                    <p class="pi-upload-txt">اضغط لرفع الصورة</p>
                    <p class="pi-upload-hint">PNG، JPG أو WebP حتى 5MB</p>
                    <div wire:loading wire:target="product_image" class="pi-upload-loading">
                        <span class="pi-spinner"></span> جاري الرفع...
                    </div>
                </label>
            @endif
            @error('product_image')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

    </div>{{-- /grid4 --}}
</div>{{-- /card --}}

{{-- ══════════════════════════════════════════════════════════
     SECTION 2 — PRODUCTS TABLE
══════════════════════════════════════════════════════════ --}}
<div class="pi-card">
    <div class="pi-sec-hd">
        <span class="pi-step">٢</span>
        <div>
            <div class="pi-sec-title">أصناف الفاتورة</div>
            <div class="pi-sec-sub">أضف المنتجات وحدد الكميات والأسعار</div>
        </div>
        <span class="pi-row-count" style="margin-right:auto">{{ count($rows) }} {{ count($rows) === 1 ? 'صنف' : 'أصناف' }}</span>
    </div>

    @error('rows')
        <div class="pi-alert-warn" style="margin-bottom:12px">⚠ {{ $message }}</div>
    @enderror

    <div class="pi-table-wrap" style="height: 400px;">
        <table class="pi-table">
            <thead>
                <tr>
                    <th style="width:36px">#</th>
                    <th style="min-width:220px">المنتج</th>
                    <th style="width:88px">الكمية</th>
                    <th style="width:120px">سعر التكلفة</th>
                    <th style="width:120px">سعر البيع</th>
                    <th style="width:100px">الإجمالي</th>
                    <th style="width:46px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $index => $row)
                <tr wire:key="row-{{ $row['id'] }}" class="pi-tr">
                    {{-- # --}}
                    <td class="pi-td-num">{{ $index + 1 }}</td>

                    {{-- Product search --}}
                    <td style="position:relative">
                        <div style="position:relative">
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="searchQueries.{{ $index }}"
                                wire:focus="$set('openDropdowns.{{ $index }}', true)"
                                class="pi-ti @error("rows.{$index}.product_id") pi-ti-err @enderror"
                                placeholder="ابحث بالاسم أو الكود..."
                                autocomplete="off"
                            >
                            @if($row['product_id'])
                                <span class="pi-prod-badge">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    {{ $row['product_code'] }}
                                </span>
                            @endif

                            {{-- Loading indicator --}}
                            <span wire:loading wire:target="searchQueries" class="pi-search-loading">
                                <span class="pi-spinner-sm"></span>
                            </span>
                        </div>

                        {{-- Autocomplete dropdown --}}
                        @if(($openDropdowns[$index] ?? false) && (count($searchResults[$index] ?? []) > 0 || strlen(trim($searchQueries[$index] ?? '')) > 0))
                        <div class="pi-dropdown" wire:click.stop>
                            @forelse($searchResults[$index] ?? [] as $product)
                                <div
                                    class="pi-drop-item"
                                    wire:click="selectProduct({{ $index }}, {{ $product['id'] }})"
                                    wire:key="prod-{{ $product['id'] }}"
                                >
                                    <div style="flex:1">
                                        <div class="pi-drop-name">{{ $product['product_name'] }}</div>
                                        <div class="pi-drop-cat">{{ $product['category']['category_name'] ?? '' }}</div>
                                    </div>
                                    <span class="pi-drop-code">{{ $product['product_code'] ? $product['product_code'] : 'PRD-'.str_pad($product['id'], 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @empty
                                <div style="padding:10px 14px; font-size:12px; color:var(--tx3); text-align:center">لا توجد نتائج</div>
                            @endforelse

                            {{-- Add New Product --}}
                            <div
                                class="pi-drop-add"
                                wire:click="openAddProductModal({{ $index }}, @js(trim($searchQueries[$index] ?? '')))"
                            >
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                                إضافة "{{ trim($searchQueries[$index] ?? '') }}" كمنتج جديد
                            </div>
                        </div>
                        @endif

                        @error("rows.{$index}.product_id")
                            <span class="pi-err-msg">⚠ {{ $message }}</span>
                        @enderror
                    </td>

                    {{-- Quantity --}}
                    <td>
                        <input
                            type="number"
                            wire:change="updateRowQty({{ $index }}, $event.target.value)"
                            class="pi-ti pi-ti-sm @error("rows.{$index}.qty") pi-ti-err @enderror"
                            value="{{ $row['qty'] }}"
                            min="0.01" step="0.01"
                        >
                        @error("rows.{$index}.qty")
                            <span class="pi-err-msg" style="font-size:10px">⚠ {{ $message }}</span>
                        @enderror
                    </td>

                    {{-- Cost Price --}}
                    <td>
                        <div style="position:relative">
                            <input
                                type="number"
                                wire:change="updateRowCost({{ $index }}, $event.target.value)"
                                class="pi-ti @error("rows.{$index}.cost") pi-ti-err @enderror"
                                value="{{ $row['cost'] }}"
                                min="0" step="0.01"
                                style="padding-left:34px"
                            >
                            <span class="pi-curr">ج.م</span>
                        </div>
                        @error("rows.{$index}.cost")
                            <span class="pi-err-msg" style="font-size:10px">⚠ {{ $message }}</span>
                        @enderror
                    </td>

                    {{-- Selling Price --}}
                    <td>
                        <div style="position:relative">
                            <input
                                type="number"
                                wire:change="updateRowSell({{ $index }}, $event.target.value)"
                                class="pi-ti"
                                value="{{ $row['sell'] ?? '' }}"
                                min="0" step="0.01"
                                placeholder="—"
                                style="padding-left:34px"
                            >
                            <span class="pi-curr">ج.م</span>
                        </div>
                    </td>

                    {{-- Row Total --}}
                    <td>
                        <span class="pi-tot-cell">
                            {{ number_format(($row['qty'] ?? 0) * ($row['cost'] ?? 0), 2) }}
                        </span>
                    </td>

                    {{-- Remove --}}
                    <td>
                        <button
                            type="button"
                            wire:click="removeRow({{ $index }})"
                            class="pi-btn-rm"
                            title="حذف الصنف"
                        >
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="pi-empty-state">
                        <div class="pi-empty-icon">
                            <svg width="22" height="22" fill="none" stroke="var(--tx3)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                        </div>
                        <p>لا توجد أصناف — اضغط "إضافة صنف جديد"</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <button type="button" wire:click="addRow" class="pi-btn-add-row">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        إضافة صنف جديد
    </button>
</div>{{-- /card --}}

{{-- ══════════════════════════════════════════════════════════
     SECTION 3 — SUMMARY + PAYMENT
══════════════════════════════════════════════════════════ --}}
<div class="pi-bottom-grid">

    {{-- Summary Cards --}}
    <div class="pi-sum-cards">
        <div class="pi-sum-card">
            <div class="pi-sum-lbl">إجمالي الفاتورة</div>
            <div class="pi-sum-val pi-sum-acc">{{ number_format($this->invoiceTotal, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
        <div class="pi-sum-card">
            <div class="pi-sum-lbl">المبلغ المدفوع</div>
            <div class="pi-sum-val pi-sum-grn">{{ number_format($paid_amount, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
        <div class="pi-sum-card pi-sum-card-remaining {{ $this->remainingAmount > 0 ? 'pi-sum-card-warn' : 'pi-sum-card-ok' }}">
            <div class="pi-sum-lbl">المتبقي</div>
            <div class="pi-sum-val {{ $this->remainingAmount > 0 ? 'pi-sum-yel' : 'pi-sum-grn' }}">{{ number_format($this->remainingAmount, 2) }}</div>
            <div class="pi-sum-unit">جنيه مصري</div>
        </div>
    </div>

    {{-- Payment Panel --}}
    <div class="pi-card pi-payment-card">
        <div class="pi-sec-mini-title">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
            بيانات الدفع
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">إجمالي الفاتورة</label>
            <div class="pi-readonly-field">{{ number_format($this->invoiceTotal, 2) }} ج.م</div>
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">طريقة الدفع <span class="pi-req">*</span></label>
            <select wire:model.live="payment_method" class="pi-input pi-select">
                <option value="cash">نقدي</option>
                <option value="bank_transfer">تحويل بنكي</option>
                <option value="check">شيك</option>
                <option value="credit">آجل</option>
            </select>
        </div>

        <div class="pi-field" style="margin-bottom:10px">
            <label class="pi-label">المبلغ المدفوع <span class="pi-req">*</span></label>
            <div style="position:relative">
                <input
                    type="number"
                    wire:model.live="paid_amount"
                    class="pi-input @error('paid_amount') pi-input-err @enderror"
                    min="0" step="0.01"
                    placeholder="0.00"
                    style="padding-left:42px"
                    dir="ltr"
                >
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
            </div>
            @error('paid_amount')
                <span class="pi-err-msg">⚠ {{ $message }}</span>
            @enderror
        </div>

        <div class="pi-field">
            <label class="pi-label">المتبقي</label>
            <div class="pi-remaining-field {{ $this->remainingAmount > 0 ? 'pi-rem-yel' : 'pi-rem-grn' }}">
                {{ number_format($this->remainingAmount, 2) }} ج.م
            </div>
        </div>
    </div>

</div>{{-- /bottom-grid --}}

{{-- ══════════════════════════════════════════════════════════
     ACTION FOOTER
══════════════════════════════════════════════════════════ --}}
<div class="pi-card pi-footer-card">
    <div class="pi-footer-inner">
        <div class="pi-footer-meta">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            آخر تحديث: الآن
        </div>
        <div class="pi-footer-btns">
            <a href="{{ route('purchaseInvoices') }}" wire:navigate class="pi-btn-sec">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                إلغاء
            </a>
            <button
                type="button"
                wire:click="saveInvoice"
                wire:loading.attr="disabled"
                class="pi-btn-pri"
            >
                <span wire:loading.remove wire:target="saveInvoice">
                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    حفظ الفاتورة
                </span>
                <span wire:loading wire:target="saveInvoice">
                    <span class="pi-spinner"></span>
                    جاري الحفظ...
                </span>
            </button>
        </div>
    </div>
</div>

</div>{{-- /pi-body --}}


{{-- ══════════════════════════════════════════════════════════
     MODAL — ADD NEW PRODUCT
══════════════════════════════════════════════════════════ --}}
@if($showModal)
<div
    class="pi-modal-bg"
    x-data
    x-init="$el.style.opacity=0; requestAnimationFrame(()=>{ $el.style.transition='opacity .2s'; $el.style.opacity=1; })"
    wire:click.self="closeModal"
    @keydown.escape.window="$wire.closeModal()"
>
    <div
        class="pi-modal"
        x-data
        x-init="$el.style.transform='scale(0.93)'; $el.style.opacity=0; requestAnimationFrame(()=>{ $el.style.transition='all .22s cubic-bezier(.34,1.56,.64,1)'; $el.style.transform='scale(1)'; $el.style.opacity=1; })"
        wire:click.stop
    >
        {{-- Modal Header --}}
        <div class="pi-modal-hd">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="pi-modal-icon">
                    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--tx)">إضافة منتج جديد</div>
                    <div style="font-size:11px;color:var(--tx3)">سيتم إضافته في الصف الحالي تلقائياً</div>
                </div>
            </div>
            <button type="button" wire:click="closeModal" class="pi-modal-close">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="pi-modal-body">
            <div class="pi-grid2">

                {{-- Product Name --}}
                <div class="pi-field pi-col-full">
                    <label class="pi-label">اسم المنتج <span class="pi-req">*</span></label>
                    <input
                        type="text"
                        wire:model.live="newProductName"
                        class="pi-input @error('newProductName') pi-input-err @enderror"
                        placeholder="مثال: لاب توب HP ProBook 450"
                        autofocus
                    >
                    @error('newProductName')
                        <span class="pi-err-msg">⚠ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Product Code --}}
                <div class="pi-field">
                    <label class="pi-label">كود المنتج <span class="pi-req">*</span></label>
                    <input
                        type="text"
                        wire:model.live="newProductCode"
                        class="pi-input @error('newProductCode') pi-input-err @enderror"
                        placeholder="HP-001"
                        dir="ltr"
                    >
                    @error('newProductCode')
                        <span class="pi-err-msg">⚠ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="pi-field">
                    <label class="pi-label">التصنيف <span class="pi-req">*</span></label>
                    <select
                        wire:model.live="newProductCategoryId"
                        class="pi-input pi-select @error('newProductCategoryId') pi-input-err @enderror"
                    >
                        <option value="">-- اختر التصنيف --</option>
                        @foreach($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    @error('newProductCategoryId')
                        <span class="pi-err-msg">⚠ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Cost Price --}}
                <div class="pi-field">
                    <label class="pi-label">سعر التكلفة <span class="pi-req">*</span></label>
                    <div style="position:relative">
                        <input
                            type="number"
                            wire:model.live="newProductCost"
                            class="pi-input @error('newProductCost') pi-input-err @enderror"
                            min="0" step="0.01" placeholder="0.00"
                            style="padding-left:42px" dir="ltr"
                        >
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
                    </div>
                    @error('newProductCost')
                        <span class="pi-err-msg">⚠ {{ $message }}</span>
                    @enderror
                </div>

                {{-- Selling Price --}}
                <div class="pi-field">
                    <label class="pi-label">
                        سعر البيع
                        <span class="pi-optional">(اختياري)</span>
                    </label>
                    <div style="position:relative">
                        <input
                            type="number"
                            wire:model.live="newProductSell"
                            class="pi-input"
                            min="0" step="0.01" placeholder="0.00"
                            style="padding-left:42px" dir="ltr"
                        >
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:var(--tx3);pointer-events:none">ج.م</span>
                    </div>
                </div>

            </div>

            {{-- Info hint --}}
            <div class="pi-hint-box">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <span class="pi-hint-txt">بعد الحفظ سيتم إضافة المنتج تلقائياً في الصف الحالي بالفاتورة وتحديث الكميات.</span>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="pi-modal-ft">
            <button type="button" wire:click="closeModal" class="pi-btn-sec" style="padding:8px 16px;font-size:12px">إلغاء</button>
            <button
                type="button"
                wire:click="saveNewProduct"
                wire:loading.attr="disabled"
                class="pi-btn-pri"
                style="padding:8px 16px;font-size:12px"
            >
                <span wire:loading.remove wire:target="saveNewProduct">
                    <svg width="13" height="13" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    حفظ المنتج
                </span>
                <span wire:loading wire:target="saveNewProduct">
                    <span class="pi-spinner"></span>
                    جاري الحفظ...
                </span>
            </button>
        </div>
    </div>
</div>
@endif


{{-- ══════════════════════════════════════════════════════════
     TOAST NOTIFICATION
══════════════════════════════════════════════════════════ --}}
<div id="pi-toast" class="pi-toast" aria-live="polite"></div>

</div>{{-- /pi-root --}}

@push('styles')
<style>
/* ── Reset & theme tokens (follows Flux / Tailwind: html.dark) ─ */
.pi-root *{box-sizing:border-box}
/* Light (default) */
.pi-root{
    --bg:#f4f4f5;
    --card:#ffffff;
    --surf:#fafafa;
    --hover:#f4f4f5;
    --brd:rgba(24,24,27,0.1);
    --brd2:rgba(24,24,27,0.14);
    --acc:#4f6ef7;
    --acc-s:rgba(79,110,247,0.1);
    --acc-g:rgba(79,110,247,0.2);
    --acc-bd:rgba(79,110,247,0.32);
    --acc-drop-hover:rgba(79,110,247,0.14);
    --grn:#16a34a;
    --grn-s:rgba(22,163,74,0.12);
    --grn-bd:rgba(22,163,74,0.35);
    --grn-hover:rgba(22,163,74,0.18);
    --red:#dc2626;
    --red-s:rgba(220,38,38,0.1);
    --red-bd:rgba(220,38,38,0.22);
    --red-hover:rgba(220,38,38,0.18);
    --yel:#d97706;
    --tx:#18181b;
    --tx2:#52525b;
    --tx3:#71717a;
    --req:#dc2626;
    --input-bg:rgba(24,24,27,0.04);
    --input-bg-focus:#ffffff;
    --readonly-bg:rgba(24,24,27,0.05);
    --row-border:rgba(24,24,27,0.08);
    --upload-zone-bg:rgba(24,24,27,0.03);
    --upload-loading-bg:rgba(255,255,255,0.92);
    --modal-scrim:rgba(15,23,42,0.45);
    --modal-ft-bg:rgba(24,24,27,0.04);
    --dropdown-shadow:0 12px 36px rgba(0,0,0,0.12);
    --toast-shadow:0 8px 24px rgba(0,0,0,0.1);
    --hint-fg:#4f6ef7;
    --hint-bd:rgba(79,110,247,0.22);
    --thead-th-bg:rgba(79,110,247,0.07);
    --err-ring:rgba(239,68,68,0.18);
    --sum-warn-bd:rgba(217,119,6,0.45);
    --sum-ok-bd:rgba(22,163,74,0.45);
    --page-hd-grad:rgba(79,110,247,0.07);
    --modal-hd-grad:rgba(79,110,247,0.1);
    background:var(--bg);color:var(--tx);direction:rtl;min-height:100vh;
    color-scheme:light;
    transition:background-color .2s ease,color .2s ease;
}
/* Dark: Flux toggles `dark` on <html>; .pi-dark mirrors it for instant Livewire updates */
.dark .pi-root,
html.dark .pi-root,
.pi-root.pi-dark{
    --bg:#0f1117;
    --card:#1a1d27;
    --surf:#21253a;
    --hover:#272b40;
    --brd:rgba(255,255,255,0.08);
    --brd2:rgba(255,255,255,0.14);
    --acc:#4f6ef7;
    --acc-s:rgba(79,110,247,0.12);
    --acc-g:rgba(79,110,247,0.22);
    --acc-bd:rgba(79,110,247,0.28);
    --acc-drop-hover:rgba(79,110,247,0.22);
    --grn:#22c55e;
    --grn-s:rgba(34,197,94,0.12);
    --grn-bd:rgba(34,197,94,0.28);
    --grn-hover:rgba(34,197,94,0.22);
    --red:#ef4444;
    --red-s:rgba(239,68,68,0.1);
    --red-bd:rgba(239,68,68,0.18);
    --red-hover:rgba(239,68,68,0.22);
    --yel:#f59e0b;
    --tx:#e8eaf6;
    --tx2:#8892b0;
    --tx3:#6b7288;
    --req:#f87171;
    --input-bg:rgba(0,0,0,0.35);
    --input-bg-focus:rgba(0,0,0,0.5);
    --readonly-bg:rgba(0,0,0,0.3);
    --row-border:rgba(46,51,80,0.5);
    --upload-zone-bg:rgba(0,0,0,0.3);
    --upload-loading-bg:rgba(15,17,23,0.75);
    --modal-scrim:rgba(0,0,0,0.7);
    --modal-ft-bg:rgba(0,0,0,0.2);
    --dropdown-shadow:0 12px 36px rgba(0,0,0,0.55);
    --toast-shadow:0 8px 28px rgba(0,0,0,0.45);
    --hint-fg:#a5b4fc;
    --hint-bd:rgba(79,110,247,0.25);
    --thead-th-bg:rgba(79,110,247,0.08);
    --err-ring:rgba(239,68,68,0.15);
    --sum-warn-bd:rgba(245,158,11,0.35);
    --sum-ok-bd:rgba(34,197,94,0.35);
    --page-hd-grad:rgba(79,110,247,0.09);
    --modal-hd-grad:rgba(79,110,247,0.13);
    color-scheme:dark;
}
/* ── Page Header ─────────────────────────────────────────────── */
.pi-page-header{background:linear-gradient(135deg,var(--page-hd-grad),transparent);border-bottom:1px solid var(--brd);padding:14px 20px}
.pi-page-header-inner{display:flex;align-items:center;justify-content:space-between;max-width:1200px;margin:auto}
.pi-page-header-left{display:flex;align-items:center;gap:12px}
.pi-header-icon{width:38px;height:38px;color:var(--acc);background:var(--acc-s);border:1px solid var(--acc-bd);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pi-page-title{font-size:15px;font-weight:700;color:var(--tx)}
.pi-page-sub{font-size:11px;color:var(--tx3)}
.pi-inv-badge{font-family:monospace;font-weight:700;color:var(--acc);font-size:13px;background:var(--acc-s);padding:4px 12px;border-radius:6px;border:1px solid var(--acc-bd)}
/* ── Body ─────────────────────────────────────────────────────── */
.pi-body{padding:18px 20px;max-width:1200px;margin:auto;display:flex;flex-direction:column;gap:14px}
/* ── Card ─────────────────────────────────────────────────────── */
.pi-card{background:var(--card);border:1px solid var(--brd);border-radius:14px;padding:18px}
/* ── Section Header ───────────────────────────────────────────── */
.pi-sec-hd{display:flex;align-items:center;gap:10px;margin-bottom:16px}
.pi-step{width:26px;height:26px;background:var(--acc);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
.pi-sec-title{font-size:13px;font-weight:700;color:var(--tx)}
.pi-sec-sub{font-size:11px;color:var(--tx3)}
.pi-row-count{font-size:12px;color:var(--tx3)}
.pi-sec-mini-title{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:700;margin-bottom:12px;color:var(--tx)}
.pi-sec-mini-title svg{flex-shrink:0;color:var(--acc)}
/* ── Grids ─────────────────────────────────────────────────────── */
.pi-grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.pi-grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.pi-col-full{grid-column:1/-1}
@media(max-width:900px){.pi-grid4{grid-template-columns:1fr 1fr}}
@media(max-width:540px){.pi-grid4{grid-template-columns:1fr}.pi-grid2{grid-template-columns:1fr}}
/* ── Form Fields ───────────────────────────────────────────────── */
.pi-field{display:flex;flex-direction:column;gap:5px}
.pi-label{font-size:12px;font-weight:600;color:var(--tx2);display:flex;align-items:center;gap:5px}
.pi-req{color:var(--req);font-size:13px}
.pi-optional{font-size:10px;color:var(--tx3)}
.pi-input,.pi-select{width:100%;background:var(--input-bg);border:1px solid var(--brd);border-radius:9px;padding:9px 12px;font-size:13px;color:var(--tx);outline:none;transition:border .18s,box-shadow .18s,background .18s;-webkit-appearance:none;appearance:none}
.pi-input:focus,.pi-select:focus{border-color:var(--acc);box-shadow:0 0 0 3px var(--acc-g)}
.pi-input::placeholder{color:var(--tx3)}
.pi-select option{background:var(--card);color:var(--tx)}
.pi-input-err{border-color:var(--red)!important;box-shadow:0 0 0 3px var(--err-ring)!important}
.pi-err-msg{font-size:11px;color:var(--req);display:flex;align-items:center;gap:3px}
.pi-readonly-field{background:var(--readonly-bg);border:1px solid var(--brd);border-radius:9px;padding:9px 12px;font-size:13px;font-weight:700;color:var(--acc)}
.pi-remaining-field{border-radius:9px;padding:9px 12px;font-size:13px;font-weight:700;transition:all .3s}
.pi-rem-yel{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--yel)}
.pi-rem-grn{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:var(--grn)}
/* ── Upload ─────────────────────────────────────────────────────── */
.pi-upload-zone{border:1.5px dashed var(--brd);border-radius:9px;padding:18px 12px;text-align:center;cursor:pointer;transition:all .2s;background:var(--upload-zone-bg);display:block;position:relative}
.pi-upload-zone:hover{border-color:var(--acc);background:var(--acc-s)}
.pi-upload-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.pi-upload-txt{font-size:12px;color:var(--tx3);margin-top:5px}
.pi-upload-hint{font-size:10px;color:var(--tx3);margin-top:2px}
.pi-upload-loading{position:absolute;inset:0;background:var(--upload-loading-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;gap:8px;font-size:12px;color:var(--acc)}
.pi-img-preview-wrap{position:relative}
.pi-img-preview{width:100%;height:120px;object-fit:cover;border-radius:9px;border:1px solid var(--brd)}
.pi-img-remove{position:absolute;top:6px;left:6px;width:26px;height:26px;background:rgba(239,68,68,.85);border:none;border-radius:6px;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s}
.pi-img-remove:hover{background:var(--red);transform:scale(1.08)}
/* ── Table ─────────────────────────────────────────────────────── */
.pi-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid var(--brd)}
.pi-table{width:100%;border-collapse:collapse}
.pi-table thead th{background:var(--thead-th-bg);padding:10px 10px;font-size:11px;font-weight:700;color:var(--tx2);text-align:right;border-bottom:1px solid var(--brd);white-space:nowrap}
.pi-table thead th:first-child{border-radius:0 9px 0 0}
.pi-table thead th:last-child{border-radius:9px 0 0 0}
.pi-tr{border-bottom:1px solid var(--row-border);transition:background .15s;animation:piRowIn .22s ease both}
.pi-tr:hover{background:var(--hover)}
.pi-td-num{text-align:center;font-size:12px;font-weight:700;color:var(--tx3);padding:8px 6px}
/* ── Table Inputs ─────────────────────────────────────────────── */
.pi-ti{width:100%;background:var(--input-bg);border:1px solid var(--brd);border-radius:7px;padding:7px 9px;font-size:12px;color:var(--tx);outline:none;transition:border .15s,box-shadow .15s,background .15s}
.pi-ti:focus{border-color:var(--acc);background:var(--input-bg-focus);box-shadow:0 0 0 2px var(--acc-g)}
.pi-ti::placeholder{color:var(--tx3)}
.pi-ti-sm{max-width:72px}
.pi-ti-err{border-color:var(--red)!important}
.pi-curr{position:absolute;left:9px;top:50%;transform:translateY(-50%);font-size:10px;font-weight:700;color:var(--tx3);pointer-events:none}
.pi-tot-cell{font-weight:700;color:var(--acc);font-size:12px;padding:6px 10px;background:var(--acc-s);border-radius:7px;display:inline-block;min-width:72px;text-align:center}
.pi-prod-badge{position:absolute;top:50%;right:6px;transform:translateY(-50%);background:var(--grn-s);border:1px solid var(--grn-bd);border-radius:5px;padding:1px 6px;font-size:10px;font-weight:700;color:var(--grn);display:flex;align-items:center;gap:3px;pointer-events:none}
.pi-btn-rm{width:30px;height:30px;border-radius:7px;background:var(--red-s);border:1px solid var(--red-bd);color:var(--red);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;margin:auto}
.pi-btn-rm:hover{background:var(--red-hover);border-color:var(--red)}
.pi-btn-add-row{background:var(--grn-s);color:var(--grn);border:1px dashed var(--grn-bd);border-radius:9px;padding:8px 18px;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s;margin-top:12px}
.pi-btn-add-row:hover{background:var(--grn-hover);border-color:var(--grn)}
/* ── Autocomplete Dropdown ────────────────────────────────────── */
.pi-dropdown{position:absolute;top:calc(100% + 4px);right:0;left:0;background:var(--card);border:1px solid var(--brd2);border-radius:9px;box-shadow:var(--dropdown-shadow);z-index:50;overflow:hidden;max-height:190px;overflow-y:auto}
.pi-drop-item{padding:9px 12px;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background .13s;border-bottom:1px solid var(--row-border)}
.pi-drop-item:hover{background:var(--hover)}
.pi-drop-name{font-size:12px;font-weight:600;color:var(--tx)}
.pi-drop-cat{font-size:10px;color:var(--tx3)}
.pi-drop-code{font-size:10px;font-weight:700;background:var(--surf);padding:2px 7px;border-radius:4px;color:var(--tx2)}
.pi-drop-add{padding:9px 12px;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:7px;color:var(--acc);font-weight:700;background:var(--acc-s);transition:background .13s}
.pi-drop-add:hover{background:var(--acc-drop-hover)}
.pi-search-loading{position:absolute;left:9px;top:50%;transform:translateY(-50%)}
/* ── Empty State ─────────────────────────────────────────────── */
.pi-empty-state{padding:36px;text-align:center;color:var(--tx3);font-size:13px}
.pi-empty-icon{width:44px;height:44px;background:var(--surf);border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid var(--brd);margin:0 auto 10px}
/* ── Bottom Grid ─────────────────────────────────────────────── */
.pi-bottom-grid{display:grid;grid-template-columns:1fr 320px;gap:14px;align-items:start}
@media(max-width:800px){.pi-bottom-grid{grid-template-columns:1fr}}
.pi-sum-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.pi-sum-card{background:var(--surf);border:1px solid var(--brd);border-radius:11px;padding:14px 16px;transition:border-color .3s}
.pi-sum-card-warn{border-color:var(--sum-warn-bd)!important}
.pi-sum-card-ok{border-color:var(--sum-ok-bd)!important}
.pi-sum-lbl{font-size:11px;font-weight:700;color:var(--tx3);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px}
.pi-sum-val{font-size:19px;font-weight:800;direction:ltr;text-align:right}
.pi-sum-acc{color:var(--acc)}
.pi-sum-grn{color:var(--grn)}
.pi-sum-yel{color:var(--yel)}
.pi-sum-unit{font-size:10px;color:var(--tx3);margin-top:2px}
.pi-payment-card{margin:0!important}
/* ── Alert ───────────────────────────────────────────────────── */
.pi-alert-warn{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:9px;padding:10px 14px;font-size:12px;color:var(--yel)}
/* ── Footer ───────────────────────────────────────────────────── */
.pi-footer-card{padding:14px 18px}
.pi-footer-inner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.pi-footer-meta{font-size:12px;color:var(--tx3);display:flex;align-items:center;gap:6px}
.pi-footer-btns{display:flex;align-items:center;gap:10px}
/* ── Buttons ─────────────────────────────────────────────────── */
.pi-btn-pri{background:var(--acc);color:#fff;border:none;border-radius:9px;padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .2s;text-decoration:none}
.pi-btn-pri:hover{background:#3b5cf4;transform:translateY(-1px)}
.pi-btn-pri:disabled{opacity:.55;cursor:not-allowed;transform:none}
.pi-btn-sec{background:var(--surf);color:var(--tx);border:1px solid var(--brd);border-radius:9px;padding:10px 18px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .2s;text-decoration:none}
.pi-btn-sec:hover{background:var(--hover);border-color:var(--acc)}
/* ── Modal ───────────────────────────────────────────────────── */
.pi-modal-bg{position:fixed;inset:0;background:var(--modal-scrim);backdrop-filter:blur(3px);z-index:200;display:flex;align-items:center;justify-content:center;padding:16px}
.pi-modal{background:var(--card);border:1px solid var(--brd2);border-radius:18px;width:100%;max-width:470px;overflow:hidden}
.pi-modal-hd{background:linear-gradient(135deg,var(--modal-hd-grad),transparent);border-bottom:1px solid var(--brd);padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.pi-modal-icon{width:32px;height:32px;background:var(--acc);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pi-modal-close{width:30px;height:30px;border-radius:7px;background:var(--surf);border:1px solid var(--brd);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--tx2);transition:color .2s}
.pi-modal-close:hover{color:var(--red)}
.pi-modal-body{padding:18px 20px;display:flex;flex-direction:column;gap:12px}
.pi-modal-ft{padding:14px 20px;border-top:1px solid var(--brd);display:flex;justify-content:flex-end;gap:10px;background:var(--modal-ft-bg)}
.pi-hint-box{background:var(--acc-s);border:1px solid var(--hint-bd);border-radius:9px;padding:9px 12px;display:flex;gap:8px;align-items:flex-start}
.pi-hint-box svg{color:var(--hint-fg);flex-shrink:0}
.pi-hint-txt{font-size:12px;color:var(--hint-fg);line-height:1.6}
/* ── Toast ───────────────────────────────────────────────────── */
.pi-toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(14px);background:var(--card);border:1px solid var(--grn);border-radius:11px;padding:11px 20px;display:flex;align-items:center;gap:9px;box-shadow:var(--toast-shadow);z-index:999;font-size:13px;font-weight:600;color:var(--tx);opacity:0;transition:all .35s cubic-bezier(.34,1.56,.64,1);pointer-events:none;white-space:nowrap}
.pi-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
/* ── Spinner ─────────────────────────────────────────────────── */
.pi-spinner{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.2);border-top-color:#fff;border-radius:50%;animation:piSpin .7s linear infinite}
.pi-spinner-sm{display:inline-block;width:12px;height:12px;border:2px solid var(--acc-s);border-top-color:var(--acc);border-radius:50%;animation:piSpin .7s linear infinite}
/* ── Animations ──────────────────────────────────────────────── */
@keyframes piRowIn{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}
@keyframes piSpin{to{transform:rotate(360deg)}}
</style>
@endpush

@push('scripts')
<script>
window.__toastTimer = null;
window.__showToast = function(msg, type) {
    const el = document.getElementById('pi-toast');
    if (!el) return;
    el.textContent = msg;
    const colors = { success: '#22c55e', warning: '#f59e0b', error: '#ef4444', info: '#4f6ef7' };
    el.style.borderColor = colors[type] ?? colors.success;
    el.classList.add('show');
    if (window.__toastTimer) clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(() => el.classList.remove('show'), 3500);
};

(function syncPurchaseInvoiceTheme() {
    const sync = () => {
        document.querySelectorAll('.pi-root').forEach((el) => {
            document.documentElement.classList.contains('dark')
                ? el.classList.add('pi-dark')
                : el.classList.remove('pi-dark');
        });
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sync);
    } else {
        sync();
    }
    new MutationObserver(sync).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    document.addEventListener('livewire:navigated', sync);
})();
</script>
@endpush
</div>
