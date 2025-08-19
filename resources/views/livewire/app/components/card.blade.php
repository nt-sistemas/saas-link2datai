<div class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow"
     wire:key="group-{{ $category->id }}">
    <div class="flex items-center justify-between">
        <h2 class="text-primary text-lg font-bold">.:: {{$category->name}} ::. </h2>
        <x-icon name="s-hand-raised" class="hover:text-primary text-gray-200 handle cursor-move"/>
    </div>
    <ul class="grid grid-cols-1 lg:grid-cols-3 gap-2" wire:sortable-group.item-group="{{ $category->id }}"
        wire:sortable-group.options="{ animation: 100 }">
        @foreach($groups as $group)
            <livewire:app.components.group :group_id="$group->id" :wire:key="$group->order"/>
        @endforeach

    </ul>
    <x-menu-separator/>
</div>
