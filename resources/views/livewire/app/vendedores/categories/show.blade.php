<div class="flex flex-col p-4 gap-4">
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/',
            ],
        ];

        if ($vendedor_id) {
            $breadcrumbs[] = [
                'label' => \App\Models\Vendedor::find($vendedor_id)->name,
                'link' => route('app.vendedores', $vendedor_id),
                'icon' => 'o-briefcase',
            ];
        }

        $breadcrumbs[] = [
            'label' => $group->name,
            'link' => '#default',
            'icon' => 's-building-office-2',
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="{{ $group->name }}" subtitle="{{ $group->description }}" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex gap-2 justify-between items-center">
                <x-datetime label="Data Inicial" wire:model="data_ini"/>
                <x-datetime label="Data Final" wire:model="data_fim"/>
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" class="btn-primary" wire:click="filter"/>

        </x-slot:actions>
    </x-header>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="flex flex-col gap-4 justify-between">
        <div class="p-4 rounded-lg shadow-md bg-white">
            <span class="font-semibold">Total de Vendas Mensal - {{ $group->name }}</span>
            <livewire:app.charts.chart-bar-valor-mensal :grupo_id="$group->id" :dt_inicio="$data_ini"
                                                        :dt_fim="$data_fim"
                                                        :vendedores_multi_ids="$vendedores_multi_ids"/>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-md">
            <span class="font-semibold">Quantidade de Vendas Mensal - {{ $group->name }}</span>
            <livewire:app.charts.chart-bar-quantidade-mensal :grupo_id="$group->id" :dt_inicio="$data_ini"
                                                             :dt_fim="$data_fim"
                                                             :vendedores_multi_ids="$vendedores_multi_ids"/>
        </div>
        <div class="flex flex-col gap-4 justify-between">
            <div class="p-4 rounded-lg shadow-md bg-white">
                <span class="font-semibold">Total de Vendas - {{ $group->name }}</span>
                <livewire:app.charts.chart-bar-valor :grupo_id="$group->id" :dt_inicio="$data_ini" :dt_fim="$data_fim"
                                                     :vendedores_multi_ids="$vendedores_multi_ids"/>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md">
                <span class="font-semibold">Quantidade de Vendas - {{ $group->name }}</span>
                <livewire:app.charts.chart-bar-quantidade :grupo_id="$group->id" :dt_inicio="$data_ini"
                                                          :dt_fim="$data_fim"
                                                          :vendedores_multi_ids="$vendedores_multi_ids"/>
            </div>
        </div>


    </div>
