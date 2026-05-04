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
        return Supplier::query()
            ->orderBy('supplier_name')
            ->get(['id', 'supplier_name']);
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()
            ->orderBy('category_name')
            ->get(['id', 'category_name']);
    }

    #[Computed]
    public function invoiceTotal(): float
    {
        return collect($this->rows)->sum(fn($r) => ($r['qty'] ?? 0) * ($r['cost'] ?? 0));
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
                $q->where('product_name', 'like', '%' . $query . '%')->orWhere('product_code', 'like', '%' . $query . '%');
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
        if (!$product) {
            return;
        }

        $alreadyUsed = collect($this->rows)->filter(fn($r, $i) => (int) $i !== $rowIndex && $r['product_id'] === $productId)->isNotEmpty();

        if ($alreadyUsed) {
            $this->dispatch('toast', message: 'هذا المنتج مضاف بالفعل في الفاتورة', type: 'warning');

            return;
        }

        $displayCode = $product->product_code ?: 'PRD-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);

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
        $this->validate(
            [
                'newProductName' => 'required|string|max:191',
                'newProductCode' => 'required|string|max:100|unique:products,product_code',
                'newProductCategoryId' => 'required|exists:categories,id',
                'newProductCost' => 'required|numeric|min:0',
                'newProductSell' => 'nullable|numeric|min:0',
            ],
            [
                'newProductName.required' => 'اسم المنتج مطلوب',
                'newProductCode.required' => 'كود المنتج مطلوب',
                'newProductCode.unique' => 'كود المنتج مستخدم مسبقاً',
                'newProductCategoryId.required' => 'التصنيف مطلوب',
                'newProductCategoryId.exists' => 'التصنيف غير صحيح',
                'newProductCost.required' => 'سعر التكلفة مطلوب',
                'newProductCost.numeric' => 'سعر التكلفة يجب أن يكون رقماً',
                'newProductCost.min' => 'سعر التكلفة يجب أن يكون أكبر من أو يساوي 0',
            ],
        );

        $product = Product::create([
            'product_name' => $this->newProductName,
            'product_code' => mb_strtoupper($this->newProductCode),
            'product_quantity' => 0,
            'product_cost' => $this->newProductCost,
            'product_price' => $this->newProductSell ?? 0,
            'category_id' => $this->newProductCategoryId,
            'branch_id' => auth()->user()?->branch_id ?? 1,
        ]);

        $displayCode = $product->product_code ?: 'PRD-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);

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
        $this->dispatch('toast', message: 'تم حفظ المنتج "' . $product->product_name . '" وإضافته للفاتورة', type: 'success');
    }

    public function saveInvoice(): void
    {
        $this->saving = true;

        $this->validate(
            [
                'invoice_number' => ['required', 'string', 'max:100', Rule::unique('purchase_invoices', 'invoice_number')->whereNull('deleted_at')],
                'purchase_invoice_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'product_image' => 'nullable|image|max:5120',
                'paid_amount' => 'required|numeric|min:0',
            ],
            [
                'invoice_number.required' => 'رقم الفاتورة مطلوب',
                'invoice_number.unique' => 'رقم الفاتورة مستخدم بالفعل',
                'purchase_invoice_date.required' => 'تاريخ الفاتورة مطلوب',
                'supplier_id.required' => 'المورد مطلوب',
                'supplier_id.exists' => 'المورد غير موجود',
                'paid_amount.required' => 'المبلغ المدفوع مطلوب',
                'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقماً',
            ],
        );

        if (empty($this->rows)) {
            $this->addError('rows', 'يجب إضافة صنف واحد على الأقل');
            $this->saving = false;

            return;
        }

        foreach ($this->rows as $i => $row) {
            if (empty($row['product_id'])) {
                $this->addError("rows.{$i}.product_id", 'يجب اختيار منتج للصف ' . ($i + 1));
                $this->saving = false;

                return;
            }
            if (($row['qty'] ?? 0) <= 0) {
                $this->addError("rows.{$i}.qty", 'الكمية يجب أن تكون أكبر من صفر للصف ' . ($i + 1));
                $this->saving = false;

                return;
            }
            if (($row['cost'] ?? 0) <= 0) {
                $this->addError("rows.{$i}.cost", 'سعر التكلفة يجب أن يكون أكبر من صفر للصف ' . ($i + 1));
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
                        if (!empty($row['sell'])) {
                            $product->update(['product_price' => $row['sell']]);
                        }
                    }
                }
            });

            $this->dispatch('toast', message: 'تم حفظ الفاتورة بنجاح!', type: 'success');
            $this->redirect(route('createpurchaseInvoices'), navigate: true);
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'حدث خطأ أثناء الحفظ: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->saving = false;
        }
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'INV-' . $year . '-';

        $latest = PurchaseInvoice::withTrashed()
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $nextSeq = 1;
        if ($latest && preg_match('/^INV-\d{4}-(\d{5})$/', $latest, $m)) {
            $nextSeq = (int) $m[1] + 1;
        }

        return $prefix . str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);
    }
};
?>
<div>

{{-- ══════════════════════════════════════════════════════════
TOAST NOTIFICATION
 ══════════════════════════════════════════════════════════ --}}
<div id="pi-toast" class="pi-toast" aria-live="polite"></div>


    @include('livewire.purchase-invoices.partials._header')

    <div class="pi-body">
        @include('livewire.purchase-invoices.partials._basic-info')
        @include('livewire.purchase-invoices.partials._products-table')
        @include('livewire.purchase-invoices.partials._summary')
    </div>

    @include('livewire.purchase-invoices.partials._add-product-modal')



</div>
