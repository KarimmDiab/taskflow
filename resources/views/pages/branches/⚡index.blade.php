<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="space-y-4">
    <flux:heading size="xl" class="text-zinc-900 dark:text-white">Branches</flux:heading>
    <flux:subheading class="text-zinc-600 dark:text-zinc-400">manage your branches</flux:subheading>
    <flux:separator variant="subtle" />


    {{-- model button --}}
    <flux:modal.trigger name="add-branch">
        <flux:button variant="primary" color="fuchsia">create branch</flux:button>
    </flux:modal.trigger>

    <livewire:branch.create />
    
</div>