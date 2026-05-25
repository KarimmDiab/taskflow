<?php

use App\Livewire\Forms\ProductForm;
use App\Models\Branches;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\SubCategory;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('إنشاء منتج')] class extends Component {
    use WithFileUploads;

    public $category_id = '';
    public $sub_category_id = '';
    public $collection_id = '';
    public $branch_id = '';
    public string $product_name = '';
    public string $product_code = '';
    public $product_quantity = 0;
    public $product_cost = '';
    public $product_price = '';
    public string $product_desc = '';
    public bool $is_active = true;
    public array $selected_colors = [];
    public array $selected_sizes = [];
    public array $variants = [];
    public array $media = [];
    public array $color_media = [];
    public ?string $primary_upload = null;
    public ProductForm $form;

    public function getCategoriesProperty()
    {
        return Category::select('id', 'category_name')->orderBy('category_name')->get();
    }

    public function getSubCategoriesProperty()
    {
        return SubCategory::select('id', 'sub_category_name', 'category_id')
            ->when($this->category_id, fn ($query) => $query->where('category_id', $this->category_id))
            ->orderBy('sub_category_name')
            ->get();
    }

    public function getCollectionsProperty()
    {
        return Collection::select('id', 'collection_name')->orderBy('collection_name')->get();
    }

    public function getBranchesProperty()
    {
        return Branches::select('id', 'branch_name')->orderBy('branch_name')->get();
    }

    public function getColorsProperty()
    {
        return Color::select('id', 'color_name', 'color_hex_code')->where('is_active', true)->orderBy('color_name')->get();
    }

    public function getSizesProperty()
    {
        return Size::select('id', 'size_name')->where('is_active', true)->orderBy('sort_order')->orderBy('size_name')->get();
    }

    public function updatedCategoryId(): void
    {
        $this->sub_category_id = '';
    }

    public function generateVariants(): void
    {
        $colors = $this->selected_colors ?: [null];
        $sizes = $this->selected_sizes ?: [null];
        $colorNames = Color::whereIn('id', array_filter($this->selected_colors))->pluck('color_name', 'id');
        $sizeNames = Size::whereIn('id', array_filter($this->selected_sizes))->pluck('size_name', 'id');
        $rows = [];
        $counter = 1;

        foreach ($colors as $colorId) {
            foreach ($sizes as $sizeId) {
                $rows[] = [
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'label' => trim(($colorId ? $colorNames[$colorId] : 'افتراضي') . ($sizeId ? ' / ' . $sizeNames[$sizeId] : '')),
                    'sku' => $this->makeSku($colorId, $sizeId, $counter),
                    'variant_cost' => $this->product_cost ?: 0,
                    'variant_price' => $this->product_price ?: 0,
                    'quantity' => $this->product_quantity ?: 0,
                    'branch_id' => $this->branch_id ?: '',
                    'is_active' => true,
                ];
                $counter++;
            }
        }

        $this->variants = $rows;
    }

    public function removeVariant(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function removeMedia(int $index): void
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
        $this->normalizePrimaryUploadAfterRemoval('media', $index);
    }

    public function removeColorMedia(int $colorId, int $index): void
    {
        unset($this->color_media[$colorId][$index]);
        $this->color_media[$colorId] = array_values($this->color_media[$colorId]);

        if (empty($this->color_media[$colorId])) {
            unset($this->color_media[$colorId]);
        }

        $this->normalizePrimaryUploadAfterRemoval('color_media', $index, $colorId);
    }

    public function setPrimaryUpload(string $uploadKey): void
    {
        $this->primary_upload = $uploadKey;
    }

    private function normalizePrimaryUploadAfterRemoval(string $group, int $removedIndex, ?int $colorId = null): void
    {
        if (! $this->primary_upload) {
            return;
        }

        if ($group === 'media' && str_starts_with($this->primary_upload, 'media:')) {
            [$prefix, $selectedIndex] = explode(':', $this->primary_upload, 2);
            $selectedIndex = (int) $selectedIndex;

            if ($selectedIndex === $removedIndex) {
                $this->primary_upload = null;
                return;
            }

            if ($removedIndex < $selectedIndex) {
                $this->primary_upload = 'media:' . ($selectedIndex - 1);
            }

            return;
        }

        if ($group === 'color_media' && $colorId !== null && str_starts_with($this->primary_upload, "color_media:$colorId:")) {
            [$prefix, $selectedColorId, $selectedIndex] = explode(':', $this->primary_upload, 3);
            $selectedIndex = (int) $selectedIndex;

            if ($selectedIndex === $removedIndex) {
                $this->primary_upload = null;
                return;
            }

            if ($removedIndex < $selectedIndex) {
                $this->primary_upload = "color_media:$colorId:" . ($selectedIndex - 1);
            }
        }
    }

    public function save()
    {
        $validated = $this->validate(
            [
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'sub_category_id' => ['nullable', 'integer', 'exists:sub_categories,id'],
                'collection_id' => ['nullable', 'integer', 'exists:collections,id'],
                'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
                'product_name' => ['required', 'string', 'max:255', 'regex:/^[\p{Arabic}A-Za-z0-9\s\-_()]+$/u'],
                'product_code' => ['nullable', 'string', 'max:100', 'unique:products,product_code'],
                'product_quantity' => ['required', 'integer', 'min:0'],
                'product_cost' => ['required', 'numeric', 'min:0'],
                'product_price' => ['required', 'numeric', 'min:0', 'gte:product_cost'],
                'product_desc' => ['nullable', 'string', 'max:2000'],
                'is_active' => ['boolean'],
                'selected_colors' => ['array'],
                'selected_colors.*' => ['integer', 'exists:colors,id'],
                'selected_sizes' => ['array'],
                'selected_sizes.*' => ['integer', 'exists:sizes,id'],
                'variants' => ['array'],
                'variants.*.color_id' => ['nullable', 'integer', 'exists:colors,id'],
                'variants.*.size_id' => ['nullable', 'integer', 'exists:sizes,id'],
                'variants.*.label' => ['nullable', 'string', 'max:255'],
                'variants.*.sku' => ['required', 'string', 'max:255'],
                'variants.*.variant_cost' => ['required', 'numeric', 'min:0'],
                'variants.*.variant_price' => ['required', 'numeric', 'min:0'],
                'variants.*.quantity' => ['required', 'integer', 'min:0'],
                'variants.*.branch_id' => ['nullable', 'integer', 'exists:branches,id'],
                'variants.*.is_active' => ['boolean'],
                'media' => ['array'],
                'media.*' => ['image', 'mimes:png,jpg,jpeg,webp,avif', 'max:4096'],
                'color_media' => ['array'],
                'color_media.*' => ['array'],
                'color_media.*.*' => ['image', 'mimes:png,jpg,jpeg,webp,avif', 'max:4096'],
            ],
            [
                'media.*.image' => 'يجب أن تكون الصورة من نوع صورة صحيح.',
                'media.*.mimes' => 'الصيغ المقبولة: PNG, JPG, JPEG, WEBP, AVIF فقط.',
                'media.*.max' => 'حجم الملف يجب أن لا يتجاوز 4 ميجابايت.',
                'color_media.*.*.image' => 'يجب أن تكون الصورة من نوع صورة صحيح.',
                'color_media.*.*.mimes' => 'الصيغ المقبولة: PNG, JPG, JPEG, WEBP, AVIF فقط.',
                'color_media.*.*.max' => 'حجم الملف يجب أن لا يتجاوز 4 ميجابايت.',
            ]
        );

        // معالجة branch_id للخيارات - إذا لم يتم تحديد فرع يتم استخدام الفرع الافتراضي
        if (!empty($this->variants)) {
            foreach ($validated['variants'] as &$variant) {
                if (empty($variant['branch_id']) && !empty($this->branch_id)) {
                    $variant['branch_id'] = $this->branch_id;
                }
            }
        }

        if (empty($this->variants)) {
            $this->generateVariants();
            $validated['variants'] = $this->variants;
        }

        $this->form->storeWithRelations(
            productData: $validated,
            variants: $validated['variants'],
            media: $this->media,
            colorMedia: $this->color_media,
            primaryUpload: $this->primary_upload,
        );

        session()->flash('success', 'تم إنشاء المنتج بنجاح.');

        return redirect()->route('products');
    }

    private function makeSku($colorId, $sizeId, int $counter): string
    {
        $base = $this->product_code ?: str($this->product_name ?: 'PRODUCT')->slug('-')->upper()->limit(24, '');
        $suffix = collect([$colorId ? 'C' . $colorId : null, $sizeId ? 'S' . $sizeId : null, $counter])->filter()->implode('-');

        return trim($base . '-' . $suffix, '-');
    }

};
?>

<div
    x-data="{ dropActive: false, sidebarOpen: true }"
    class="min-h-screen bg-[#f6f6f3] text-slate-900"
>
    <x-flash-message />

    <div class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur" style="border-radius:10px; ">
        <div class="mx-auto flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('products') }}" wire:navigate class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">المنتجات</p>
                    <h1 class="truncate text-lg font-semibold text-slate-950">إنشاء منتج جديد</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50" @click="sidebarOpen = ! sidebarOpen">
                    ملخص
                </button>
                <button
                    type="submit"
                    form="create-product-form"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="rounded-lg bg-[#008060] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#006e52] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="save">حفظ المنتج</span>
                    <span wire:loading wire:target="save">جاري الحفظ...</span>
                </button>
            </div>
        </div>
    </div>

    <form id="create-product-form" wire:submit.prevent="save" class="mx-auto grid  grid-cols-1 gap-5 px-4 py-6 sm:px-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-8">
        <main class="space-y-5">
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">معلومات المنتج</h2>
                    <p class="mt-1 text-sm text-slate-500">تفاصيل المنتج الأساسية المستخدمة في المبيعات والمخزون وعرض الكتالوج.</p>
                </div>

                <div class="grid gap-4 p-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">اسم المنتج</label>
                        <input type="text" wire:model.live="product_name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15" placeholder="قميص أكسفورد كلاسيكي">
                        @error('product_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">رمز المنتج</label>
                            <input type="text" wire:model.live="product_code" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm uppercase outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15" placeholder="OXF-001">
                            @error('product_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">الكمية</label>
                            <input type="number" min="0" wire:model.live="product_quantity" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            @error('product_quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">الحالة</label>
                            <select wire:model="is_active" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                                <option value="1">نشط</option>
                                <option value="0">مسودة</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">التكلفة</label>
                            <input type="number" min="0" step="0.01" wire:model.live="product_cost" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            @error('product_cost') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">سعر البيع</label>
                            <input type="number" min="0" step="0.01" wire:model.live="product_price" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            @error('product_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">الوصف</label>
                        <textarea rows="4" wire:model="product_desc" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15" placeholder="الخامة، المقاس، تعليمات العناية، ملاحظات داخلية."></textarea>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">رفع الوسائط</h2>
                    <p class="mt-1 text-sm text-slate-500">قم بتحميل صور المنتج. الصورة الأولى تكون الأساسية بشكل افتراضي، أو يمكنك اختيار غيرها.</p>
                </div>

                <div class="p-5">
                    <div class="mb-5">
                        <h3 class="text-sm font-medium text-slate-800">وسائط المنتج العامة</h3>
                        <p class="mt-1 text-xs text-slate-500">استخدم هذا للصور غير المرتبطة بلون معين.</p>
                    </div>

                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-6 py-10 text-center transition"
                        :class="dropActive ? 'border-[#008060] bg-emerald-50' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'"
                        @dragover.prevent="dropActive = true"
                        @dragleave.prevent="dropActive = false"
                        @drop="dropActive = false"
                    >
                        <input type="file" multiple accept="image/*" wire:model="media" class="hidden">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                            </svg>
                        </span>
                        <span class="mt-3 text-sm font-medium text-slate-800">اسحب الصور هنا أو انقر للرفع</span>
                        <span class="mt-1 text-xs text-slate-500">PNG, JPG, WEBP, أو AVIF بحد أقصى 4 ميجابايت لكل ملف</span>
                    </label>
                    @if ($errors->has('media.*') || $errors->has('media'))
                        <div class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
                            @foreach ($errors->get('media.*') as $messages)
                                @foreach ($messages as $message)
                                    <p class="text-xs text-red-700 flex items-start gap-2 mb-1">
                                        <span class="text-red-500 mt-0.5">⚠</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @endforeach
                            @endforeach
                            @foreach ($errors->get('media') as $message)
                                <p class="text-xs text-red-700 flex items-start gap-2">
                                    <span class="text-red-500 mt-0.5">⚠</span>
                                    <span>{{ $message }}</span>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    @if ($media)
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ($media as $index => $image)
                                <div class="relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                    <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover" alt="معاينة رفع المنتج">

                                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-2 bg-black/40 px-2 py-2 text-xs text-white">
                                        <button type="button" wire:click="setPrimaryUpload('media:{{ $index }}')" class="rounded border border-white/20 bg-white/10 px-2 py-1 text-[10px] font-semibold hover:bg-white/20 {{ $primary_upload === 'media:' . $index ? 'text-amber-300' : '' }}">
                                            {{ $primary_upload === 'media:' . $index ? 'أساسي' : 'تعيين كأساسي' }}
                                        </button>
                                        <button type="button" wire:click="removeMedia({{ $index }})" class="rounded border border-white/20 bg-white/10 px-2 py-1 text-[10px] font-semibold text-rose-100 hover:bg-white/20">
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-medium text-slate-800">الصور حسب اللون</h3>
                                <p class="mt-1 text-xs text-slate-500">كل صورة تُرفع هنا تُحفظ مع <span class="font-mono">color_id</span> المناسب.</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ count($selected_colors) }} لون تم اختياره</span>
                        </div>

                        @if ($selected_colors)
                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach ($this->colors->whereIn('id', $selected_colors) as $color)
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <div class="mb-3 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-2">
                                                <span class="h-4 w-4 rounded-full border border-slate-300" style="background: {{ $color->color_hex_code ?: '#e2e8f0' }}"></span>
                                                <span class="text-sm font-semibold text-slate-800">{{ $color->color_name }}</span>
                                            </div>
                                            <span class="text-xs text-slate-500">معرف {{ $color->id }}</span>
                                        </div>

                                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center hover:bg-slate-100">
                                            <input type="file" multiple accept="image/*" wire:model="color_media.{{ $color->id }}" class="hidden">
                                            <span class="text-sm font-medium text-slate-700">رفع صور {{ $color->color_name }}</span>
                                            <span class="mt-1 text-xs text-slate-500">تُحفظ في product_images مع color_id</span>
                                        </label>

                                        @if ($errors->has('color_media.' . $color->id . '.*') || $errors->has('color_media.*.*'))
                                            <div class="mt-2 rounded-lg border border-red-200 bg-red-50 p-2">
                                                @foreach ($errors->get('color_media.' . $color->id . '.*') as $messages)
                                                    @foreach ($messages as $message)
                                                        <p class="text-xs text-red-700 flex items-start gap-2 mb-1">
                                                            <span class="text-red-500 mt-0.5">⚠</span>
                                                            <span>{{ $message }}</span>
                                                        </p>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (! empty($color_media[$color->id]))
                                            <div class="mt-3 grid grid-cols-3 gap-2">
                                                @foreach ($color_media[$color->id] as $index => $image)
                                                    <div class="relative aspect-square overflow-hidden rounded-md border border-slate-200 bg-slate-100">
                                                        <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover" alt="معاينة {{ $color->color_name }}">

                                                        <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-2 bg-black/40 px-2 py-2 text-xs text-white">
                                                            <button type="button" wire:click="setPrimaryUpload('color_media:{{ $color->id }}:{{ $index }}')" class="rounded border border-white/20 bg-white/10 px-2 py-1 text-[10px] font-semibold hover:bg-white/20 {{ $primary_upload === 'color_media:' . $color->id . ':' . $index ? 'text-amber-300' : '' }}">
                                                                {{ $primary_upload === 'color_media:' . $color->id . ':' . $index ? 'أساسي' : 'تعيين كأساسي' }}
                                                            </button>
                                                            <button type="button" wire:click="removeColorMedia({{ $color->id }}, {{ $index }})" class="rounded border border-white/20 bg-white/10 px-2 py-1 text-[10px] font-semibold text-rose-100 hover:bg-white/20">
                                                                حذف
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                                قم بتحديد الألوان في مولد الخيارات لرفع صور خاصة بكل لون.
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-950">مولد الخيارات (التبويبات)</h2>
                        <p class="mt-1 text-sm text-slate-500">اختر خيارات الألوان والمقاسات، ثم أنشئ صفوف الخيارات القابلة للتعديل.</p>
                    </div>
                    <button type="button" wire:click="generateVariants" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        إنشاء الخيارات
                    </button>
                </div>

                <div class="grid gap-5 p-5 md:grid-cols-2">
                    <div>
                        <p class="mb-3 text-sm font-medium text-slate-700">الألوان</p>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse ($this->colors as $color)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:border-[#008060]">
                                    <input type="checkbox" value="{{ $color->id }}" wire:model="selected_colors" class="rounded border-slate-300 text-[#008060] focus:ring-[#008060]">
                                    <span class="h-3 w-3 rounded-full border border-slate-300" style="background: {{ $color->color_hex_code ?: '#e2e8f0' }}"></span>
                                    <span>{{ $color->color_name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-slate-500">لا توجد ألوان نشطة.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <p class="mb-3 text-sm font-medium text-slate-700">المقاسات</p>
                        <div class="grid grid-cols-3 gap-2">
                            @forelse ($this->sizes as $size)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:border-[#008060]">
                                    <input type="checkbox" value="{{ $size->id }}" wire:model="selected_sizes" class="rounded border-slate-300 text-[#008060] focus:ring-[#008060]">
                                    <span>{{ $size->size_name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-slate-500">لا توجد مقاسات نشطة.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">جدول الخيارات</h2>
                    <p class="mt-1 text-sm text-slate-500">راجع أكواد SKU والأسعار والكميات قبل الحفظ.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[920px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">الخيار</th>
                                <th class="px-4 py-3 font-semibold">SKU</th>
                                <th class="px-4 py-3 font-semibold">التكلفة</th>
                                <th class="px-4 py-3 font-semibold">السعر</th>
                                <th class="px-4 py-3 font-semibold">الكمية</th>
                                <th class="px-4 py-3 font-semibold">الفرع</th>
                                <th class="px-4 py-3 font-semibold">نشط</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($variants as $index => $variant)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $variant['label'] }}</td>
                                    <td class="px-4 py-3">
                                        <input type="text" wire:model="variants.{{ $index }}.sku" class="w-40 rounded-md border border-slate-300 px-2 py-1.5 text-xs font-mono outline-none focus:border-[#008060]">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="0" step="0.01" wire:model="variants.{{ $index }}.variant_cost" class="w-24 rounded-md border border-slate-300 px-2 py-1.5 text-xs outline-none focus:border-[#008060]">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="0" step="0.01" wire:model="variants.{{ $index }}.variant_price" class="w-24 rounded-md border border-slate-300 px-2 py-1.5 text-xs outline-none focus:border-[#008060]">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="0" wire:model="variants.{{ $index }}.quantity" class="w-20 rounded-md border border-slate-300 px-2 py-1.5 text-xs outline-none focus:border-[#008060]">
                                    </td>
                                    <td class="px-4 py-3">
                                        <select wire:model="variants.{{ $index }}.branch_id" class="w-36 rounded-md border border-slate-300 px-2 py-1.5 text-xs outline-none focus:border-[#008060]">
                                            <option value="">الفرع الافتراضي</option>
                                            @foreach ($this->branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="checkbox" wire:model="variants.{{ $index }}.is_active" class="rounded border-slate-300 text-[#008060] focus:ring-[#008060]">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" wire:click="removeVariant({{ $index }})" class="rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50">إزالة</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">قم بإنشاء الخيارات، أو احفظ الآن لإنشاء خيار افتراضي.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">المخزون</h2>
                    <p class="mt-1 text-sm text-slate-500">اختر الفرع الافتراضي، ثم يمكنك تجاوزه لكل خيار في جدول الخيارات عند الحاجة.</p>
                </div>

                <div class="grid gap-4 p-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">الفرع الافتراضي</label>
                        <select wire:model="branch_id" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            <option value="">لا تقم بإنشاء مخزون الآن</option>
                            @foreach ($this->branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        اختيارات الفرع في الخيارات تتجاوز هذا الافتراضي. إذا لم يتم اختيار أي منهما، فسيتم حفظ الخيار بدون سجلات مخزون.
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                <a href="{{ route('products') }}" wire:navigate class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    إلغاء
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="rounded-lg bg-[#008060] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#006e52] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="save">حفظ المنتج</span>
                    <span wire:loading wire:target="save">جاري الحفظ...</span>
                </button>
            </div>
        </main>

        <aside x-show="sidebarOpen" x-transition class="space-y-5 lg:sticky lg:top-20 lg:self-start">
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">ملخص</h2>
                </div>
                <div class="space-y-3 p-5 text-sm">
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">المنتج</span>
                        <span class="max-w-40 truncate font-medium text-slate-900">{{ $product_name ?: 'بدون عنوان' }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">الحالة</span>
                        <span class="font-medium {{ $is_active ? 'text-[#008060]' : 'text-amber-700' }}">{{ $is_active ? 'نشط' : 'مسودة' }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">الخيارات</span>
                        <span class="font-medium text-slate-900">{{ count($variants) ?: 1 }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">الصور</span>
                        <span class="font-medium text-slate-900">{{ count($media) + collect($color_media)->flatten(1)->count() }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">المخزون الأساسي</span>
                        <span class="font-medium text-slate-900">{{ $product_quantity ?: 0 }}</span>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-950">التنظيم</h2>
                </div>
                <div class="space-y-4 p-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">التصنيف</label>
                        <select wire:model.live="category_id" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            <option value="">اختر التصنيف</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">التصنيف الفرعي</label>
                        <select wire:model="sub_category_id" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            <option value="">بدون تصنيف فرعي</option>
                            @foreach ($this->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->sub_category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">المجموعة</label>
                        <select wire:model="collection_id" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#008060] focus:ring-2 focus:ring-[#008060]/15">
                            <option value="">بدون مجموعة</option>
                            @foreach ($this->collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->collection_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
        </aside>
    </form>
</div>
