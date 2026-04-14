<?php

use Livewire\Component;
use App\Livewire\Forms\CreateBranch;

new class extends Component {
    public CreateBranch $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-branch')->close();

        session()->flash('success', 'تم اضافة الفرع بنجاح');

        $this->redirectRoute('branches', navigate:true);
    }

};
?>

<div>


    <flux:modal name="add-branch" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">add branch</flux:heading>
                <flux:text class="mt-2">create a new branch with all details</flux:text>
            </div>

            <flux:input label="Branch Name" placeholder="branch name" wire:model="form.branch_name" />

            <flux:input label="Branch Address" type="text" placeholder="branch address" wire:model="form.branch_address" />

            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">Cancel</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">Add Branch
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
