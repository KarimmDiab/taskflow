@if ($showModal)
    <div class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-sm" wire:click.self="closeModal"
        @keydown.escape.window="$wire.closeModal()">
        <section class="ml-auto flex h-full w-full max-w-xl flex-col bg-white shadow-2xl" wire:click.stop>
            <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Quick Create Variant</h2>
                    <p class="text-xs text-slate-500">Create product, color, size, SKU, then inject it into the current
                        row.</p>
                </div>
                <button type="button" wire:click="closeModal"
                    class="flex h-9 w-9 items-center justify-center rounded-md border border-slate-300 text-slate-600 hover:bg-slate-50">X</button>
            </header>
            <div class="flex-1 overflow-y-auto p-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="sm:col-span-2"><span class="mb-1 block text-xs font-semibold text-slate-500">Product
                            Name</span><input type="text" wire:model.blur="newProductName"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        @error('newProductName')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Base Code</span><input
                            type="text" wire:model.blur="newProductCode" dir="ltr"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 font-mono text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        @error('newProductCode')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Size</span>
                        <select wire:model.blur="newProductSize"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Select size</option>
                            @foreach ($this->sizes as $size)
                                <option value="{{ $size->size_name }}">{{ $size->size_name }}</option>
                            @endforeach
                        </select>
                        @error('newProductSize')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Category</span><select
                            wire:model.live="newProductCategoryId"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Select category</option>
                            @foreach ($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        @error('newProductCategoryId')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Sub Category</span><select
                            wire:model.blur="newProductSubCategoryId"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Select sub category</option>
                            @foreach ($this->subCategories as $subCat)
                                <option value="{{ $subCat->id }}">{{ $subCat->sub_category_name }}</option>
                            @endforeach
                        </select>
                        @error('newProductSubCategoryId')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Color field with select from colors table + custom option --}}
                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-xs font-semibold text-slate-500">Color</span>
                        <select wire:model.live="newProductColor"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">Select color</option>
                            @foreach ($this->colors as $color)
                                <option value="{{ $color->color_hex_code }}"
                                    style="background-color: {{ $color->color_hex_code }}; color: {{ $color->color_hex_code == '#FFFFFF' ? '#000' : '#fff' }};">
                                    {{ $color->color_name }} ({{ $color->color_hex_code }})
                                </option>
                            @endforeach
                            <option value="custom">+ Custom color</option>
                        </select>

                        {{-- Custom color input (shown only when "custom" is selected) --}}
                        @if ($newProductColor === 'custom')
                            <div class="mt-2 flex items-center gap-2">
                                <input type="color" x-data x-on:input="$wire.newProductCustomColor = $el.value"
                                    value="{{ $newProductCustomColor ?? '#000000' }}"
                                    class="h-10 w-14 rounded-md border border-slate-300 p-1">
                                <input type="text" wire:model.blur="newProductCustomColor" dir="ltr"
                                    placeholder="#000000"
                                    class="h-10 flex-1 rounded-md border border-slate-300 px-3 font-mono text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            </div>
                        @endif

                        @error('newProductColor')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                        @error('newProductCustomColor')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Cost</span><input
                            type="number" wire:model.blur="newProductCost" min="0" step="0.01"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-right text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        @error('newProductCost')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label><span class="mb-1 block text-xs font-semibold text-slate-500">Selling Price</span><input
                            type="number" wire:model.blur="newProductSell" min="0" step="0.01"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-right text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        @error('newProductSell')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
            </div>
            <footer class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                <button type="button" wire:click="closeModal"
                    class="h-9 rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" wire:click="saveNewProduct" wire:loading.attr="disabled"
                    class="h-9 rounded-md bg-slate-950 px-4 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-60"><span
                        wire:loading.remove wire:target="saveNewProduct">Create and Select</span><span wire:loading
                        wire:target="saveNewProduct">Creating...</span></button>
            </footer>
        </section>
    </div>
@endif
