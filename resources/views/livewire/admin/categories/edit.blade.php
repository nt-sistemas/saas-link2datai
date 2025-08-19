<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/admin',
            ],
            [
                'label' => 'Categorias',
                'link' => '/admin/tenants',
                'icon' => 'o-tag',
            ],
            [
                'label' => 'Editar',
                'link' => '#default',
                'icon' => 's-pencil',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Pagina" separator/>
    <div class="bg-base-100 p-4 rounded-lg shadow">
        <x-form wire:submit.prevent="save">
            <x-input label="Categoria" wire:model="name"/>
            <x-input label="Descrição" wire:model="description"/>
            <div class="flex gap-4 justify-between items-center">
                <x-input label="Ordem" wire:model="order" type="number"/>
                <x-toggle label="Ativo" wire:model="active"/>
            </div>


            <x-slot:actions>
                <div class="w-full flex justify-end space-x-2">
                    <x-button label="Atualziar" class="btn-primary w-1/3" type="submit" spinner="save"/>
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
