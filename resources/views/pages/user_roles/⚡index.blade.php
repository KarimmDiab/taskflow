<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new #[Title('User Roles')] class extends Component {
    use WithPagination;

    public string $search = '';
    public array $selectedRoles = [];
    public ?int $savingUserId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('users.update'), 403);
        $this->fillSelectedRoles();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function getRolesProperty()
    {
        return Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->sortBy(fn ($role) => array_search($role->name, config('rbac.roles', []), true) === false ? PHP_INT_MAX : array_search($role->name, config('rbac.roles', []), true))
            ->values();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->fillSelectedRoles();
    }

    public function updatedPage(): void
    {
        $this->fillSelectedRoles();
    }

    public function save(int $userId): void
    {
        $this->savingUserId = $userId;

        $this->validate([
            "selectedRoles.{$userId}" => ['required', 'array', 'min:1'],
            "selectedRoles.{$userId}.*" => ['string', 'exists:roles,name'],
        ]);

        $user = User::findOrFail($userId);
        $user->syncRoles($this->selectedRoles[$userId]);

        session()->flash('success', "Roles for user '{$user->name}' have been updated successfully.");

        $this->savingUserId = null;
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
        $this->fillSelectedRoles();
    }

    private function fillSelectedRoles(): void
    {
        foreach ($this->users as $user) {
            $this->selectedRoles[$user->id] = $user->roles->pluck('name')->all();
        }
    }
};
?>

<div dir="rtl" class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950">
    <!-- Animated background blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300/30 dark:bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300/30 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Flash Message Toast -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
                class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md">
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl shadow-xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                        <button @click="show = false" class="mr-auto text-emerald-600 dark:text-emerald-400 hover:text-emerald-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full shadow-md"></div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">User Roles</h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 pr-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Assign one or more roles to users. Changes use syncRoles().
                </p>
            </div>

            <!-- Search with Clear Button -->
            <div class="relative">
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..."
                    class="w-full sm:w-80 text-sm border-2 border-gray-200 dark:border-gray-700 rounded-xl pr-10 pl-10 py-2.5 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200">
                @if($search)
                    <button type="button" wire:click="clearSearch" class="absolute inset-y-0 left-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30 border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 backdrop-blur-sm">
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Roles</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assign Roles</th>
                            <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                        @forelse ($this->users as $user)
                            <tr wire:key="user-{{ $user->id }}" class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-150">
                                <!-- User Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Current Roles (as pills) -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($user->roles as $role)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full">No roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <!-- Role Assignment (Checkbox Pill Group) -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($this->roles as $role)
                                            <label class="relative inline-flex items-center cursor-pointer group/role">
                                                <input type="checkbox"
                                                    wire:model.live="selectedRoles.{{ $user->id }}"
                                                    value="{{ $role->name }}"
                                                    class="sr-only peer">
                                                <div class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200
                                                    peer-checked:bg-gradient-to-r peer-checked:from-blue-600 peer-checked:to-indigo-600 peer-checked:text-white peer-checked:shadow-md
                                                    bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                                    hover:bg-gray-200 dark:hover:bg-gray-700
                                                    peer-checked:hover:from-blue-700 peer-checked:hover:to-indigo-700
                                                    border border-gray-200 dark:border-gray-700 peer-checked:border-transparent">
                                                    {{ $role->name }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error("selectedRoles.{$user->id}")
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </td>
                                <!-- Save Button with Loading -->
                                <td class="px-6 py-4 text-end">
                                    <button type="button"
                                        wire:click="save({{ $user->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="save({{ $user->id }})"
                                        class="relative inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="save({{ $user->id }})">Save</span>
                                        <span wire:loading wire:target="save({{ $user->id }})" class="flex items-center gap-1">
                                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Saving
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">No users found</span>
                                        <span class="text-xs">Try adjusting your search or clear the filter.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Modern Styling -->
            @if ($this->users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/20">
                    {{ $this->users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
