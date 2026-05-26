<section class="px-4 pt-4 lg:px-6">
    <div class="grid gap-3 rounded-md border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-2 xl:grid-cols-5">
        <label class="block">
            <span class="mb-1 block text-xs font-semibold text-slate-500">Invoice Number</span>
            <input type="text" wire:model.blur="invoice_number" dir="ltr" class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm font-semibold focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            @error('invoice_number') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="block">
            <span class="mb-1 block text-xs font-semibold text-slate-500">Supplier</span>
            <select wire:model.live="supplier_id" class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                <option value="">Select supplier</option>
                @foreach ($this->suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="block">
            <span class="mb-1 block text-xs font-semibold text-slate-500">Purchase Date</span>
            <input type="date" wire:model.blur="purchase_invoice_date" class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            @error('purchase_invoice_date') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="block">
            <span class="mb-1 block text-xs font-semibold text-slate-500">Branch / Warehouse</span>
            <select wire:model.live="branch_id" class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                <option value="">Select branch</option>
                @foreach($this->branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
            @error('branch_id') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
        </label>
        <div>
            <span class="mb-1 block text-xs font-semibold text-slate-500">Payment Status</span>
            <div class="flex h-10 items-center justify-between rounded-md border border-slate-300 bg-slate-50 px-3 text-sm">
                <span class="font-semibold {{ $this->remainingAmount > 0 ? 'text-amber-700' : 'text-emerald-700' }}">{{ $this->remainingAmount > 0 ? 'Partially paid' : 'Paid' }}</span>
                <span class="text-xs text-slate-500">{{ number_format($this->remainingAmount, 2) }} due</span>
            </div>
        </div>
    </div>
</section>
