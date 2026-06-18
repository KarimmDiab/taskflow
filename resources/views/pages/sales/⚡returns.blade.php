<?php

use App\Models\SalesReturn;
use App\Services\SalesReturnService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Sales Returns')] class extends Component {
    use WithPagination;

    public string $status = '';
    public string $search = '';
    public ?int $selectedReturnId = null;
    public string $approvalNote = '';

    public function getReturnsProperty()
    {
        return SalesReturn::query()
            ->with(['invoice.customer', 'invoice.branch', 'createdBy', 'approvedBy'])
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->search, fn ($query) => $query->where(function ($inner) {
                $inner->where('return_number', 'like', "%{$this->search}%")
                    ->orWhereHas('invoice', fn ($invoice) => $invoice->where('invoice_number', 'like', "%{$this->search}%"));
            }))
            ->latest()
            ->paginate(12);
    }

    public function approve(int $id, SalesReturnService $service): void
    {
        abort_unless(auth()->user()?->can('sales.return.approve'), 403);
        $service->approve(SalesReturn::findOrFail($id), $this->approvalNote ?: null);
        $this->approvalNote = '';
        session()->flash('success', 'Return approved and inventory restored.');
    }

    public function reject(int $id, SalesReturnService $service): void
    {
        abort_unless(auth()->user()?->can('sales.return.approve'), 403);
        $this->validate(['approvalNote' => ['required', 'string', 'min:3']]);
        $service->reject(SalesReturn::findOrFail($id), $this->approvalNote);
        $this->approvalNote = '';
        session()->flash('success', 'Return rejected.');
    }

    public function complete(int $id, SalesReturnService $service): void
    {
        abort_unless(auth()->user()?->can('sales.return.approve'), 403);
        $service->complete(SalesReturn::findOrFail($id));
        session()->flash('success', 'Return marked as completed.');
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
        @endif
        <div>
            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Returns Management</p>
            <h1 class="text-2xl font-bold tracking-tight">Sales Returns</h1>
        </div>
        <div class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:grid-cols-3">
            <input wire:model.live.debounce.350ms="search" placeholder="Search return or invoice" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
            <select wire:model.live="status" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="completed">Completed</option>
            </select>
            <input wire:model="approvalNote" placeholder="Approval or rejection note" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
        </div>
        @error('approvalNote') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Return No', 'Invoice', 'Customer', 'Branch', 'Type', 'Refund', 'Amount', 'Status', 'Created By', 'Actions'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->returns as $return)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $return->return_number }}</td>
                                <td class="px-4 py-3"><a href="{{ route('sales.show', $return->invoice) }}" wire:navigate class="text-blue-600">{{ $return->invoice?->invoice_number }}</a></td>
                                <td class="px-4 py-3">{{ $return->invoice?->customer?->customer_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $return->invoice?->branch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($return->return_type) }}</td>
                                <td class="px-4 py-3">{{ str_replace('_', ' ', ucfirst($return->refund_method)) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $return->return_amount, 2) }}</td>
                                <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold dark:bg-zinc-800">{{ ucfirst($return->status) }}</span></td>
                                <td class="px-4 py-3">{{ $return->createdBy?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @can('sales.print')
                                            <a href="{{ route('sales.returns.print', $return) }}" target="_blank" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold dark:border-zinc-700">Print</a>
                                        @endcan
                                        @can('sales.return.approve')
                                            @if ($return->status === 'pending')
                                                <button wire:click="approve({{ $return->id }})" wire:confirm="Approve this return and restore inventory?" class="rounded-md bg-emerald-600 px-2 py-1 text-xs font-semibold text-white">Approve</button>
                                                <button wire:click="reject({{ $return->id }})" wire:confirm="Reject this return?" class="rounded-md bg-red-600 px-2 py-1 text-xs font-semibold text-white">Reject</button>
                                            @elseif ($return->status === 'approved')
                                                <button wire:click="complete({{ $return->id }})" class="rounded-md bg-blue-600 px-2 py-1 text-xs font-semibold text-white">Complete</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="px-4 py-12 text-center text-slate-500">No returns found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->returns->links() }}</div>
        </div>
    </div>
</div>
