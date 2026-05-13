<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\Inventory;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceDetail;
use App\Models\Supplier;
use App\Models\Branches;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PaymentMethod;

new #[Title('فاتورة مشتريات جديدة')] class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:100')]
    public string $invoice_number = '';

    #[Validate('required|date')]
    public string $purchase_invoice_date = '';

    #[Validate('required|exists:suppliers,id')]
    public ?int $supplier_id = null;

    #[Validate('nullable|image|max:5120')]
    public $invoice_image = null;

    public ?string $imagePreview = null;
    public ?string $imageName = null;

    #[Validate('required|numeric|min:0')]
    public float $paid_amount = 0;

    public ?int $payment_method = null;

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

    // متغيرات المنتج الجديد
    #[Validate('required|string|max:191', as: 'اسم المنتج', onUpdate: false)]
    public string $newProductName = '';

    #[Validate('required|string|max:100|unique:products,product_code', as: 'كود المنتج', onUpdate: false)]
    public string $newProductCode = '';

    #[Validate('required|exists:categories,id', as: 'التصنيف', onUpdate: false)]
    public ?int $newProductCategoryId = null;

    public ?int $newProductSubCategoryId = null;

    #[Validate('required|numeric|min:0', as: 'سعر التكلفة', onUpdate: false)]
    public float $newProductCost = 0;

    #[Validate('required|numeric|min:0', as: 'سعر البيع', onUpdate: false)]
    public ?float $newProductSell = null;

    // متغيرات المقاس واللون
    #[Validate('required|exists:sizes,size_name', as: 'المقاس', onUpdate: false)]
    public string $newProductSize = '';

    #[Validate('required|regex:/^#[a-fA-F0-9]{6}$/', as: 'اللون', onUpdate: false)]
    public string $newProductColor = '';

    public bool $saving = false;

    // متغيرات لعرض متغيرات المنتج
    public ?Product $selectedProductForVariant = null;
    public ?int $pendingRowIndexForVariant = null;
    public bool $showVariantModal = false;
    public array $availableVariants = [];

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
    public function sizes(): \Illuminate\Database\Eloquent\Collection
    {
        return Size::query()
            ->orderBy('size_name')
            ->get(['id', 'size_name']);
    }

    #[Computed]
    public function colors(): \Illuminate\Database\Eloquent\Collection
    {
        return Color::query()
            ->orderBy('color_name')
            ->get(['id', 'color_name', 'color_hex_code']);
    }

    #[Computed]
    public function branches(): \Illuminate\Database\Eloquent\Collection
    {
        return Branches::query()
            ->orderBy('branch_name')
            ->get(['id', 'branch_name']);
    }

    public function getSubCategoriesProperty()
    {
        if (!$this->newProductCategoryId) {
            return collect();
        }

        return SubCategory::where('category_id', $this->newProductCategoryId)->select('id', 'sub_category_name')->orderBy('sub_category_name')->get();
    }

    public function updatedNewProductCategoryId()
    {
        $this->newProductSubCategoryId = null;
    }

    #[Computed]
    public function paymentMethods(): \Illuminate\Database\Eloquent\Collection
    {
        return PaymentMethod::where('is_active', true)->select('id', 'payment_method_name')->orderBy('payment_method_name')->get();
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
            'variant_id' => null,
            'product_name' => '',
            'product_code' => '',
            'qty' => 1,
            'cost' => 0,
            'sell' => null,
            'total' => 0,
            'branch_id' => null,
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

    public function updateRowBranch(int $index, mixed $value): void
    {
        $this->rows[$index]['branch_id'] = $value ? (int) $value : null;
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

        // بحث في المنتجات مع الـ variants - نزيل فحص التكرار من البحث
        $this->searchResults[$index] = Product::query()
            ->where(function ($q) use ($query) {
                $q->where('product_name', 'like', '%' . $query . '%')
                  ->orWhere('product_code', 'like', '%' . $query . '%');
            })
            ->with(['category:id,category_name', 'productVariants.color', 'productVariants.size'])
            ->select('id', 'product_name', 'product_code', 'product_cost', 'product_price', 'category_id')
            ->limit(8)
            ->get()
            ->map(function($product) {
                $variantsInfo = '';
                if ($product->productVariants->isNotEmpty()) {
                    $variantsInfo = ' (متوفر: ' . $product->productVariants->map(fn($v) => $v->size->size_name ?? '' . '/' . $v->color->color_name ?? '')->implode(', ') . ')';
                }
                $product->display_name = $product->product_name . $variantsInfo;
                return $product;
            })
            ->toArray();

        $this->openDropdowns[$index] = true;
    }

    public function selectProduct(int $rowIndex, int $productId): void
    {
        $product = Product::query()->with('productVariants')->find($productId);
        if (!$product) {
            return;
        }

        // إذا كان المنتج له variants، نعرض نافذة اختيار
        if ($product->productVariants->isNotEmpty()) {
            $this->showVariantSelector($rowIndex, $product);
            return;
        }

        // إزالة فحص التكرار - الآن يسمح بتكرار نفس المنتج في صفوف مختلفة
        $displayCode = $product->product_code ?: 'PRD-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);

        $this->rows[$rowIndex] = array_merge($this->rows[$rowIndex], [
            'product_id' => $product->id,
            'variant_id' => null,
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

    public function showVariantSelector(int $rowIndex, Product $product): void
    {
        $this->selectedProductForVariant = $product;
        $this->pendingRowIndexForVariant = $rowIndex;
        $this->availableVariants = $product->productVariants->load('color', 'size')->toArray();
        $this->showVariantModal = true;
        $this->openDropdowns[$rowIndex] = false;
    }

    public function selectVariant(int $variantId): void
    {
        $variant = ProductVariant::with(['product', 'color', 'size'])->find($variantId);
        if (!$variant) {
            return;
        }

        // إزالة فحص التكرار - الآن يسمح بتكرار نفس الـ variant في صفوف مختلفة
        $displayCode = $variant->product->product_code . '-' . ($variant->size->size_code ?? $variant->size->size_name) . '-' . ($variant->color->color_hex_code ?? '');

        $this->rows[$this->pendingRowIndexForVariant] = array_merge($this->rows[$this->pendingRowIndexForVariant], [
            'product_id' => $variant->product_id,
            'variant_id' => $variant->id,
            'product_name' => $variant->product->product_name . ' - ' . ($variant->size->size_name ?? '') . ' - ' . ($variant->color->color_name ?? ''),
            'product_code' => $displayCode,
            'cost' => (float) $variant->variant_cost,
            'sell' => (float) $variant->variant_price,
            'total' => $this->rows[$this->pendingRowIndexForVariant]['qty'] * (float) $variant->variant_cost,
        ]);

        $this->searchQueries[$this->pendingRowIndexForVariant] = $variant->product->product_name;
        $this->searchResults[$this->pendingRowIndexForVariant] = [];
        $this->openDropdowns[$this->pendingRowIndexForVariant] = false;
        $this->showVariantModal = false;
    }

    public function closeVariantModal(): void
    {
        $this->showVariantModal = false;
        $this->selectedProductForVariant = null;
        $this->pendingRowIndexForVariant = null;
        $this->availableVariants = [];
    }

    public function closeDropdown(int $index): void
    {
        $this->openDropdowns[$index] = false;
    }

    public function updatedInvoiceImage(): void
    {
        $this->validateOnly('invoice_image');
        if ($this->invoice_image) {
            $this->imagePreview = $this->invoice_image->temporaryUrl();
            $this->imageName = $this->invoice_image->getClientOriginalName();
        }
    }

    public function removeImage(): void
    {
        $this->invoice_image = null;
        $this->imagePreview = null;
        $this->imageName = null;
    }

    public function openAddProductModal(int $rowIndex, string $prefill = ''): void
    {
        $this->pendingRowIndex = $rowIndex;
        $this->newProductName = $prefill;
        $this->newProductCode = '';
        $this->newProductCategoryId = null;
        $this->newProductSubCategoryId = null;
        $this->newProductCost = 0;
        $this->newProductSell = null;
        $this->newProductSize = '';
        $this->newProductColor = '';
        $this->resetValidation(['newProductName', 'newProductCode', 'newProductCategoryId', 'newProductSubCategoryId', 'newProductCost', 'newProductSell', 'newProductSize', 'newProductColor']);
        $this->showModal = true;
        $this->openDropdowns[$rowIndex] = false;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->pendingRowIndex = null;
    }

    private function getColorNameFromHex(string $hex): string
    {
        $colors = [
            '#FF0000' => 'أحمر',
            '#00FF00' => 'أخضر',
            '#0000FF' => 'أزرق',
            '#FFFF00' => 'أصفر',
            '#FF00FF' => 'أرجواني',
            '#00FFFF' => 'سماوي',
            '#000000' => 'أسود',
            '#FFFFFF' => 'أبيض',
            '#808080' => 'رمادي',
            '#800000' => 'كستنائي',
            '#008000' => 'أخضر غامق',
            '#000080' => 'أزرق غامق',
            '#FFA500' => 'برتقالي',
            '#A52A2A' => 'بني',
            '#FFC0CB' => 'وردي',
        ];

        return $colors[$hex] ?? 'لون مخصص';
    }

    public function saveNewProduct(): void
    {
        $this->validate(
            [
                'newProductName' => 'required|string|max:191',
                'newProductCode' => 'required|string|max:100|unique:products,product_code',
                'newProductCategoryId' => 'required|exists:categories,id',
                'newProductSubCategoryId' => 'required|exists:sub_categories,id',
                'newProductCost' => 'required|numeric|min:0',
                'newProductSell' => 'required|numeric|min:0',
                'newProductSize' => 'required|exists:sizes,size_name',
                'newProductColor' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            ],
            [
                'newProductName.required' => 'اسم المنتج مطلوب',
                'newProductCode.required' => 'كود المنتج مطلوب',
                'newProductCode.unique' => 'كود المنتج مستخدم مسبقاً',
                'newProductCategoryId.required' => 'التصنيف مطلوب',
                'newProductCategoryId.exists' => 'التصنيف غير صحيح',
                'newProductSubCategoryId.required' => 'التصنيف الفرعي مطلوب',
                'newProductSubCategoryId.exists' => 'التصنيف الفرعي غير صحيح',
                'newProductCost.required' => 'سعر التكلفة مطلوب',
                'newProductCost.numeric' => 'سعر التكلفة يجب أن يكون رقماً',
                'newProductCost.min' => 'سعر التكلفة يجب أن يكون أكبر من أو يساوي 0',
                'newProductSell.required' => 'سعر البيع مطلوب',
                'newProductSell.numeric' => 'سعر البيع يجب أن يكون رقماً',
                'newProductSell.min' => 'سعر البيع يجب أن يكون أكبر من أو يساوي 0',
                'newProductSize.required' => 'المقاس مطلوب',
                'newProductSize.exists' => 'المقاس غير صحيح',
                'newProductColor.required' => 'اللون مطلوب',
                'newProductColor.regex' => 'يجب إدخال لون صحيح بصيغة Hex مثل #FF0000',
            ],
        );

        DB::transaction(function () {
            // البحث عن أو إنشاء اللون
            $color = Color::firstOrCreate(
                ['color_hex_code' => $this->newProductColor],
                ['color_name' => $this->getColorNameFromHex($this->newProductColor)]
            );

            // البحث عن أو إنشاء المقاس
            $size = Size::firstOrCreate(
                ['size_name' => $this->newProductSize],
                ['size_code' => strtoupper(substr($this->newProductSize, 0, 3))]
            );

            // إنشاء المنتج
            $product = Product::create([
                'product_name' => $this->newProductName,
                'product_code' => mb_strtoupper($this->newProductCode),
                'product_quantity' => 0,
                'product_cost' => $this->newProductCost,
                'product_price' => $this->newProductSell,
                'category_id' => $this->newProductCategoryId,
                'sub_category_id' => $this->newProductSubCategoryId,
            ]);

            // إنشاء الـ Product Variant
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => $color->id,
                'size_id' => $size->id,
                'sku' => $product->product_code . '-' . $size->size_code . '-' . $color->color_hex_code,
                'variant_cost' => $this->newProductCost,
                'variant_price' => $this->newProductSell,
                'is_active' => true,
            ]);

            $displayCode = $product->product_code . '-' . $size->size_code . '-' . $color->color_hex_code;

            if ($this->pendingRowIndex !== null && isset($this->rows[$this->pendingRowIndex])) {
                $this->rows[$this->pendingRowIndex] = array_merge($this->rows[$this->pendingRowIndex], [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'product_name' => $product->product_name . ' - ' . $size->size_name . ' - ' . $color->color_name,
                    'product_code' => $displayCode,
                    'cost' => (float) $variant->variant_cost,
                    'sell' => (float) $variant->variant_price,
                    'total' => $this->rows[$this->pendingRowIndex]['qty'] * (float) $variant->variant_cost,
                ]);
                $this->searchQueries[$this->pendingRowIndex] = $product->product_name;
            }
        });

        $this->closeModal();
        $this->dispatch('toast', message: 'تم حفظ المنتج مع المقاس واللون وإضافته للفاتورة', type: 'success');
    }

    public function saveInvoice(): void
    {
        $this->saving = true;

        $this->validate(
            [
                'invoice_number' => ['required', 'string', 'max:100', Rule::unique('purchase_invoices', 'invoice_number')->whereNull('deleted_at')],
                'purchase_invoice_date' => 'required|date',
                'supplier_id' => 'required|exists:suppliers,id',
                'invoice_image' => 'nullable|image|max:5120',
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
            if (empty($row['branch_id'])) {
                $this->addError("rows.{$i}.branch_id", 'يجب اختيار الفرع للصف ' . ($i + 1));
                $this->saving = false;
                return;
            }
        }

        try {
            DB::transaction(function () {
                $imagePath = null;
                if ($this->invoice_image) {
                    $imagePath = $this->invoice_image->store('purchase-invoices', 'public');
                }

                $total = $this->invoiceTotal;
                $remaining = max(0, $total - $this->paid_amount);

                $invoice = PurchaseInvoice::create([
                    'invoice_number' => $this->invoice_number,
                    'purchase_invoice_date' => $this->purchase_invoice_date,
                    'total_amount' => $total,
                    'paid_amount' => $this->paid_amount,
                    'remaining_amount' => $remaining,
                    'payment_method_id' => $this->payment_method,
                    'invoice_image' => $imagePath,
                    'supplier_id' => $this->supplier_id,
                    'branch_id' => auth()->user()?->branch_id ?? 1,
                    'user_id' => auth()->id(),
                ]);

                foreach ($this->rows as $row) {
                    // إنشاء تفاصيل الفاتورة
                    PurchaseInvoiceDetail::create([
                        'purchase_invoice_id' => $invoice->id,
                        'product_variant_id' => $row['variant_id'],
                        'product_quantity' => $row['qty'],
                        'unit_cost' => $row['cost'],
                        'branch_id' => $row['branch_id'],
                    ]);

                    // تحديث المخزون حسب الـ variant والفرع
                    Inventory::updateOrCreate(
                        [
                            'product_variant_id' => $row['variant_id'],
                            'branch_id' => $row['branch_id'],
                        ],
                        [
                            'quantity' => DB::raw('COALESCE(quantity, 0) + ' . $row['qty']),
                        ]
                    );

                    // تحديث سعر التكلفة وسعر البيع في الـ variant
                    $variant = ProductVariant::find($row['variant_id']);
                    if ($variant) {
                        // تحديث الأسعار إذا تغيرت
                        if ($variant->variant_cost != $row['cost']) {
                            $variant->update(['variant_cost' => $row['cost']]);
                        }

                        if (!empty($row['sell']) && $variant->variant_price != $row['sell']) {
                            $variant->update(['variant_price' => $row['sell']]);
                        }
                    }
                }

                // تحديث الكمية الإجمالية لكل منتج في جدول products
                $productIds = collect($this->rows)->pluck('product_id')->unique();

                foreach ($productIds as $productId) {
                    // حساب الكمية الإجمالية للمنتج من جميع الفروع والمتغيرات
                    $totalQuantity = DB::table('inventories')
                        ->join('product_variants', 'inventories.product_variant_id', '=', 'product_variants.id')
                        ->where('product_variants.product_id', $productId)
                        ->sum('inventories.quantity');

                    // تحديث الكمية في جدول المنتجات
                    Product::where('id', $productId)->update(['product_quantity' => $totalQuantity]);
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

{{-- Modal for Variant Selection --}}
@if ($showVariantModal)
<div class="pi-modal-bg" wire:click.self="closeVariantModal" @keydown.escape.window="closeVariantModal">
    <div class="pi-modal" style="max-width: 550px; overflow: hidden;" wire:click.stop>
        <div class="pi-modal-hd" style="border-bottom: 1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="pi-modal-icon" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--tx)">اختر المقاس واللون</div>
                    <div style="font-size:12px;color:var(--tx3);margin-top:2px">
                        {{ $selectedProductForVariant?->product_name }}
                    </div>
                </div>
            </div>
            <button type="button" wire:click="closeVariantModal" class="pi-modal-close">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="pi-modal-body" style="padding: 20px; max-height: 450px; overflow-y: auto;">
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($availableVariants as $variant)
                <button type="button"
                    wire:click="selectVariant({{ $variant['id'] }})"
                    class="variant-card"
                    style="
                        width: 100%;
                        background: var(--bg2);
                        border: 1.5px solid var(--border);
                        border-radius: 12px;
                        padding: 14px 16px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        text-align: right;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 12px;
                    "
                    onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateX(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                    onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateX(0)'; this.style.boxShadow='none'">

                    {{-- Right side: Color and Size info --}}
                    <div style="display: flex; align-items: center; gap: 14px; flex: 1;">
                        {{-- Color preview circle --}}
                        <div style="position: relative;">
                            <div style="
                                width: 48px;
                                height: 48px;
                                border-radius: 12px;
                                background: {{ $variant['color']['color_hex_code'] ?? '#000000' }};
                                border: 2px solid var(--border);
                                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                                transition: transform 0.2s ease;
                            "></div>
                            @if(isset($variant['color']['color_hex_code']))
                            <div style="
                                position: absolute;
                                bottom: -4px;
                                right: -4px;
                                width: 16px;
                                height: 16px;
                                border-radius: 50%;
                                background: var(--bg2);
                                border: 1px solid var(--border);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                            </div>
                            @endif
                        </div>

                        {{-- Size and Color names --}}
                        <div style="flex: 1;">
                            <div style="
                                font-size: 16px;
                                font-weight: 700;
                                color: var(--tx);
                                margin-bottom: 4px;
                            ">
                                {{ $variant['size']['size_name'] ?? 'بدون مقاس' }}
                            </div>
                            <div style="
                                display: inline-block;
                                background: var(--bg3);
                                padding: 4px 10px;
                                border-radius: 20px;
                                font-size: 12px;
                                color: var(--tx2);
                            ">
                                🎨 {{ $variant['color']['color_name'] ?? 'لون مخصص' }}
                            </div>
                        </div>
                    </div>

                    {{-- Left side: Price information --}}
                    <div style="text-align: left; direction: ltr;">
                        <div style="
                            font-size: 18px;
                            font-weight: 800;
                            color: var(--primary);
                            margin-bottom: 4px;
                        ">
                            {{ number_format($variant['variant_cost'], 2) }} <span style="font-size: 12px;">ج.م</span>
                        </div>
                        <div style="
                            font-size: 11px;
                            color: var(--tx3);
                            display: flex;
                            align-items: center;
                            gap: 4px;
                            justify-content: flex-end;
                        ">
                            <span>سعر البيع:</span>
                            <span style="color: var(--success); font-weight: 600;">{{ number_format($variant['variant_price'], 2) }} ج.م</span>
                        </div>
                    </div>

                    {{-- Arrow icon --}}
                    <div style="color: var(--tx3); transition: transform 0.2s ease;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                </button>
                @endforeach
            </div>

            @if(empty($availableVariants))
            <div style="
                text-align: center;
                padding: 40px 20px;
                background: var(--bg2);
                border-radius: 12px;
            ">
                <svg width="48" height="48" fill="none" stroke="var(--tx3)" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                <div style="margin-top: 12px; color: var(--tx3);">
                    لا توجد متغيرات متاحة لهذا المنتج
                </div>
            </div>
            @endif
        </div>

        <div class="pi-modal-ft" style="border-top: 1px solid var(--border); background: var(--bg);">
            <button type="button"
                wire:click="closeVariantModal"
                class="pi-btn-sec"
                style="padding: 8px 20px; font-size: 13px; font-weight: 500;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left: 6px;">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                إلغاء
            </button>
        </div>
    </div>
</div>

<style>
    .variant-card:hover svg:last-child {
        transform: translateX(4px);
        color: var(--primary);
    }
</style>
@endif

</div>
