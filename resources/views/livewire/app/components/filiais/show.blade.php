<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-home-modern',
                'link' => '/',
            ],
            [
                'icon' => 'o-building-office-2',
                'link' => route('app.filiais'),
            ],
            [
                'label' => $filial->name,
                'link' => '#default',
                'icon' => 's-building-office',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header :title="$filial->name" separator>
        <x-slot:actions>
            <x-button label="Home" class="btn-primary" link="{{route('app.dashboard')}}"/>
            <x-button label="Filiais" class="btn-primary" link="{{route('app.filiais')}}"/>
            <x-button label="Vendedores" class="btn-primary" wire:click="irParaVendedores"/>
        </x-slot:actions>
    </x-header>
    <div class="flex flex-col gap-4">
        <div id="chart-filial-anual" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <livewire:app.components.filiais.charts.chart-filial-anual :id="$filial->id"/>

        </div>
        <div id="chart-filial-mensal" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <livewire:app.components.filiais.charts.chart-filial-mensal :id="$filial->id"/>
        </div>

    </div>
    <div class="mt-8">
        <span class="text-gray-600 text-xl font-black">Vendedores</span>
        <x-menu-separator/>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        @foreach($vendedores as $vendedor)
            <div
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg hover:bg-secondary/30 transition-shadow mb-4 hover:cursor-pointer">
                <h2 class="text-primary text-sm font-bold mb-2">{{ $vendedor['vendedor']->name }}</h2>
                <x-menu-separator/>
                <p class="text-gray-600 text-xl font-bold">Total de Vendas:
                    R$ {{ number_format($vendedor['total_vendas'],2,',','.') }}</p>

            </div>

        @endforeach
    </div>

</div>
