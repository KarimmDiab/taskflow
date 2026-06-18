<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Requests\UpdateCheckoutRequest;
use App\Mail\OrderInvoiceMail;
use App\Models\Branches;
use App\Models\Checkout;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\OnlineOrder;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use App\Models\Shipping;
use App\Models\User;
use App\Services\DiscountService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $govs = Shipping::where('is_active', true)->get(); // فقط المحافظات النشطة

        return view('ryo-checkout', compact('govs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckoutRequest $request)
    {
        $data = $request->validated();

        $invoice = DB::transaction(function () use ($data) {
            $customerEmail = $this->validEmail($data['email'] ?? null);
            $cart = collect($data['cart'])
                ->groupBy(fn ($item) => (int) $item['variantId'])
                ->map(fn ($items, $variantId) => [
                    'variant_id' => (int) $variantId,
                    'quantity' => $items->sum(fn ($item) => (int) $item['quantity']),
                ])
                ->values();

            $branchId = $this->findFulfillmentBranchId($cart);

            if (! $branchId) {
                throw ValidationException::withMessages([
                    'cart' => 'Some items are not available in stock.',
                ]);
            }

            $paymentMethodId = PaymentMethod::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->where('payment_method_name', 'like', '%cash%')
                        ->orWhere('payment_method_name', 'like', '%cod%')
                        ->orWhere('payment_method_name', 'like', '%delivery%');
                })
                ->orderBy('id')
                ->value('id') ?? PaymentMethod::query()->where('is_active', true)->orderBy('id')->value('id');

            $userId = auth()->id() ?? User::query()->orderBy('id')->value('id');

            if (! $paymentMethodId || ! $userId) {
                throw ValidationException::withMessages([
                    'cart' => 'Checkout is not configured yet. Please contact support.',
                ]);
            }

            $variantIds = $cart->pluck('variant_id')->all();
            $variants = ProductVariant::query()
                ->with('product')
                ->whereIn('id', $variantIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            if ($variants->count() !== count($variantIds)) {
                throw ValidationException::withMessages([
                    'cart' => 'One or more products are no longer available.',
                ]);
            }

            $subtotal = 0;
            foreach ($cart as $item) {
                $variant = $variants[$item['variant_id']];
                $unitPrice = (float) ($variant->variant_price ?: $variant->product?->product_price ?: 0);
                $subtotal += $unitPrice * $item['quantity'];
            }

            $shipping = Shipping::query()->where('is_active', true)->findOrFail($data['shipping_id']);
            $shippingCost = $subtotal >= 1500 ? 0 : max(0, (float) ($shipping->shipping_cost ?? 0));
            $coupon = null;
            $deduction = 0;

            if (! empty($data['coupon_code'])) {
                $couponResult = app(DiscountService::class)->validateCoupon($data['coupon_code'], $subtotal);
                $coupon = $couponResult['coupon'];
                $deduction = (float) $couponResult['discount_amount'];
            }

            $netTotal = max(0, $subtotal + $shippingCost - $deduction);
            $customerName = trim($data['first_name'].' '.$data['last_name']);
            $customer = Customer::query()->firstOrCreate(
                ['contact_info' => $data['phone']],
                ['customer_name' => $customerName]
            );

            $invoice = SalesInvoice::create([
                'invoice_number' => $this->generateInvoiceNumber('RYO'),
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'deduction' => $deduction,
                'discount_amount' => $deduction,
                'discount_type' => $coupon?->type,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'grand_total' => $netTotal,
                'net_total' => $netTotal,
                'paid_amount' => 0,
                'remaining_amount' => $netTotal,
                'customer_id' => $customer->id,
                'payment_method_id' => $paymentMethodId,
                'user_id' => $userId,
                'branch_id' => $branchId,
            ]);

            foreach ($cart as $item) {
                $variant = $variants[$item['variant_id']];
                $unitPrice = (float) ($variant->variant_price ?: $variant->product?->product_price ?: 0);

                $detail = SalesInvoiceDetail::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_variant_id' => $item['variant_id'],
                    'product_quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'cost_price' => (float) ($variant->variant_cost ?: $variant->product?->product_cost ?: 0),
                    'line_total' => $unitPrice * $item['quantity'],
                    'line_total_after_discount' => $unitPrice * $item['quantity'],
                ]);

                app(StockMovementService::class)->recordSale(
                    (int) $branchId,
                    (int) $item['variant_id'],
                    (int) $item['quantity'],
                    $unitPrice,
                    $invoice,
                    "Online order sale line #{$detail->id} for invoice {$invoice->invoice_number}",
                    true,
                );
            }

            OnlineOrder::create([
                'sales_invoice_id' => $invoice->id,
                'shipping_id' => $shipping->id,
                'shipping_cost' => (int) round($shippingCost),
                'subtotal' => $subtotal,
                'discount_amount' => $deduction,
                'discount_type' => $coupon?->type,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'grand_total' => $netTotal,
                'net_total' => $netTotal,
                'address' => trim($data['address1'].' '.($data['address2'] ?? '')),
                'area' => $data['district'],
                'order_note' => $data['notes'] ?? null,
                'status' => 'pending',
                'customer_name' => $customerName,
                'customer_phone' => $data['phone'],
                'customer_email' => $customerEmail,
            ]);

            return $invoice;
        });

        $invoice->load([
            'customer',
            'onlineOrder.shipping',
            'salesInvoiceDetails.productVariant.product',
            'salesInvoiceDetails.productVariant.color',
            'salesInvoiceDetails.productVariant.size',
        ]);

        $this->sendInvoiceEmail($invoice);

        return response()->json([
            'message' => 'Order placed successfully.',
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
        ]);
    }

    public function validateCoupon(Request $request, DiscountService $discounts)
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:255'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.variantId' => ['required', 'integer', 'exists:product_variants,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = collect($validated['cart'])
            ->groupBy(fn ($item) => (int) $item['variantId'])
            ->map(fn ($items, $variantId) => [
                'variant_id' => (int) $variantId,
                'quantity' => $items->sum(fn ($item) => (int) $item['quantity']),
            ])
            ->values();

        $variants = ProductVariant::query()
            ->with('product')
            ->whereIn('id', $cart->pluck('variant_id')->all())
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        foreach ($cart as $item) {
            $variant = $variants->get($item['variant_id']);
            if (! $variant) {
                throw ValidationException::withMessages(['cart' => 'One or more products are no longer available.']);
            }

            $subtotal += (float) ($variant->variant_price ?: $variant->product?->product_price ?: 0) * $item['quantity'];
        }

        $result = $discounts->validateCoupon($validated['coupon_code'], $subtotal);
        /** @var Coupon $coupon */
        $coupon = $result['coupon'];

        return response()->json([
            'message' => 'Coupon applied.',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => (float) $coupon->value,
            ],
            'discount_amount' => (float) $result['discount_amount'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkout $Checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $Checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckoutRequest $request, Checkout $Checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $Checkout)
    {
        //
    }

    private function findFulfillmentBranchId($cart): ?int
    {
        $branchIds = Branches::query()->orderBy('id')->pluck('id');

        foreach ($branchIds as $branchId) {
            $canFulfill = true;

            foreach ($cart as $item) {
                $quantity = Inventory::query()
                    ->where('branch_id', $branchId)
                    ->where('product_variant_id', $item['variant_id'])
                    ->value('quantity') ?? 0;

                if ($quantity < $item['quantity']) {
                    $canFulfill = false;
                    break;
                }
            }

            if ($canFulfill) {
                return (int) $branchId;
            }
        }

        return null;
    }

    private function validEmail(?string $email): ?string
    {
        $email = trim((string) $email);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $email;
    }

    private function sendInvoiceEmail(SalesInvoice $invoice): void
    {
        $email = $this->validEmail($invoice->onlineOrder?->customer_email);

        if (! $email) {
            return;
        }

        try {
            $mail = Mail::to($email);
            $mailable = new OrderInvoiceMail($invoice);

            if (config('queue.default') && config('queue.default') !== 'sync') {
                $mail->queue($mailable);
            } else {
                $mail->send($mailable);
            }
        } catch (\Throwable $exception) {
            Log::error('Failed to send order invoice email.', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function generateInvoiceNumber(string $prefix): string
    {
        $date = now()->format('ymd');

        $lastInvoice = SalesInvoice::whereDate('created_at', today())
            ->where('invoice_number', 'like', $prefix.'-'.$date.'-%')
            ->latest('id')
            ->first();

        $sequence = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf(
            '%s-%s-%03d',
            strtoupper($prefix),
            $date,
            $sequence
        );
    }
}
