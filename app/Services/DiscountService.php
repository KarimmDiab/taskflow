<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\OnlineOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DiscountService
{
    public function calculateAmount(?string $type, float $value, float $subtotal): float
    {
        $subtotal = max(round($subtotal, 2), 0);
        $value = max(round($value, 2), 0);

        if ($value <= 0 || $subtotal <= 0) {
            return 0;
        }

        if ($type === Coupon::TYPE_PERCENTAGE) {
            if ($value > 100) {
                throw ValidationException::withMessages(['discount' => 'Percentage discount cannot exceed 100%.']);
            }

            return round(min($subtotal * ($value / 100), $subtotal), 2);
        }

        if ($type === Coupon::TYPE_FIXED || $type === null) {
            return round(min($value, $subtotal), 2);
        }

        throw ValidationException::withMessages(['discount' => 'Invalid discount type.']);
    }

    public function validateCoupon(string $code, float $subtotal): array
    {
        $query = Coupon::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower(trim($code))])
            ->when(DB::transactionLevel() > 0, fn ($query) => $query->lockForUpdate());

        $coupon = $query->first();

        if (! $coupon) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon code does not exist.']);
        }

        if (! $coupon->is_active) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon is inactive.']);
        }

        $now = now();
        if ($coupon->starts_at && $coupon->starts_at->greaterThan($now)) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon is not active yet.']);
        }

        if ($coupon->ends_at && $coupon->ends_at->endOfDay()->lessThan($now)) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon has expired.']);
        }

        if ($coupon->min_order_amount !== null && $subtotal < (float) $coupon->min_order_amount) {
            throw ValidationException::withMessages([
                'coupon_code' => 'Minimum order amount for this coupon is '.number_format((float) $coupon->min_order_amount, 2).'.',
            ]);
        }

        if ($coupon->usage_limit !== null && $this->usedAndReservedCount($coupon) >= $coupon->usage_limit) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon usage limit has been reached.']);
        }

        if ($coupon->type === Coupon::TYPE_FIXED && (float) $coupon->value > $subtotal) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon discount cannot exceed order subtotal.']);
        }

        $discount = $this->calculateAmount($coupon->type, (float) $coupon->value, $subtotal);

        if ($discount > $subtotal) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon discount cannot exceed order subtotal.']);
        }

        return [
            'coupon' => $coupon,
            'discount_amount' => $discount,
        ];
    }

    public function incrementCouponUsage(?Coupon $coupon): void
    {
        if (! $coupon) {
            return;
        }

        DB::transaction(function () use ($coupon): void {
            $locked = Coupon::query()->lockForUpdate()->find($coupon->id);

            if (! $locked) {
                return;
            }

            if ($locked->usage_limit !== null && $locked->used_count >= $locked->usage_limit) {
                throw ValidationException::withMessages(['coupon_code' => 'Coupon usage limit has been reached.']);
            }

            $locked->increment('used_count');
        });
    }

    public function decrementCouponUsage(?Coupon $coupon): void
    {
        if (! $coupon) {
            return;
        }

        $coupon->newQuery()
            ->whereKey($coupon->id)
            ->where('used_count', '>', 0)
            ->decrement('used_count');
    }

    private function usedAndReservedCount(Coupon $coupon): int
    {
        $reserved = OnlineOrder::query()
            ->where('coupon_id', $coupon->id)
            ->whereNull('coupon_counted_at')
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->count();

        return (int) $coupon->used_count + $reserved;
    }
}
