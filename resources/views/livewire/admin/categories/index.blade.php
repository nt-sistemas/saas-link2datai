<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/admin',
            ],
            [
                'label' => 'Categorias',
                'link' => '#default',
                'icon' => 's-tag',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Categorias" separator/>

    <div class="flex items-center  mb-4 bg-base-100 p-4 rounded-lg shadow">
        <div class="w-1/3 ">
            <a href="{{ route('admin.categories.create') }}" class="bg-green-500 btn hover:bg-green-700">
                <x-icon name="s-plus"/>
                Adicionar Categoria
            </a>

        </div>

        <div class="w-2/3 items-center ">
            <x-input type="text" placeholder="Pesquisar..." wire:model.live="search"/>
        </div>

    </div>

    <div class="w-full bg-white p-2 rounded-lg shadow overflow-hidden">
        <x-table :headers="$headers" :rows="$this->categories">
            @scope('cell_active', $category)
            @if ($category->active)
                <x-icon name="s-check-circle" class="text-green-500"/>
            @else
                <x-icon name="s-x-circle" class="text-red-500"/>
            @endif
            @endscope
            @scope('actions', $category)
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.categories.edit', $category->id) }}"
                   class="text-white bg-blue-500 p-2 hover:bg-blue-700 btn-circle">
                    <x-icon name="s-pencil"/>
                </a>
                <x-button icon="o-trash" wire:click.prevent="modalDelete('{{ $category->id }}')"
                          wire:key="delete-{{ $category->id }}"
                          class="btn-circle bg-red-500 hover:bg-red-700 text-white"/>

            </div>
            @endscope

        </x-table>

    </div>

    <x-modal wire:model="modal_delete" title="Confirmar Exclusão" box-class="bg-red-200 border" class="backdrop-blur"
             persistent separator>
        <div class="flex flex-col items-center space-y-4">
            <span class="font-bold text-lg text-gray-700">Você tem certeza que deseja excluir esta empresa?</span>
            <span class="font-black text-xl text-red-500">{{ $category_name }}</span>
        </div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_delete = false"/>
            <x-button label="Deletar" class="bg-red-500 hover:bg-red-700" wire:click="delete"/>
        </x-slot:actions>
    </x-modal>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
</div>
