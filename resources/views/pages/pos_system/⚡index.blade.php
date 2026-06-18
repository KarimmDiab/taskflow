<?php

use App\Models\Branches;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use App\Services\SalesInvoiceService;
use App\Services\DiscountService;
use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('POS System')] class extends Component {
    public string $search = '';
    public string $barcodeInput = '';
    public ?int $branch_id = null;
    public ?int $customer_id = null;
    public ?int $payment_method_id = null;
    public float $deduction = 0;
    public string $discount_type = 'fixed';
    public float $discount_value = 0;
    public float $paid_amount = 0;
    public array $cart = [];
    public bool $isCheckingOut = false;
    public ?string $notification = null;
    public ?string $notificationType = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('pos_system.view'), 403);

        $this->branch_id = Branches::query()->orderBy('id')->value('id');
        $this->customer_id = Customer::query()->orderBy('customer_name')->value('id');
        $this->payment_method_id = PaymentMethod::query()->where('is_active', true)->orderBy('id')->value('id');
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get();
    }

    public function getCustomersProperty()
    {
        return Customer::query()->orderBy('customer_name')->get();
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::query()->where('is_active', true)->orderBy('payment_method_name')->get();
    }

    public function getVariantsProperty()
    {
        return ProductVariant::query()
            ->with(['product.primaryImage', 'product.category', 'color', 'size', 'inventories' => fn($query) => $query->where('branch_id', $this->branch_id)])
            ->where('is_active', true)
            ->whereHas('product', fn($query) => $query->where('is_active', true))
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('sku', 'like', "%{$this->search}%")
                        ->orWhere('barcode', 'like', "%{$this->search}%")
                        ->orWhereHas('product', function ($productQuery) {
                            $productQuery->where('product_name', 'like', "%{$this->search}%")
                                ->orWhere('product_code', 'like', "%{$this->search}%");
                        });
                });
            })
            ->whereHas('inventories', fn($query) => $query->where('branch_id', $this->branch_id)->where('quantity', '>', 0))
            ->latest()
            ->take(24)
            ->get();
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['quantity'] * $item['unit_price']);
    }

    public function getItemDiscountTotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => (float) ($item['item_discount_amount'] ?? 0));
    }

    public function getSubtotalAfterItemDiscountsProperty(): float
    {
        return max($this->subtotal - $this->itemDiscountTotal, 0);
    }

    public function getInvoiceDiscountAmountProperty(): float
    {
        try {
            return app(DiscountService::class)->calculateAmount($this->discount_type, $this->discount_value, $this->subtotalAfterItemDiscounts);
        } catch (\Illuminate\Validation\ValidationException) {
            return 0;
        }
    }

    public function getNetTotalProperty(): float
    {
        return max($this->subtotalAfterItemDiscounts - $this->invoiceDiscountAmount, 0);
    }

    public function getRemainingAmountProperty(): float
    {
        return max($this->netTotal - $this->paid_amount, 0);
    }

    public function addToCart(int $variantId): void
    {
        abort_unless(auth()->user()?->can('pos_system.create'), 403);

        $variant = ProductVariant::with(['product', 'color', 'size', 'inventories' => fn($query) => $query->where('branch_id', $this->branch_id)])->findOrFail($variantId);
        $available = (int) ($variant->inventories->first()?->quantity ?? 0);

        if ($available < 1) {
            $this->showNotification('Product is out of stock in the selected branch.', 'error');
            return;
        }

        $currentQuantity = $this->cart[$variantId]['quantity'] ?? 0;
        if ($currentQuantity + 1 > $available) {
            $this->showNotification('Selected quantity exceeds available stock.', 'error');
            return;
        }

        $this->cart[$variantId] = [
            'variant_id' => $variant->id,
            'name' => $variant->product->product_name,
            'variant' => trim(($variant->color?->color_name ?? '') . ' ' . ($variant->size?->size_name ?? '')),
            'available' => $available,
            'quantity' => $currentQuantity + 1,
            'unit_price' => (float) ($variant->variant_price ?: $variant->product->product_price),
            'item_discount_type' => $this->cart[$variantId]['item_discount_type'] ?? 'fixed',
            'item_discount_value' => (float) ($this->cart[$variantId]['item_discount_value'] ?? 0),
            'item_discount_amount' => (float) ($this->cart[$variantId]['item_discount_amount'] ?? 0),
        ];

        $this->recalculateItemDiscount($variantId);

        $this->paid_amount = $this->netTotal;
        $this->showNotification('Added to cart', 'success');
    }

    public function scanBarcode(): void
    {
        abort_unless(auth()->user()?->can('pos_system.create'), 403);

        $code = trim($this->barcodeInput);
        $this->barcodeInput = '';

        if ($code === '') {
            return;
        }

        $variant = ProductVariant::query()
            ->where('barcode', $code)
            ->orWhere('sku', $code)
            ->first();

        if (! $variant) {
            $this->showNotification("Barcode or SKU not found: {$code}", 'error');
            return;
        }

        $this->addToCart($variant->id);
    }

    public function increment(int $variantId): void
    {
        if (!isset($this->cart[$variantId])) {
            return;
        }

        if ($this->cart[$variantId]['quantity'] >= $this->cart[$variantId]['available']) {
            $this->showNotification('Selected quantity exceeds available stock.', 'error');
            return;
        }

        $this->cart[$variantId]['quantity']++;
        $this->recalculateItemDiscount($variantId);
        $this->paid_amount = $this->netTotal;
    }

    public function decrement(int $variantId): void
    {
        if (!isset($this->cart[$variantId])) {
            return;
        }

        $this->cart[$variantId]['quantity']--;

        if ($this->cart[$variantId]['quantity'] <= 0) {
            unset($this->cart[$variantId]);
        } else {
            $this->recalculateItemDiscount($variantId);
        }

        $this->paid_amount = $this->netTotal;
    }

    public function removeFromCart(int $variantId): void
    {
        unset($this->cart[$variantId]);
        $this->paid_amount = $this->netTotal;
        $this->showNotification('Item removed', 'info');
    }

    public function clearCart(): void
    {
        if (empty($this->cart)) {
            return;
        }
        $this->cart = [];
        $this->deduction = 0;
        $this->discount_type = 'fixed';
        $this->discount_value = 0;
        $this->paid_amount = 0;
        $this->showNotification('Cart cleared', 'info');
    }

    public function updatedDiscountValue(): void
    {
        $this->discount_value = max((float) $this->discount_value, 0);
        $this->deduction = $this->invoiceDiscountAmount;
        $this->paid_amount = min((float) $this->paid_amount, $this->netTotal);
    }

    public function updatedDiscountType(): void
    {
        $this->updatedDiscountValue();
    }

    public function updatedCart($value, string $key): void
    {
        if (! str_contains($key, '.item_discount_')) {
            return;
        }

        abort_unless(auth()->user()?->can('discounts.manual.apply'), 403);

        $variantId = (int) str($key)->before('.')->toString();
        $this->recalculateItemDiscount($variantId);
        $this->paid_amount = min((float) $this->paid_amount, $this->netTotal);
    }

    public function checkout(): void
    {
        abort_unless(auth()->user()?->can('pos_system.create'), 403);
        $this->isCheckingOut = true;

        $this->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'discount_value' => ['numeric', 'min:0'],
            'paid_amount' => ['numeric', 'min:0'],
            'cart' => ['required', 'array', 'min:1'],
        ]);

        if (($this->discount_value > 0 || $this->itemDiscountTotal > 0) && ! auth()->user()?->can('discounts.manual.apply')) {
            $this->addError('discount_value', 'You do not have permission to apply manual discounts.');
            $this->isCheckingOut = false;
            return;
        }

        if ($this->discount_type === 'percentage' && $this->discount_value > 100) {
            $this->addError('discount_value', 'Percentage discount cannot exceed 100%.');
            $this->isCheckingOut = false;
            return;
        }

        if ($this->discount_type === 'fixed' && $this->discount_value > $this->subtotalAfterItemDiscounts) {
            $this->addError('discount_value', 'Discount cannot exceed subtotal.');
            $this->isCheckingOut = false;
            return;
        }

        if ($this->remainingAmount > 0) {
            $this->addError('paid_amount', 'Paid amount must cover the net total.');
            $this->isCheckingOut = false;
            return;
        }

        app(SalesInvoiceService::class)->create([
            'discount_type' => $this->discount_value > 0 ? $this->discount_type : null,
            'discount_value' => $this->discount_value,
            'deduction' => $this->invoiceDiscountAmount,
            'paid_amount' => $this->paid_amount,
            'customer_id' => $this->customer_id,
            'payment_method_id' => $this->payment_method_id,
            'user_id' => auth()->id(),
            'branch_id' => $this->branch_id,
        ], collect($this->cart)->map(fn (array $item): array => [
            'product_variant_id' => $item['variant_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['unit_price'],
            'item_discount_type' => ($item['item_discount_value'] ?? 0) > 0 ? ($item['item_discount_type'] ?? 'fixed') : null,
            'item_discount_value' => (float) ($item['item_discount_value'] ?? 0),
        ])->values()->all());

        $this->cart = [];
        $this->deduction = 0;
        $this->discount_type = 'fixed';
        $this->discount_value = 0;
        $this->paid_amount = 0;
        $this->isCheckingOut = false;
        $this->showNotification('Sale completed successfully!', 'success');
    }

    private function showNotification(string $message, string $type = 'success'): void
    {
        $this->notification = $message;
        $this->notificationType = $type;
        $this->dispatch('notification-shown');
    }

    private function recalculateItemDiscount(int $variantId): void
    {
        if (! isset($this->cart[$variantId])) {
            return;
        }

        $item = $this->cart[$variantId];
        $lineSubtotal = (float) $item['quantity'] * (float) $item['unit_price'];
        $type = $item['item_discount_type'] ?? 'fixed';
        $value = max((float) ($item['item_discount_value'] ?? 0), 0);

        if ($type === 'percentage' && $value > 100) {
            $value = 100;
            $this->cart[$variantId]['item_discount_value'] = 100;
        }

        $this->cart[$variantId]['item_discount_amount'] = app(DiscountService::class)->calculateAmount($type, $value, $lineSubtotal);
    }
};
?>

<div x-data="{ showNotification: false, notificationMessage: '', notificationType: '' }"
    x-on:notification-shown.window="
        notificationMessage = '{{ addslashes($notification) }}';
        notificationType = '{{ $notificationType }}';
        showNotification = true;
        setTimeout(() => showNotification = false, 4000);
     "
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950 font-sans antialiased">

    <!-- Animated background blobs (professional subtle) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-300/20 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-300/20 dark:bg-indigo-500/5 rounded-full blur-3xl">
        </div>
    </div>

    <!-- Toast Notification (professional slide-in) -->
    <div x-show="showNotification" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md">
        <div :class="{
            'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800': notificationType === 'success',
            'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800': notificationType === 'error',
            'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800': notificationType === 'info'
        }"
            class="rounded-2xl shadow-xl p-4 backdrop-blur-sm border">
            <div class="flex items-center gap-3">
                <template x-if="notificationType === 'success'">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="notificationType === 'error'">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="notificationType === 'info'">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <p x-text="notificationMessage" class="text-sm font-medium"
                    :class="{
                        'text-emerald-800 dark:text-emerald-200': notificationType === 'success',
                        'text-red-800 dark:text-red-200': notificationType === 'error',
                        'text-blue-800 dark:text-blue-200': notificationType === 'info'
                    }">
                </p>
                <button @click="showNotification = false"
                    class="mr-auto text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8 py-6 ">
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_480px] gap-8">
            <!-- Left Column: Products -->
            <div class="space-y-6">
                <!-- Header + Filters -->
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-8 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full shadow-md">
                            </div>
                            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Point of Sale
                            </h1>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M6 4h16">
                                </path>
                            </svg>
                            Fast and secure checkout
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="relative">
                            <select wire:model.live="branch_id"
                                class="appearance-none bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 shadow-sm">
                                @foreach ($this->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search by name, SKU, or barcode..."
                                class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-xl pr-4 pl-10 py-2.5 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 shadow-sm">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative flex-1 min-w-[220px]">
                            <form wire:submit.prevent="scanBarcode">
                                <input type="text" wire:model="barcodeInput" autofocus
                                    placeholder="Scan barcode or SKU + Enter"
                                    class="w-full text-sm border border-blue-200 dark:border-blue-800 rounded-xl pr-4 pl-10 py-2.5 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 shadow-sm">
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 8h.01M11 8h.01M15 8h2M7 12h2M13 12h.01M17 12h.01M7 16h.01M11 16h6" />
                                    </svg>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product Grid (Professional Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse ($this->variants as $variant)
                        @php
                            $product = $variant->product;
                            $stock = (int) ($variant->inventories->first()?->quantity ?? 0);
                            $price = (float) ($variant->variant_price ?: $product->product_price);
                            $lowStock = $stock <= 5;
                        @endphp
                        <div
                            class="group relative bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-800 overflow-hidden">
                            <button type="button" wire:click="addToCart({{ $variant->id }})"
                                class="w-full text-right p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if ($product->primaryImage?->image_path)
                                            <img src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                alt="{{ $product->product_name }}"
                                                class="w-16 h-16 rounded-xl object-cover shadow-sm">
                                        @else
                                            <div
                                                class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                                                {{ mb_substr($product->product_name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 text-right">
                                        <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-1">
                                            {{ $product->product_name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $variant->color?->color_name }} - {{ $variant->size?->size_name }}
                                        </p>
                                        <p class="text-[11px] text-gray-400 mt-1 font-mono">
                                            {{ $variant->barcode ?: $variant->sku }}
                                        </p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span
                                                class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($price) }}
                                                ج.م</span>
                                            <span
                                                class="text-xs px-2.5 py-1 rounded-full font-medium {{ $lowStock ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                                {{ $lowStock ? '⚠️ Low Stock' : "Stock: {$stock}" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                            <div
                                class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full shadow-md">+ Add
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 bg-white/60 dark:bg-gray-900/60 py-16 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No products available in this branch.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Column: Cart (Professional Sidebar) -->
            <div class="xl:sticky xl:top-6 h-fit">
                <div
                    class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100/50 dark:border-gray-800/50 overflow-hidden transition-all duration-300">
                    <!-- Cart Header -->
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/50 to-white dark:from-gray-800/30 dark:to-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M6 4h16">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-bold text-gray-900 dark:text-white">Current Sale</h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ count($cart) }} item(s)
                                    </p>
                                </div>
                            </div>
                            @if (count($cart) > 0)
                                <button type="button" wire:click="clearCart" wire:confirm="Clear entire cart?"
                                    class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Clear
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col h-full">
                        <!-- Scrollable Cart Items -->
                        <div class="flex-1 overflow-y-auto max-h-[400px] px-6 py-4 space-y-3 custom-scrollbar">
                            @forelse ($cart as $variantId => $item)
                                <div
                                    class="bg-gray-50/80 dark:bg-gray-800/50 rounded-xl p-3 transition-all hover:shadow-sm">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                                {{ $item['name'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item['variant'] ?: 'Default' }}</p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ number_format($item['unit_price']) }} ج.م لكل قطعة</p>
                                        </div>
                                        <button type="button" wire:click="removeFromCart({{ $variantId }})"
                                            class="text-gray-400 hover:text-red-500 transition p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between mt-3">
                                        <div
                                            class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800">
                                            <button type="button" wire:click="decrement({{ $variantId }})"
                                                class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">-</button>
                                            <span
                                                class="px-3 py-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $item['quantity'] }}</span>
                                            <button type="button" wire:click="increment({{ $variantId }})"
                                                class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">+</button>
                                        </div>
                                        <span
                                            class="font-bold text-gray-900 dark:text-white">{{ number_format(($item['quantity'] * $item['unit_price']) - ($item['item_discount_amount'] ?? 0)) }}
                                            ج.م</span>
                                    </div>
                                    @can('discounts.manual.apply')
                                        <div class="mt-3 grid grid-cols-[120px_1fr] gap-2">
                                            <select wire:model.live="cart.{{ $variantId }}.item_discount_type"
                                                class="rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs dark:border-gray-700 dark:bg-gray-800">
                                                <option value="fixed">EGP off</option>
                                                <option value="percentage">% off</option>
                                            </select>
                                            <input type="number" min="0" step="0.01"
                                                wire:model.live.debounce.300ms="cart.{{ $variantId }}.item_discount_value"
                                                placeholder="Item discount"
                                                class="rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs dark:border-gray-700 dark:bg-gray-800">
                                        </div>
                                        @if (($item['item_discount_amount'] ?? 0) > 0)
                                            <p class="mt-2 inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-600 dark:bg-rose-950/40 dark:text-rose-300">
                                                -{{ number_format((float) $item['item_discount_amount'], 2) }} EGP item discount
                                            </p>
                                        @endif
                                    @endcan
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300 dark:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Cart is empty</p>
                                    <p class="text-xs mt-1">Add items to begin sale</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Cart Footer (Always visible) -->
                        <div
                            class="border-t border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 p-6 space-y-5">
                            <!-- Customer & Payment -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative">
                                    <select wire:model.live="customer_id"
                                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500/30">
                                        <option value="">Select Customer</option>
                                        @foreach ($this->customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative">
                                    <select wire:model.live="payment_method_id"
                                        class="w-full border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500/30">
                                        <option value="">select payment method</option>
                                        @foreach ($this->paymentMethods as $pm)
                                            <option value="{{ $pm->id }}">{{ $pm->payment_method_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Discount & Paid -->
                            <div class="space-y-3">
                                @can('discounts.manual.apply')
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Discount
                                        (ج.م)</label>
                                    <div class="grid grid-cols-[130px_1fr] gap-2">
                                        <select wire:model.live="discount_type"
                                            class="text-sm border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500/30">
                                            <option value="fixed">EGP off</option>
                                            <option value="percentage">% off</option>
                                        </select>
                                    <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="discount_value"
                                        class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500/30">
                                    </div>
                                    @error('discount_value')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endcan
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Paid
                                        Amount (ج.م)</label>
                                    <input type="number" step="0.01" min="0"
                                        wire:model.live="paid_amount"
                                        class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500/30">
                                    @error('paid_amount')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Totals Panel -->
                            <div
                                class="rounded-xl bg-gradient-to-r from-slate-50 to-white dark:from-gray-800/50 dark:to-gray-900 p-4 space-y-2 shadow-inner">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ number_format($this->subtotal) }}
                                        ج.م</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Discount</span>
                                    <span class="font-semibold text-red-500">{{ number_format($this->itemDiscountTotal + $this->invoiceDiscountAmount) }}
                                        ج.م</span>
                                </div>
                                <div
                                    class="flex justify-between text-base font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-900 dark:text-white">Net Total</span>
                                    <span
                                        class="text-blue-700 dark:text-blue-400">{{ number_format($this->netTotal) }}
                                        ج.م</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Remaining</span>
                                    <span
                                        class="font-semibold {{ $this->remainingAmount > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($this->remainingAmount) }}
                                        ج.م</span>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button type="button" wire:click="checkout" wire:loading.attr="disabled"
                                class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                                <span wire:loading.remove>Complete Sale</span>
                                <span wire:loading class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for cart items */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
</div>
