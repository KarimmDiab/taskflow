<?php

use App\Models\Coupon;
use App\Models\SalesInvoice;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

new #[Title('Coupons')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $type = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 10;
    public bool $showForm = false;
    public bool $showDetails = false;
    public ?int $selectedCouponId = null;
    public array $form = [
        'code' => '',
        'name' => '',
        'type' => 'percentage',
        'value' => '',
        'min_order_amount' => '',
        'starts_at' => '',
        'ends_at' => '',
        'usage_limit' => '',
        'is_active' => true,
        'notes' => '',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('coupons.view'), 403);
    }

    public function getStatsProperty(): array
    {
        return [
            'active' => Coupon::query()->active()->where(fn ($query) => $query->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))->count(),
            'expired' => Coupon::query()->whereNotNull('ends_at')->whereDate('ends_at', '<', today())->count(),
            'usage' => (int) Coupon::query()->sum('used_count'),
            'discounts' => (float) SalesInvoice::query()->whereNotNull('coupon_id')->where('status', '!=', 'cancelled')->sum('discount_amount'),
        ];
    }

    public function getCouponsProperty()
    {
        return Coupon::query()
            ->with('creator')
            ->when($this->search, fn ($query) => $query->where(fn ($inner) => $inner
                ->where('code', 'like', '%'.trim($this->search).'%')
                ->orWhere('name', 'like', '%'.trim($this->search).'%')))
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->when($this->status === 'active', fn ($query) => $query->where('is_active', true)->where(fn ($inner) => $inner->whereNull('ends_at')->orWhereDate('ends_at', '>=', today())))
            ->when($this->status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($this->status === 'expired', fn ($query) => $query->whereNotNull('ends_at')->whereDate('ends_at', '<', today()))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate($this->perPage);
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'type', 'dateFrom', 'dateTo', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function create(): void
    {
        abort_unless(auth()->user()?->can('coupons.create'), 403);
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $couponId): void
    {
        abort_unless(auth()->user()?->can('coupons.edit'), 403);
        $coupon = Coupon::query()->findOrFail($couponId);
        $this->selectedCouponId = $coupon->id;
        $this->form = [
            'code' => $coupon->code,
            'name' => $coupon->name,
            'type' => $coupon->type,
            'value' => (string) $coupon->value,
            'min_order_amount' => (string) ($coupon->min_order_amount ?? ''),
            'starts_at' => $coupon->starts_at?->format('Y-m-d\TH:i') ?? '',
            'ends_at' => $coupon->ends_at?->format('Y-m-d\TH:i') ?? '',
            'usage_limit' => (string) ($coupon->usage_limit ?? ''),
            'is_active' => (bool) $coupon->is_active,
            'notes' => (string) $coupon->notes,
        ];
        $this->showForm = true;
    }

    public function showCoupon(int $couponId): void
    {
        $this->selectedCouponId = $couponId;
        $this->showDetails = true;
    }

    public function getSelectedCouponProperty(): ?Coupon
    {
        return $this->selectedCouponId ? Coupon::query()->with('creator')->find($this->selectedCouponId) : null;
    }

    public function save(): void
    {
        $isEditing = $this->selectedCouponId !== null;
        abort_unless(auth()->user()?->can($isEditing ? 'coupons.edit' : 'coupons.create'), 403);
        $this->form['code'] = strtoupper(trim((string) $this->form['code']));

        $validated = $this->validate([
            'form.code' => ['required', 'string', 'max:255', Rule::unique('coupons', 'code')->ignore($this->selectedCouponId)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.type' => ['required', 'in:percentage,fixed'],
            'form.value' => ['required', 'numeric', 'min:0.01'],
            'form.min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'form.starts_at' => ['nullable', 'date'],
            'form.ends_at' => ['nullable', 'date', 'after_or_equal:form.starts_at'],
            'form.usage_limit' => ['nullable', 'integer', 'min:1'],
            'form.is_active' => ['boolean'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['form']['type'] === 'percentage' && (float) $validated['form']['value'] > 100) {
            $this->addError('form.value', 'Percentage discount cannot exceed 100%.');
            return;
        }

        $payload = $validated['form'];
        $payload['code'] = strtoupper(trim($payload['code']));
        $payload['min_order_amount'] = $payload['min_order_amount'] === '' ? null : $payload['min_order_amount'];
        $payload['usage_limit'] = $payload['usage_limit'] === '' ? null : $payload['usage_limit'];
        $payload['notes'] = $payload['notes'] === '' ? null : $payload['notes'];

        if ($isEditing) {
            Coupon::query()->findOrFail($this->selectedCouponId)->update($payload);
            session()->flash('success', 'Coupon updated.');
        } else {
            $payload['created_by'] = auth()->id();
            Coupon::query()->create($payload);
            session()->flash('success', 'Coupon created.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function toggleStatus(int $couponId): void
    {
        $coupon = Coupon::query()->findOrFail($couponId);
        abort_unless(auth()->user()?->can($coupon->is_active ? 'coupons.deactivate' : 'coupons.activate'), 403);

        $coupon->update(['is_active' => ! $coupon->is_active]);
        session()->flash('success', $coupon->fresh()->is_active ? 'Coupon activated.' : 'Coupon deactivated.');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'type', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->selectedCouponId = null;
        $this->resetValidation();
        $this->form = [
            'code' => '',
            'name' => '',
            'type' => 'percentage',
            'value' => '',
            'min_order_amount' => '',
            'starts_at' => '',
            'ends_at' => '',
            'usage_limit' => '',
            'is_active' => true,
            'notes' => '',
        ];
    }
};
?>

<div dir="ltr" class="min-h-screen bg-slate-50 px-4 py-8 text-slate-900 dark:bg-slate-950 dark:text-white sm:px-6 lg:px-8">
    <x-flash-message />

    <div class="mx-auto max-w-[1700px] space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">Discounts</p>
                    <h1 class="mt-1 text-3xl font-bold tracking-tight">Coupons Dashboard</h1>
                    <p class="mt-2 text-sm text-slate-500">Manage online coupons and track discount impact across invoices and reports.</p>
                </div>
                @can('coupons.create')
                    <button wire:click="create" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-600 dark:bg-white dark:text-slate-900">
                        Create Coupon
                    </button>
                @endcan
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['Active Coupons', $this->stats['active'], 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300'],
                    ['Expired Coupons', $this->stats['expired'], 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300'],
                    ['Total Coupon Usage', $this->stats['usage'], 'bg-sky-50 text-sky-700 dark:bg-sky-950/30 dark:text-sky-300'],
                    ['Total Discount Given', number_format($this->stats['discounts'], 2), 'bg-violet-50 text-violet-700 dark:bg-violet-950/30 dark:text-violet-300'],
                ] as [$label, $value, $classes])
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                        <p class="mt-3 rounded-lg px-3 py-2 text-2xl font-bold {{ $classes }}">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="grid gap-4 border-b border-slate-100 p-5 dark:border-slate-800 md:grid-cols-2 xl:grid-cols-6">
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Coupon code or name" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950 xl:col-span-2">
                <select wire:model.live="status" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950">
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="expired">Expired</option>
                </select>
                <select wire:model.live="type" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950">
                    <option value="">All types</option>
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                </select>
                <input wire:model.live="dateFrom" type="date" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950">
                <input wire:model.live="dateTo" type="date" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950">
                <div class="flex gap-2 xl:col-span-6">
                    <select wire:model.live="perPage" class="rounded-xl border-slate-200 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-950">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                    <button wire:click="resetFilters" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">Reset</button>
                </div>
            </div>

            <div class="relative overflow-x-auto">
                <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/75 backdrop-blur-sm dark:bg-slate-950/75">
                    <div class="rounded-xl border bg-white px-4 py-3 text-sm font-semibold shadow-lg dark:border-slate-800 dark:bg-slate-900">Loading coupons...</div>
                </div>
                <table class="w-full min-w-[1300px] text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-950/60">
                        <tr>
                            @foreach (['Code', 'Name', 'Type', 'Value', 'Min Order', 'Usage Limit', 'Used Count', 'Start Date', 'End Date', 'Status', 'Actions'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($this->coupons as $coupon)
                            @php
                                $statusClasses = $coupon->status_label === 'Active'
                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900'
                                    : 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700';
                            @endphp
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-950/10">
                                <td class="px-4 py-4 font-mono font-bold">{{ $coupon->code }}</td>
                                <td class="px-4 py-4 font-medium">{{ $coupon->name }}</td>
                                <td class="px-4 py-4"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ ucfirst($coupon->type) }}</span></td>
                                <td class="px-4 py-4 font-semibold">{{ $coupon->type === 'percentage' ? number_format((float) $coupon->value, 2).'%' : number_format((float) $coupon->value, 2).' EGP' }}</td>
                                <td class="px-4 py-4">{{ $coupon->min_order_amount !== null ? number_format((float) $coupon->min_order_amount, 2) : '-' }}</td>
                                <td class="px-4 py-4">{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                                <td class="px-4 py-4 font-semibold">{{ number_format($coupon->used_count) }}</td>
                                <td class="px-4 py-4">{{ $coupon->starts_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $coupon->ends_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">{{ $coupon->status_label }}</span></td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="showCoupon({{ $coupon->id }})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">View</button>
                                        @can('coupons.edit')
                                            <button wire:click="edit({{ $coupon->id }})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">Edit</button>
                                        @endcan
                                        @if ($coupon->is_active)
                                            @can('coupons.deactivate')
                                                <button wire:click="toggleStatus({{ $coupon->id }})" wire:confirm="Deactivate this coupon?" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 dark:bg-rose-950/30 dark:text-rose-300">Deactivate</button>
                                            @endcan
                                        @else
                                            @can('coupons.activate')
                                                <button wire:click="toggleStatus({{ $coupon->id }})" wire:confirm="Activate this coupon?" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-300">Activate</button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-20 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <h3 class="text-lg font-bold">No coupons found</h3>
                                        <p class="mt-1 text-sm text-slate-500">Create your first coupon or adjust the filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->coupons->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $this->coupons->links() }}</div>
            @endif
        </section>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">{{ $selectedCouponId ? 'Edit Coupon' : 'Create Coupon' }}</h2>
                    <button wire:click="$set('showForm', false)" class="rounded-lg border px-3 py-1 text-sm dark:border-slate-700">Close</button>
                </div>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Code</label><input wire:model="form.code" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950">@error('form.code') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror</div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Name</label><input wire:model="form.name" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950">@error('form.name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror</div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Type</label><select wire:model.live="form.type" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950"><option value="percentage">Percentage</option><option value="fixed">Fixed amount</option></select></div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Value</label><input wire:model="form.value" type="number" min="0.01" step="0.01" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950">@error('form.value') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror</div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Min Order</label><input wire:model="form.min_order_amount" type="number" min="0" step="0.01" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950"></div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Usage Limit</label><input wire:model="form.usage_limit" type="number" min="1" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950"></div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Starts At</label><input wire:model="form.starts_at" type="datetime-local" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950"></div>
                    <div><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Ends At</label><input wire:model="form.ends_at" type="datetime-local" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950">@error('form.ends_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror</div>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold dark:border-slate-700"><input wire:model="form.is_active" type="checkbox" class="rounded border-slate-300"> Active</label>
                    <div class="md:col-span-2"><label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label><textarea wire:model="form.notes" rows="3" class="w-full rounded-xl border-slate-200 text-sm dark:border-slate-700 dark:bg-slate-950"></textarea></div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="$set('showForm', false)" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-slate-700">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Coupon</button>
                </div>
            </div>
        </div>
    @endif

    @if ($showDetails && $this->selectedCoupon)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
            <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">{{ $this->selectedCoupon->code }}</h2>
                    <button wire:click="$set('showDetails', false)" class="rounded-lg border px-3 py-1 text-sm dark:border-slate-700">Close</button>
                </div>
                <dl class="mt-5 grid gap-3 text-sm sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-950"><dt class="text-slate-500">Name</dt><dd class="font-semibold">{{ $this->selectedCoupon->name }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-950"><dt class="text-slate-500">Status</dt><dd class="font-semibold">{{ $this->selectedCoupon->status_label }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-950"><dt class="text-slate-500">Type</dt><dd class="font-semibold">{{ ucfirst($this->selectedCoupon->type) }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-950"><dt class="text-slate-500">Usage</dt><dd class="font-semibold">{{ $this->selectedCoupon->used_count }} / {{ $this->selectedCoupon->usage_limit ?? 'Unlimited' }}</dd></div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-950 sm:col-span-2"><dt class="text-slate-500">Notes</dt><dd>{{ $this->selectedCoupon->notes ?: '-' }}</dd></div>
                </dl>
            </div>
        </div>
    @endif
</div>
