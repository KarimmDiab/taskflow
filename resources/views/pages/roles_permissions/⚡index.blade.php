<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new #[Title('Roles Permissions')] class extends Component {
    public string $selectedRole = 'manager';
    public array $selectedPermissions = [];
    public bool $saving = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('users.update'), 403);
        $this->loadRolePermissions();
    }

    public function updatedSelectedRole(): void
    {
        $this->loadRolePermissions();
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

    public function getModulesProperty(): array
    {
        return config('rbac.modules', []);
    }

    public function getActionsProperty(): array
    {
        return config('rbac.actions', []);
    }

    /**
     * Get all possible permission names based on modules and actions
     */
    public function getAllPermissionsList(): array
    {
        $permissions = [];
        foreach ($this->modules as $module => $label) {
            foreach ($this->actions as $action => $actionLabel) {
                $permissions[] = "{$module}.{$action}";
            }
        }
        return $permissions;
    }

    /**
     * Select all permissions for the current role
     */
    public function selectAllPermissions(): void
    {
        $this->selectedPermissions = $this->getAllPermissionsList();
    }

    /**
     * Clear all selected permissions
     */
    public function clearPermissions(): void
    {
        $this->selectedPermissions = [];
    }

    public function save(): void
    {
        $this->saving = true;

        $allowedPermissions = Permission::query()->pluck('name')->all();

        $validated = $this->validate([
            'selectedRole' => ['required', 'string', 'exists:roles,name'],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['string', 'in:'.implode(',', $allowedPermissions)],
        ]);

        $role = Role::findByName($validated['selectedRole']);
        $role->syncPermissions($validated['selectedPermissions']);

        session()->flash('success', "Permissions for role '{$role->name}' have been updated successfully.");
        $this->saving = false;
    }

    private function loadRolePermissions(): void
    {
        $role = Role::findByName($this->selectedRole);
        $this->selectedPermissions = $role->permissions()->pluck('name')->all();
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
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">Roles Permissions</h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 pr-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Manage module permissions by role. Users inherit access through assigned roles only.
                </p>
            </div>
            <div class="hidden md:block text-xs text-gray-400 dark:text-gray-600 bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm px-4 py-2 rounded-full">
                {{ $this->roles->count() }} roles available
            </div>
        </div>

        <!-- Role Selector - Modern Pill Buttons -->
        <div class="flex flex-wrap gap-2 pb-2 overflow-x-auto scrollbar-thin">
            @foreach ($this->roles as $role)
                <button type="button" wire:click="$set('selectedRole', '{{ $role->name }}')"
                    class="relative px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ $selectedRole === $role->name ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 scale-105' : 'bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-blue-300 hover:shadow-md' }}">
                    {{ $role->name }}
                    @if($selectedRole === $role->name)
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Main Form Card -->
        <form wire:submit="save" class="group">
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden transition-all duration-300">
                <!-- Card Header with Toolbar -->
                <div class="px-6 py-5 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-blue-50/30 to-indigo-50/30 dark:from-blue-900/10 dark:to-indigo-900/10">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-800 dark:text-white">
                                        Permissions for <span class="text-blue-600 dark:text-blue-400">{{ $selectedRole }}</span>
                                    </h2>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Use syncPermissions() – changes are applied immediately</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Selected Count Badge -->
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-xl shadow-sm text-sm">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ count($selectedPermissions) }}</span>
                                <span class="text-gray-400">selected</span>
                            </div>
                            <!-- Bulk Actions -->
                            <button type="button" wire:click="selectAllPermissions" class="p-2 text-gray-500 hover:text-blue-600 transition rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Select All">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </button>
                            <button type="button" wire:click="clearPermissions" class="p-2 text-gray-500 hover:text-red-600 transition rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Clear All">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            <!-- Save Button with Loading -->
                            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 hover:scale-[1.02] transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="save">Save Permissions</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Validation Errors -->
                @error('selectedPermissions.*')
                    <div class="mx-6 mt-4 rounded-xl border border-red-200 bg-red-50/80 backdrop-blur-sm px-4 py-3 text-sm text-red-700 dark:border-red-900/30 dark:bg-red-900/20 dark:text-red-300">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Permissions Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-800/30 border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 backdrop-blur-sm">
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Module
                                </th>
                                @foreach ($this->actions as $actionLabel)
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span>{{ $actionLabel }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach ($this->modules as $module => $label)
                                <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-150 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $label }}</div>
                                                <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $module }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach ($this->actions as $action => $actionLabel)
                                        @php($permission = "{$module}.{$action}")
                                        <td class="px-6 py-4 text-center">
                                            <label class="inline-flex items-center justify-center cursor-pointer">
                                                <input type="checkbox"
                                                    wire:model.live="selectedPermissions"
                                                    value="{{ $permission }}"
                                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-800 transition-all duration-150">
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary -->
                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/20 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ count($this->modules) }} modules · {{ count($this->actions) }} actions each · {{ count($this->getAllPermissionsList()) }} total permissions</span>
                    </div>
                    <div>
                        <span class="font-medium">{{ $selectedRole }}</span> currently has <span class="font-medium text-blue-600 dark:text-blue-400">{{ count($selectedPermissions) }}</span> assigned permissions
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
