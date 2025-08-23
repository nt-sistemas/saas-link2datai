<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/',
            ],
            [
                'label' => $tipo_grupo->name,
                'link' => '#default',
                'icon' => 's-arrows-right-left',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="{{ $tipo_grupo->name }}" separator />
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded shadow p-4 mb-4 md:mb-0">
            @php

                $percentual = $total == 0 ? 0 : (($total - $mes_anterior) / $mes_anterior) * 100;
            @endphp
            <h3 class="text-lg font-semibold text-primary">Total de {{ $tipo_grupo->name }}</h3>
            <x-menu-separator />
            <div class="flex gap-2 justify-center items-center">
                <div class="w-full p-2 h-full">
                    <div>
                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            R$ {{ number_format($total, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-secondary rounded p-2 mt-4 text-white">
                        <div class="flex flex-col md:flex-col md:text-2xl font-bold text-gray-800 mt-2">
                            <span class="text-md md:text-lg font-bold">Mes Anterior:</span> R$
                            {{ number_format($mes_anterior, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="bg-gray-100 rounded-full flex flex-col justify-center items-center p-4  w-full h-full">
                        @if ($total > $mes_anterior)
                            <x-icon name="o-arrow-trending-up" class="w-12 h-12 text-gray-800" />
                        @else
                            <x-icon name="o-arrow-trending-down" class="w-12 h-12 text-gray-800" />
                        @endif
                        <p class="text-xl font-black text-gray-800 mt-2">
                            {{ number_format($percentual, 2, ',', '.') }}%
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow p-4 mb-4 md:mb-0">
            @php

                $percentual = (($quantidade - $quantidade_anterior) / $quantidade_anterior) * 100;
            @endphp
            <h3 class="text-lg font-semibold text-primary">Quantidade de {{ $tipo_grupo->name }}</h3>
            <x-menu-separator />
            <div class="flex gap-2 justify-center items-center">
                <div class="w-full p-2 h-full">
                    <div>
                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $quantidade }}
                        </p>
                    </div>
                    <div class="bg-secondary rounded p-2 mt-4 text-white">
                        <div class="flex flex-col md:flex-col md:text-2xl font-bold text-gray-800 mt-2">
                            <span class="text-md md:text-lg font-bold">Mes Anterior:</span>
                            {{ $quantidade_anterior }}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-100 rounded-full flex flex-col justify-center items-center p-4  w-full h-full">
                        @if ($quantidade > $quantidade_anterior)
                            <x-icon name="o-arrow-trending-up" class="w-12 h-12 text-gray-800" />
                        @else
                            <x-icon name="o-arrow-trending-down" class="w-12 h-12 text-gray-800" />
                        @endif
                        <p class=" font-black text-gray-800 mt-2">
                            {{ number_format($percentual, 2, ',', '.') }}%
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-4">
        <div id="chart-quant" class="bg-white rounded shadow p-2">
            <livewire:app.movimentacao.chart-quant :tipo_grupo="$tipo_grupo" :data="$chartData" />
        </div>
        <div id="chart-filial" class="bg-white rounded shadow p-2">
            <livewire:app.movimentacao.chart-filial :tipo_grupo="$tipo_grupo" :data="$chartDataFilial" />
        </div>

    </div>

    {{-- Nothing in the world is as soft and yielding as water. --}}
</div>
