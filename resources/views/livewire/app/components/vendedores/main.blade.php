<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-home-modern',
                'link' => '/',
            ],
            [
                'label' => $vendedor->name,
                'link' => '#default',
                'icon' => 's-briefcase',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="{{ $vendedor->name }} " subtitle="Gerencie Vendedor {{ $vendedor->name }}" separator>
        <x-slot:actions>

        </x-slot:actions>
    </x-header>

    <div wire:sortable="reorderCategories" wire:sortable-group="reorderGroups" class="flex flex-col gap-4"
        wire:sortable.options="{ animation: 50 }">
        @foreach ($this->categories as $category)
            <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}"
                class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-primary text-lg font-bold">.:: {{ $category->name }} | Total: R$
                        {{ number_format($this->totalCategoria($category->id), 2, ',', '.') }} | Quantidade:
                        {{ $this->quantidadeCategoria($category->id) }} ::. </h2>
                    <x-icon wire:sortable.handle name="s-hand-raised"
                        class="hover:text-primary text-gray-200 handle cursor-move" />
                </div>
                <ul wire:sortable-group.item-group="{{ $category->id }}" class="grid grid-cols-1 lg:grid-cols-3 gap-2"
                    wire:sortable-group.options="{ animation: 100 }">
                    @foreach ($category->groups()->orderBy('order')->get() as $group)
                        <a href="{{ route('app.categories.vendedor.show', [$group->id, $vendedor_id]) }}"
                            class="">
                            <li wire:sortable-group.item="{{ $group->id }}" wire:key="group-{{ $group->id }}"
                                class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow hover:bg-secondary/50 ">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-primary text-lg font-bold">{{ $group->name }}</h2>
                                    <x-icon wire:sortable-group.handle name="s-hand-raised"
                                        class="hover:text-primary text-gray-200 handle cursor-move" />
                                </div>
                                <div>
                                    <livewire:app.charts.totalizador wire:key="{{ $group->id }}" :grupo_id="$group->id"
                                        :vendedor_id="$vendedor_id" />
                                </div>

                            </li>
                        </a>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>


</div>
