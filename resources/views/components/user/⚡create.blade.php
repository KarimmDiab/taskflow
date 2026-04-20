<?php

use Livewire\Component;
use App\Livewire\Forms\CreateUser;

new class extends Component {
    public CreateUser $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-user')->close();

        session()->flash('success', 'تم اضافة المستخدم بنجاح');

        $this->redirectRoute('users', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add-user" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة اسم مستخدم جديد</flux:heading>
                <flux:text class="mt-2">إنشاء اسم مستخدم جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم المستخدم" placeholder="اسم المستخدم" wire:model="form.name" />

            <flux:input label="الايميل" type="email" placeholder="الايميل" wire:model="form.email" />

            <div x-data="{ show: false }" class="relative">

                <flux:input label="كلمة المرور" placeholder="كلمة المرور" wire:model="form.password" 
                    x-bind:type="show ? 'text' : 'password'" />

                <button type="button" class="absolute left-3 top-9 text-gray-500 hover:text-gray-700"
                    @click="show = !show">
                    <span x-show="!show">👁️</span>
                    <span x-show="show">🙈</span>
                </button>

            </div>
            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة المستخدم
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
