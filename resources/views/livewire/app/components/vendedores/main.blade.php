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

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="{{$vendedor->name}}" subtitle="Gerencie Vendedor {{$vendedor->name}}" separator>
        <x-slot:actions>

        </x-slot:actions>
    </x-header>

    <x-toggle label="Gráficos" wire:model="item1" wire:click="changeView" class="my-4"/>


    <div wire:show="!item1" class="mb-4">
        <div class="flex flex-col gap-4">
            @foreach ($this->categories as $category)
                <div wire:key="category-{{ $category->id }}"
                     class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <h2 class="text-primary text-lg font-bold">.:: {{ $category->name }} ::. </h2>

                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2"
                         wire:sortable-group.options="{ animation: 100 }">
                        @foreach ($category->groups()->orderBy('order')->get() as $group)
                            <div wire:key="group-{{ $group->id }}"
                                 class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow ">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-primary text-lg font-bold">{{ $group->name }}</h2>

                                </div>
                                <livewire:app.components.totalizador wire:key="{{ $group->id }}"
                                                                     :grupo_id="$group->id"
                                                                     vendedor_id="{{$vendedor->id}}"/>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div wire:show="item1" class="mb-4">
        <div class="flex flex-col gap-4">
            @foreach ($this->categories as $category)
                <div wire:key="category-{{ $category->id }}"
                     class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <h2 class="text-primary text-lg font-bold">.:: {{ $category->name }} ::. </h2>

                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                        @foreach ($category->groups()->orderBy('order')->get() as $group)
                            <div wire:key="group-{{ $group->id }}"
                                 class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow ">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-primary text-lg font-bold">{{ $group->name }}</h2>

                                </div>
                                <div>
                                    <livewire:app.charts.totalizador
                                        wire:key="{{ $group->id }}"
                                        :grupo_id="$group->id"
                                        vendedor_id="{{$vendedor->id}}"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <livewire:app.components.panel-pedidos date_ini="{{ $date_ini }}" date_fim="{{ $date_fim }}"/>
        <livewire:app.components.panel-estoque date_ini="{{ $date_ini }}" date_fim="{{ $date_fim }}"/>

    </div>


</div>
