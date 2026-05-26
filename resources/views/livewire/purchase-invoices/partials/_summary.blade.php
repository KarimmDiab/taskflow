<section class="mx-auto grid max-w-[1800px] gap-4 px-4 pb-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-6">
    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Payment</h2>
        <div class="grid gap-3 md:grid-cols-4">
            <div class="rounded-md bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Subtotal</div>
                <div class="font-mono text-xl font-bold text-slate-950">{{ number_format($this->invoiceTotal, 2) }}</div>
            </div>
            <div class="rounded-md bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Total quantity</div>
                <div class="font-mono text-xl font-bold text-slate-950">
                    {{ number_format(collect($rows)->sum(fn($r) => (float) ($r['qty'] ?? 0)), 2) }}</div>
            </div>
            <label><span class="mb-1 block text-xs font-semibold text-slate-500">Payment Method</span><select
                    wire:model.blur="payment_method"
                    class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <option value="">Select method</option>
                    @foreach ($this->paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->payment_method_name }}</option>
                    @endforeach
                </select></label>
            <label>
                <span class="mb-1 block text-xs font-semibold text-slate-500">
                    Paid Amount
                </span>

                <input type="number" wire:model.live.debounce.300ms="paid_amount" min="0" step="0.01"
                    class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-right text-sm font-semibold focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">

                @error('paid_amount')
                    <span class="mt-1 block text-xs text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </label>
        </div>
    </div>

    <aside class="space-y-4 lg:row-start-1 lg:col-start-2">
        <section class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Invoice Summary</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm"><span class="text-slate-500">Subtotal</span><span
                        class="font-mono font-bold">{{ number_format($this->invoiceTotal, 2) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-slate-500">Paid</span><span
                        class="font-mono font-bold text-emerald-700">{{ number_format($paid_amount, 2) }}</span></div>
                <div class="rounded-md bg-slate-950 p-3 text-white">
                    <div class="text-xs uppercase tracking-wide text-slate-300">Remaining</div>
                    <div class="mt-1 font-mono text-2xl font-bold">{{ number_format($this->remainingAmount, 2) }}</div>
                </div>
            </div>
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Supplier Summary</h2>
            @php($selectedSupplier = $this->suppliers->firstWhere('id', $supplier_id))
            <div class="rounded-md bg-slate-50 p-3 text-sm">
                <div class="font-semibold text-slate-950">
                    {{ $selectedSupplier?->supplier_name ?? 'No supplier selected' }}</div>
                <div class="mt-1 text-xs text-slate-500">Warehouse:
                    {{ $this->branches->firstWhere('id', $branch_id)?->branch_name ?? 'not selected' }}</div>
            </div>
        </section>
        <section class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-slate-500">Invoice Image</h2>
            <div x-data="{ dragging: false }" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; $event.dataTransfer.files.length && $wire.upload('invoice_image', $event.dataTransfer.files[0])"
                class="rounded-md border-2 border-dashed border-slate-300 bg-slate-50 p-4 text-center"
                :class="dragging ? 'border-slate-900 bg-white' : ''">
                @if (!$imagePreview)
                    <input id="invoice-image-input" type="file" wire:model.live="invoice_image" accept="image/*"
                        class="hidden">
                    <button type="button" onclick="document.getElementById('invoice-image-input').click()"
                        class="mx-auto h-10 rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Upload
                        image</button>
                    <div class="mt-2 text-xs text-slate-500">Drop receipt image or click to upload. PNG or JPG up to
                        5MB.</div>
                @else
                    <div class="relative overflow-hidden rounded-md border border-slate-200 bg-white"><img
                            src="{{ $imagePreview }}" alt="Invoice preview"
                            class="max-h-56 w-full object-contain"><button type="button" wire:click="removeImage"
                            class="absolute right-2 top-2 rounded-md bg-slate-950/80 px-2 py-1 text-xs font-semibold text-white">Remove</button>
                    </div>
                @endif
            </div>
            @error('invoice_image')
                <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
            @enderror
        </section>
    </aside>
</section>
