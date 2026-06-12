<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

new #[Title('Add Role')] class extends Component {
    public string $name = '';
    public ?int $editingRoleId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('users.update'), 403);
    }

    public function getRolesProperty()
    {
        return Role::query()
            ->where('guard_name', 'web')
            ->withCount('permissions')
            ->orderBy('name')
            ->get()
            ->sortBy(fn ($role) => array_search($role->name, config('rbac.roles', []), true) === false ? PHP_INT_MAX : array_search($role->name, config('rbac.roles', []), true))
            ->values();
    }

    public function isSystemRole(string $roleName): bool
    {
        $systemRoles = config('rbac.roles', []);
        return in_array($roleName, $systemRoles, true);
    }

    public function save(): void
    {
        if ($this->editingRoleId) {
            $this->update();
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:125', 'regex:/^[a-z0-9_\\-]+$/', 'unique:roles,name'],
        ], [
            'name.regex' => 'Role name may only contain lowercase letters, numbers, underscores, and hyphens.',
        ]);

        Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->reset('name');
        session()->flash('success', 'Role created successfully. You can now assign permissions to it.');
    }

    public function edit(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        abort_if($this->isSystemRole($role->name), 403, 'Core roles cannot be renamed.');

        $this->editingRoleId = $role->id;
        $this->name = $role->name;
    }

    public function update(): void
    {
        $role = Role::findOrFail($this->editingRoleId);

        abort_if($this->isSystemRole($role->name), 403, 'Core roles cannot be renamed.');

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:125',
                'regex:/^[a-z0-9_\\-]+$/',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
        ], [
            'name.regex' => 'Role name may only contain lowercase letters, numbers, underscores, and hyphens.',
        ]);

        $role->update(['name' => $validated['name']]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->cancelEdit();
        session()->flash('success', 'Role updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->reset('name', 'editingRoleId');
        $this->resetValidation();
    }

    public function delete(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        abort_if($this->isSystemRole($role->name), 403, 'Core roles cannot be deleted.');

        $assignedUsers = DB::table(config('permission.table_names.model_has_roles'))
            ->where('role_id', $role->id)
            ->where('model_type', App\Models\User::class)
            ->count();

        if ($assignedUsers > 0) {
            session()->flash('error', 'Role cannot be deleted while assigned to users.');
            return;
        }

        $role->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($this->editingRoleId === $roleId) {
            $this->cancelEdit();
        }

        session()->flash('success', 'Role deleted successfully.');
    }
};
?>

<div dir="rtl"
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950">
    <!-- Animated background blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300/30 dark:bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300/30 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Enhanced Flash Message -->
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

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.300
                class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md">
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl shadow-xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        <button @click="show = false" class="mr-auto text-red-600 dark:text-red-400 hover:text-red-800">
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
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">{{ $editingRoleId ? 'Edit Role' : 'Add Role' }}</h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 pr-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Create a role, then assign its permissions from Roles Permissions.
                </p>
            </div>
            <div class="hidden md:block text-xs text-gray-400 dark:text-gray-600 bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm px-4 py-2 rounded-full">
                {{ $this->roles->count() }} total roles
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-[480px_1fr] gap-8">
            <!-- Form Card with Glassmorphism -->
            <form wire:submit="save" class="group">
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10">
                    <div class="relative px-6 py-5 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-blue-50/30 to-indigo-50/30 dark:from-blue-900/10 dark:to-indigo-900/10">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-800 dark:text-white">{{ $editingRoleId ? 'Update Role' : 'Create New Role' }}</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Use lowercase with underscores or hyphens</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Input Group with Icon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                Role name
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <input type="text" wire:model.live="name" dir="ltr"
                                    placeholder="e.g., branch_manager"
                                    class="w-full text-sm border-2 border-gray-200 dark:border-gray-700 rounded-xl ps-3 pe-10 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200">
                            </div>
                            @error('name')
                                <div class="flex items-center gap-2 mt-2 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg px-3 py-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Example hint -->
                        <div class="bg-blue-50/50 dark:bg-blue-900/20 rounded-xl p-3 border border-blue-100 dark:border-blue-800/30">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-xs text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Naming convention:</span>
                                    <code class="bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-blue-600 dark:text-blue-400 mx-1">admin_panel</code> or
                                    <code class="bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-blue-600 dark:text-blue-400 mx-1">content_editor</code>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button with Loading State -->
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="relative inline-flex items-center justify-center gap-2 w-full px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 hover:scale-[1.02] transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">{{ $editingRoleId ? 'Update Role' : 'Create Role' }}</span>
                            <span wire:loading wire:target="save">{{ $editingRoleId ? 'Updating...' : 'Creating...' }}</span>
                        </button>

                        @if ($editingRoleId)
                            <button type="button" wire:click="cancelEdit"
                                class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                Cancel Edit
                            </button>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Roles Table Card -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden transition-all duration-300">
                <div class="px-6 py-5 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-gray-50/30 to-white/30 dark:from-gray-800/20 dark:to-gray-900/20">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Existing Roles
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Permissions are managed by role</p>
                        </div>
                        <div class="text-xs font-medium px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-full text-gray-600 dark:text-gray-300">
                            {{ $this->roles->count() }} role{{ $this->roles->count() != 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-800/30 border-b border-gray-200/50 dark:border-gray-700/50">
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Permissions</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @forelse ($this->roles as $role)
                                <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-all duration-150 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $role->name }}</span>
                                            @if($this->isSystemRole($role->name))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                    <svg class="w-2.5 h-2.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Core
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $role->permissions_count }}</span>
                                            <span class="text-gray-400 text-xs">perm{{ $role->permissions_count != 1 ? 's' : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $role->created_at?->format('Y-m-d') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-2">
                                            @if($this->isSystemRole($role->name))
                                                <span class="text-xs text-gray-400 dark:text-gray-500">Locked</span>
                                            @else
                                                <button type="button" wire:click="edit({{ $role->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 dark:hover:border-blue-800 hover:text-blue-700 transition-all duration-150">
                                                    Edit
                                                </button>

                                                <button type="button" wire:click="delete({{ $role->id }})"
                                                    wire:confirm="Are you sure you want to delete role '{{ $role->name }}'?"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 transition-all duration-150">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2 text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span class="text-sm">No roles found</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($this->roles->isNotEmpty())
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/20 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-between">
                        <span>System roles are marked with <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 dark:bg-amber-900/40 text-[10px]">Core</span> badge</span>
                        <span>Total permissions assigned: {{ $this->roles->sum('permissions_count') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
