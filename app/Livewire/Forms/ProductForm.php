<?php

namespace App\Livewire\Forms;

use App\Models\Color;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product = null;

    public string $product_name = '';

    public ?int $product_quantity = null;

    public ?float $product_cost = null;

    public ?float $product_price = null;

    public ?int $category_id = null;

    public ?int $branch_id = null;

    public function rules(): array
    {
        return [
            'product_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],

            'product_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'product_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'product_price' => [
                'required',
                'numeric',
                'min:0',
                'gte:product_cost', // السعر لازم يكون >= التكلفة
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // product_name
            'product_name.required' => 'اسم المنتج مطلوب.',
            'product_name.string' => 'اسم المنتج يجب أن يكون نص.',
            'product_name.max' => 'اسم المنتج لا يجب أن يزيد عن 255 حرف.',
            'product_name.regex' => 'اسم المنتج يحتوي على رموز غير مسموح بها.',

            // product_quantity
            'product_quantity.integer' => 'الكمية يجب أن تكون رقم صحيح.',
            'product_quantity.min' => 'الكمية لا يمكن أن تكون أقل من 0.',

            // product_cost
            'product_cost.required' => 'تكلفة المنتج مطلوبة.',
            'product_cost.numeric' => 'التكلفة يجب أن تكون رقم.',
            'product_cost.min' => 'التكلفة لا يمكن أن تكون أقل من 0.',

            // product_price
            'product_price.required' => 'سعر البيع مطلوب.',
            'product_price.numeric' => 'سعر البيع يجب أن يكون رقم.',
            'product_price.min' => 'سعر البيع لا يمكن أن يكون أقل من 0.',
            'product_price.gte' => 'سعر البيع يجب أن يكون أكبر من أو يساوي التكلفة.',

            // category_id
            'category_id.required' => 'يجب اختيار التصنيف.',
            'category_id.exists' => 'التصنيف غير موجود.',

            // branch_id
            'branch_id.required' => 'يجب اختيار الفرع.',
            'branch_id.exists' => 'الفرع غير موجود.',
        ];
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->product_name = $product->product_name;
        $this->product_quantity = $product->product_quantity;
        $this->product_cost = $product->product_cost;
        $this->product_price = $product->product_price;
        $this->category_id = $product->category_id;
        $this->branch_id = $product->branch_id;
    }

    public function store()
    {
        $data = $this->validate();
        Product::create($data);
        $this->reset();
    }

    /**
     * Store a product and its create-page related records in one transaction.
     *
     * @param  array<int, array<string, mixed>>  $variants
     * @param  array<int, TemporaryUploadedFile|UploadedFile>  $media
     * @param  array<int|string, array<int, TemporaryUploadedFile|UploadedFile>>  $colorMedia
     */
    public function storeWithRelations(
        array $productData,
        array $variants = [],
        array $media = [],
        array $colorMedia = [],
        ?string $primaryUpload = null,
    ): Product {
        return DB::transaction(function () use ($productData, $variants, $media, $colorMedia, $primaryUpload) {
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'sub_category_id' => $productData['sub_category_id'] ?: null,
                'collection_id' => $productData['collection_id'] ?: null,
                'product_name' => $productData['product_name'],
                'product_code' => $productData['product_code'] ?: null,
                'product_quantity' => $productData['product_quantity'],
                'product_cost' => $productData['product_cost'],
                'product_price' => $productData['product_price'],
            ]);

            $extra = [];

            if (Schema::hasColumn('products', 'product_desc')) {
                $extra['product_desc'] = $productData['product_desc'] ?: null;
            }

            if (Schema::hasColumn('products', 'is_active')) {
                $extra['is_active'] = $productData['is_active'];
            }

            if ($extra) {
                $product->forceFill($extra)->save();
            }

            foreach ($variants as $variantData) {
                $variantBranchId = $variantData['branch_id'] ?? $productData['branch_id'];

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'] ?? null,
                    'size_id' => $variantData['size_id'] ?? null,
                    'sku' => $this->uniqueVariantSku($variantData['sku']),
                    'variant_cost' => $variantData['variant_cost'],
                    'variant_price' => $variantData['variant_price'],
                    'is_active' => $variantData['is_active'] ?? true,
                ]);

                if ($variantBranchId) {
                    Inventory::create([
                        'product_variant_id' => $variant->id,
                        'branch_id' => $variantBranchId,
                        'quantity' => $variantData['quantity'],
                    ]);
                }
            }

            $sortOrder = 0;

            foreach ($media as $index => $file) {
                $this->createProductImage(
                    product: $product,
                    file: $file,
                    sortOrder: $sortOrder,
                    isPrimary: $primaryUpload === "media:$index" || ($primaryUpload === null && $sortOrder === 0),
                );
                $sortOrder++;
            }

            foreach ($colorMedia as $colorId => $files) {
                foreach ($files ?? [] as $fileIndex => $file) {
                    $this->createProductImage(
                        product: $product,
                        file: $file,
                        sortOrder: $sortOrder,
                        isPrimary: $primaryUpload === "color_media:$colorId:$fileIndex" || ($primaryUpload === null && $sortOrder === 0),
                        colorId: (int) $colorId,
                    );
                    $sortOrder++;
                }
            }

            return $product;
        });
    }

    private function createProductImage(
        Product $product,
        TemporaryUploadedFile|UploadedFile $file,
        int $sortOrder,
        bool $isPrimary,
        ?int $colorId = null,
        ?string $slug = null,
    ): void {
        // 1. الحصول على أسماء التصنيفات (مع قيمة افتراضية)
        $categoryName = optional($product->category)->category_name ?? 'uncategorized';
        $subCategoryName = optional($product->subCategory)->sub_category_name ?? 'general';
        $productName = $product->product_name ?? 'general';  // ← التصحيح هنا

        // 2. تحويل الأسماء إلى slugs صالحة للمجلدات
        $categorySlug = Str::slug($categoryName);
        $subCategorySlug = Str::slug($subCategoryName);
        $productSlug = Str::slug($productName);

        // 3. slug المنتج (إما المستلم أو id المنتج)
        // $productSlug = $slug ? Str::slug($slug) : (string) $product->id;

        // 4. اسم مجلد اللون (إذا كان colorId موجوداً)
        $colorSlug = 'general'; // default if no color
        if ($colorId) {
            $color = Color::find($colorId);
            $colorSlug = $color ? Str::slug($color->color_name) : 'unknown-color';
        }

        // 5. بناء المسار الكامل (بدون اسم الملف)
        $directory = "products/{$categorySlug}/{$subCategorySlug}/{$productSlug}/{$colorSlug}";

        // 6. إنشاء اسم ملف فريد وآمن
        $originalName = $file->getClientOriginalName();
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $filename = time().'_'.uniqid().'_'.$safeName.'.'.$extension;

        // 7. حفظ الملف باستخدام storeAs (تحديد المسار الكامل)
        $path = $file->storeAs($directory, $filename, 'public');

        // 8. إنشاء السجل في قاعدة البيانات
        $imageData = [
            'product_id' => $product->id,
            'image_path' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
        ];

        // إذا كان الجدول يحتوي على عمود color_id وكان هناك لون محدد
        if (Schema::hasColumn('product_images', 'color_id') && $colorId !== null) {
            $imageData['color_id'] = $colorId;
        }

        ProductImage::create($imageData);
    }

    private function uniqueVariantSku(string $sku): string
    {
        $base = strtoupper($sku);
        $sku = $base;
        $counter = 1;

        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $base.'-'.now()->format('His').'-'.$counter;
            $counter++;
        }

        return $sku;
    }

    public function update()
    {
        $this->validate();
        $this->product->update([
            'product_name' => $this->product_name,
            'product_quantity' => $this->product_quantity,
            'product_cost' => $this->product_cost,
            'product_price' => $this->product_price,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
        ]);

    }
}
