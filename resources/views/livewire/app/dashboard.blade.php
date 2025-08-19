<div>
    <x-header title="Dashboard" subtitle="Gerencie seus dados e visualizações" separator>
        <x-slot:actions>
            <x-button label="Filiais" class="btn-primary" wire:click="irParaFiliais"/>
            <x-button label="Vendedores" class="btn-primary" wire:click="irParaVendedores"/>
        </x-slot:actions>
    </x-header>
    <div class="bg-gray-200 mb-4 p-4 rounded-lg flex justify-between">
        <div
            class="text-xl font-bold text-primary">Data da última venda
            registrada: {{\Carbon\Carbon::parse($lastUpdated->data_pedido)->format('d/m/Y')}}</div>
        @if(intval($daysOfData)>=5)
            <div class="text-md font-bold text-red-600">
                O SISTEMA ESTÁ À <span>{{intval($daysOfData)}}</span> SEM SER ATUALIZADO COM NOVOS DADOS.
            </div>
        @else
            <div class="text-md font-bold text-green-600">
                Sistema atualizado há <span>{{intval($daysOfData)}}</span> dias.
            </div>
        @endif


    </div>
    <div wire:sortable="reorderCategories" wire:sortable-group="reorderGroups" class="flex flex-col gap-4"
         wire:sortable.options="{ animation: 50 }">
        @foreach ($this->categories as $category)
            <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}"
                 class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-primary text-lg font-bold">.:: {{$category->name}} ::. </h2>
                    <x-icon wire:sortable.handle name="s-hand-raised"
                            class="hover:text-primary text-gray-200 handle cursor-move"/>
                </div>
                <ul wire:sortable-group.item-group="{{ $category->id }}" class="grid grid-cols-1 lg:grid-cols-3 gap-2"
                    wire:sortable-group.options="{ animation: 100 }">
                    @foreach ($category->groups()->orderBy('order')->get() as $group)
                        <li wire:sortable-group.item="{{ $group->id }}" wire:key="group-{{ $group->id }}"
                            class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow ">
                            <div class="flex items-center justify-between">
                                <h2 class="text-primary text-lg font-bold">{{$group->name}}</h2>
                                <x-icon wire:sortable-group.handle name="s-hand-raised"
                                        class="hover:text-primary text-gray-200 handle cursor-move"/>
                            </div>
                            <livewire:app.components.totalizador wire:key="{{$group->id}}" :grupo_id="$group->id"/>

                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

</div>
