<?php

use Livewire\Component;
use App\Livewire\Forms\CreateUser;
use Livewire\Attributes\On;
use App\Models\User;

new class extends Component {
    public CreateUser $form;

    #[On('editUser')]
    public function editUser($id)
    {
        //return dd($id);
        $user = User::findOrFail($id);
        $this->form->setUser($user);
        Flux::modal('edit-user')->show();
    }

    public function updateUser()
    {
        $this->form->update();
        Flux::modal('edit-user')->close();
        session()->flash('warning', 'تم تحديث بيانات المستخدم بنجاح');
        $this->redirectRoute('users', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $user = User::findOrFail($id);
        $this->form->setUser($user);
        Flux::modal('edit-user')->show();
    }

    public function deleteBranch()
    {
        $this->form->user->delete();
        Flux::modal('delete-user')->close();
        session()->flash('success', 'تم حذف بيانات المستخدم بنجاح');
        $this->redirectRoute('users', navigate: true);
    }
};
?>

<div>


    <flux:modal name="edit-user" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updateUser">
            <div>
                <flux:heading size="lg">تعديل بيانات المستخدم</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل بيانات المستخدم </flux:text>
            </div>

            <flux:input label="اسم يالمستخدم" placeholder="اسم المستخدم" wire:model="form.name"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="الايميل" type="email" placeholder="الايميل" wire:model="form.email"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <div x-data="{ show: false }" class="relative">

                <flux:input label="كلمة المرور" x-bind:type="show ? 'text' : 'password'" placeholder="كلمة المرور" wire:model="form.password"
                    wire:dirty.class="rind-1 ring-yellow-400"/>

                <button type="button" class="absolute left-3 top-9 text-gray-500 hover:text-gray-700"
                    @click="show = !show">
                    <span x-show="!show">👁️</span>
                    <span x-show="show">🙈</span>
                </button>

            </div>
            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">Cancel</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">update Branch
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
