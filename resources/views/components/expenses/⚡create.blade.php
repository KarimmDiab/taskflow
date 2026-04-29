<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\ExpensesForm;
use App\Models\ExpensesDetail;
use App\Models\ExpensesItem;
use Illuminate\Database\Eloquent\Builder;
use Flux\Flux;

new class extends Component {
    use WithPagination;

    public ExpensesForm $form;

    public string $search = '';
    public int $perPage = 10;

    public function getExpensesDetailsProperty()
    {
        return ExpensesDetail::query()
            ->with(['expensesItem', 'user'])
            ->when($this->search, function (Builder $q) {
                $q->where(function (Builder $query) {
                    $query
                        ->where('expenses_note', 'like', "%{$this->search}%")
                        ->orWhereHas('expensesItem', function (Builder $q) {
                            $q->where('expenses_name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('user', function (Builder $q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function getExpensesItemsProperty()
    {
        return ExpensesItem::select('id', 'expenses_name')->get();
    }

    public function save()
    {
        $this->form->store();

        Flux::modal('add-expensesDetails')->close();

        session()->flash('success', 'تم اضافة مصروف جديد بنجاح');

        $this->redirectRoute('expenses', navigate: true);
    }
};
?>

<div>

    <flux:modal name="add-expensesDetails" class="md:w-150">

        <form class="space-y-6" wire:submit.prevent="save">

            <!-- 🔹 Header -->
            <div>
                <flux:heading size="lg">اضافة مصروف جديد</flux:heading>
                <flux:text class="mt-2">مصروف جديد مع جميع التفاصيل</flux:text>
            </div>

            <!-- 🔥 Select (Search شغال + مربوط) -->
            <flux:select searchable placeholder="اختر بند المصروف" wire:model="form.expenses_item_id">
                @foreach ($this->expensesItems as $item)
                    <flux:select.option value="{{ $item->id }}"> {{ $item->expenses_name }} </flux:select.option>
                @endforeach
            </flux:select>

            <!-- 🔹 التكلفة -->
            <flux:input label="التكلفة" type="number" placeholder="ادخل التكلفة" wire:model="form.expenses_cost" />

            <!-- 🔹 الملاحظات -->
            <flux:input label="الملحوظات" placeholder="ادخل الملاحظات" wire:model="form.expenses_note" />

            <!-- 🔥 التاريخ (بدل input العادي) -->
            <flux:input label="التاريخ" type="date" wire:model="form.expenses_date" dir="rtl" />

            <!-- 🔹 الأزرار -->
            <div class="grid grid-cols-3 items-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="button" color="red">
                            الغاء
                        </flux:button>
                    </flux:modal.close>
                </div>

                <div></div>

                <div class="text-right">
                    <flux:button type="submit" color="green">
                        اضافة مصروف
                    </flux:button>
                </div>

            </div>

        </form>

    </flux:modal>

</div>
