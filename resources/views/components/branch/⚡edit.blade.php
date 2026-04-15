<?php

use Livewire\Component;
use App\Livewire\Forms\CreateBranch;
use Livewire\Attributes\On;
use App\Models\Branches;

new class extends Component
{
    public CreateBranch $form;

    #[On('editBranch')]
    public function editPost($id)
    {
        //return dd($id);
        $branch = Branches::findOrFail($id);
        $this->form->setBranch($branch);
        Flux::modal('edit-branch')->show();
    }

    public function updatebranch()
    {
        $this->form->update();
        Flux::modal('edit-branch')->close();
        session()->flash('warning', 'تم تحديث بيانات الفرع بنجاح');
        $this->redirectRoute('branches', navigate:true);
    }

        public function confirmDelete($id)
    {
        //return dd($id);
        $branch = Branches::findOrFail($id);
        $this->form->setBranch($branch);
        Flux::modal('edit-branch')->show();
    }


    public function deleteBranch()
    {
        $this->form->branch->delete();
        Flux::modal('delete-branch')->close();
        session()->flash('success', 'تم حذف بيانات الفرع بنجاح');
        $this->redirectRoute('branches', navigate:true);
    }


};
?>

<div>


    <flux:modal name="edit-branch" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updatebranch">
            <div>
                <flux:heading size="lg">edit branch</flux:heading>
                <flux:text class="mt-2">edit details of branch</flux:text>
            </div>

            <flux:input label="Branch Name" placeholder="branch name" wire:model="form.branch_name" wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="Branch Address" type="text" placeholder="branch address" wire:model="form.branch_address" wire:dirty.class="rind-1 ring-yellow-400"/>

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
