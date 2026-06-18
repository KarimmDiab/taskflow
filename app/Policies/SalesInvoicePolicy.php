<?php

namespace App\Policies;

use App\Models\SalesInvoice;
use App\Models\User;

class SalesInvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sales.view');
    }

    public function view(User $user, SalesInvoice $salesInvoice): bool
    {
        return $user->can('sales.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sales.create');
    }

    public function update(User $user, SalesInvoice $salesInvoice): bool
    {
        return $salesInvoice->status !== 'cancelled' && ($user->can('sales.edit') || $user->can('sales.update'));
    }

    public function delete(User $user, SalesInvoice $salesInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SalesInvoice $salesInvoice): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SalesInvoice $salesInvoice): bool
    {
        return false;
    }

    public function print(User $user, SalesInvoice $salesInvoice): bool
    {
        return $user->can('sales.print');
    }

    public function createReturn(User $user, SalesInvoice $salesInvoice): bool
    {
        return $salesInvoice->status !== 'cancelled' && $user->can('sales.return.create');
    }
}
