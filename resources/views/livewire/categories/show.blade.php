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

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="{{ $group->name }}" subtitle="{{ $group->description }}" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex gap-2 justify-between items-center">
                <x-datetime label="Data Inicial" wire:model="data_ini" />
                <x-datetime label="Data Final" wire:model="data_fim" />
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" class="btn-primary" wire:click="filter" />

        </x-slot:actions>
    </x-header>



    @if (!empty($filiais_multi_ids))
        <div class="bg-white p-4 rounded-lg shadow-md">
            <span class="font-semibold text-xl text-primary text-center w-full">Filiais Selecionadas</span>
            <x-menu-separator class="my-2" />
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                @foreach ($filiais_multi_ids as $filial_id)
                    <x-badge value="{{ \App\Models\Filial::find($filial_id)->name }}"
                        class="badge-primary text-sm hover:bg-secondary transition-colors hover:text-primary" />
                @endforeach

            </div>

        </div>
    @endif

    {{-- The best athlete wants his opponent at his best. --}}
    <div class="flex flex-col gap-4 justify-between">
        <div class="p-4 rounded-lg shadow-md bg-white">
            <span class="font-semibold">Total de Vendas - {{ $group->name }}</span>
            <livewire:app.charts.chart-bar-valor :grupo_id="$group->id" :dt_inicio="$data_ini" :dt_fim="$data_fim"
                :filiais_multi_ids="$filiais_multi_ids" />
        </div>

        <div class="p-4 bg-white rounded-lg shadow-md">
            <span class="font-semibold">Quantidade de Vendas - {{ $group->name }}</span>
            <livewire:app.charts.chart-bar-quantidade :grupo_id="$group->id" :dt_inicio="$data_ini" :dt_fim="$data_fim"
                :filiais_multi_ids="$filiais_multi_ids" />
        </div>
    </div>
    @if (!$filial_id)
        <div class="flex  gap-4 w-full lg:flex-row flex-col">
            <div class="p-4 rounded-lg shadow-md bg-white w-full">
                <span class="font-semibold">Ranking Filiais Valor Total - {{ $group->name }}</span>
                <livewire:app.charts.chart-ranking-filiais-valor :grupo_id="$group->id" :dt_inicio="$data_ini" :dt_fim="$data_fim"
                    :filiais_multi_ids="$filiais_multi_ids" />
            </div>
            <div class="p-4 rounded-lg shadow-md bg-white w-full">
                <span class="font-semibold">Ranking Filiais Quantidade Total - {{ $group->name }}</span>
                <livewire:app.charts.chart-ranking-filiais-quantidade :grupo_id="$group->id" :dt_inicio="$data_ini"
                    :dt_fim="$data_fim" :filiais_multi_ids="$filiais_multi_ids" />
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md w-full">
                <span class="font-semibold">Ranking Filiais Atingimento Metas - {{ $group->name }}</span>
                <livewire:app.charts.chart-ranking-filiais-atingimento :grupo_id="$group->id" :dt_inicio="$data_ini"
                    :dt_fim="$data_fim" :filiais_multi_ids="$filiais_multi_ids" />
            </div>
        </div>
    @endif
    <div class="flex  gap-4 w-full lg:flex-row flex-col h-full">
        <div class="p-4 rounded-lg shadow-md bg-white w-full">
            <span class="font-semibold">Ranking Vendedores Valor Total - {{ $group->name }}</span>
            <livewire:app.charts.chart-ranking-vendedores-valor :grupo_id="$group->id" :dt_inicio="$data_ini" :dt_fim="$data_fim"
                :filiais_multi_ids="$filiais_multi_ids" />
        </div>
        <div class="p-4 rounded-lg shadow-md bg-white w-full">
            <span class="font-semibold">Ranking Vendedores Quantidade Total - {{ $group->name }}</span>
            <livewire:app.charts.chart-ranking-vendedores-quantidade :grupo_id="$group->id" :dt_inicio="$data_ini"
                :dt_fim="$data_fim" :filiais_multi_ids="$filiais_multi_ids" />
        </div>

        <div class="p-4 bg-white rounded-lg shadow-md w-full">
            <span class="font-semibold">Ranking Vendedores Atingimento Metas - {{ $group->name }}</span>
            <livewire:app.charts.chart-ranking-vendedores-atingimento :grupo_id="$group->id" :dt_inicio="$data_ini"
                :dt_fim="$data_fim" :filiais_multi_ids="$filiais_multi_ids" />
        </div>
    </div>
</div>
