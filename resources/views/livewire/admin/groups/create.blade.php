<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/admin',
            ],
            [
                'label' => 'Categorias',
                'link' => '/admin/groups',
                'icon' => 'o-tag',
            ],
            [
                'label' => 'Criar',
                'link' => '#default',
                'icon' => 's-square-3-stack-3d',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Criar Grupos" separator/>
    <div class="bg-base-100 p-4 rounded-lg shadow">
        <x-form wire:submit.prevent="save">
            <div class="flex w-full gap-4">
                <div class="w-full">
                    <x-input class="w-full" label="Grupo" wire:model="name"/>
                </div>

                <div class="w-full">
                    <x-select class="w-full" label="Tipo de Grupo" wire:model="select_campo_valor" :options="$campos"
                              icon="o-tag"/>
                </div>
                <div class="w-1/3">
                    <x-select class="w-full" label="Tipo de Grupo" wire:model="selected_tipo" :options="$tipos"
                              icon="o-tag"/>

                </div>
            </div>

            <x-input label="Descrição" wire:model="description"/>
            <div class="flex gap-4 justify-between items-center">
                <x-input label="Ordem" wire:model="order" type="number"/>
                <div class="w-full">
                    <x-choices
                        label="Grupo de Estoque"
                        wire:model="grupo_estoque_ids"
                        :options="$grupo_estoques"
                        allow-all
                        allow-all-text="Selecionar Todos"
                        remove-all-text="Deletar Todos"

                        compact
                        compact-text="Grupos de Estoque"
                        clearable

                    />
                </div>
                <div class="w-full">
                    <x-choices
                        label="Modalidade de Vendas"
                        wire:model="modalidade_venda_ids"
                        :options="$modalidades_venda"
                        allow-all
                        allow-all-text="Selecionar Todos"
                        remove-all-text="Deletar Todos"
                        compact
                        compact-text="Modalidades de Vendas"
                        clearable
                    />
                </div>
                <div class="w-full">
                    <x-choices
                        label="Planos Habilitados"
                        wire:model="plano_habilitado_ids"
                        :options="$planos_habilitados"
                        allow-all
                        allow-all-text="Selecionar Todos"
                        remove-all-text="Deletar Todos"
                        compact
                        compact-text="Planos Habilitados"
                        clearable

                    />
                </div>

                <x-toggle label="Ativo" wire:model="active"/>
            </div>


            <x-slot:actions>
                <div class="w-full flex justify-end space-x-2">
                    <x-button label="Criar" class="btn-primary w-1/3" type="submit" spinner="save"/>
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</div>
