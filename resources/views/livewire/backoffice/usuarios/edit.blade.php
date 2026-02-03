<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/backoffice',
            ],
            [
                'label' => 'Usuarios',
                'link' => '/backoffice/usuarios',
                'icon' => 'o-user-group',
            ],
            [
                'label' => 'Editar',
                'link' => '#default',
                'icon' => 's-pencil',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="Editar Usuario" separator />
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    @php
        $tenants = \App\Models\Tenant::all();

    @endphp
    <div class="bg-base-100 p-4 rounded-lg shadow">
        <x-form wire:submit.prevent="save">
            <x-input label="Nome da Empresa" wire:model="name" />
            <x-input label="Email" wire:model="email" />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="Senha" type="password" wire:model="password" />
                <x-input label="Confirmação de Senha" type="password" wire:model="password_confirmation" />
            </div>
            <x-select label="Master user" wire:model="tenant_id" :options="$tenants" icon="o-user" />
            <x-toggle label="Ativo" wire:model="is_active" />


            <x-slot:actions>
                <div class="w-full flex justify-end space-x-2">
                    <x-button label="Salvar" class="btn-primary w-1/3" type="submit" spinner="save" />
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
