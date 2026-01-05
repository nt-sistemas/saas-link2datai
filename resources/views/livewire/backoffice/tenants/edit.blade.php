<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/admin',
            ],
            [
                'label' => 'Empresas',
                'link' => '/admin/tenants',
                'icon' => 'o-building-office-2',
            ],
            [
                'label' => 'Editar',
                'link' => '#default',
                'icon' => 's-pencil',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="Editar Empresa" separator />
    <div class="bg-base-100 p-4 rounded-lg shadow">
        <x-form wire:submit.prevent="save">
            <x-input label="Nome da Empresa" wire:model="name" />
            <x-input label="Email" wire:model="email" />
            <x-input label="Telefone" wire:model="phone" />
            <x-toggle label="Ativo" wire:model="active" />


            <x-slot:actions>
                <div class="w-full flex justify-end space-x-2">
                    <x-button label="Salvar" class="btn-primary w-1/3" type="submit" spinner="save" />
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
