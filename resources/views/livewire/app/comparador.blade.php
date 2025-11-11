<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-rectangle-group',
                'link' => '/',
            ],
            [
                'label' => 'Comparar Meses',
                'link' => '#default',
                'icon' => 's-arrows-right-left',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Comparar Meses" separator/>

    <div class="flex p-4 bg-white rounded-lg shadow-lg gap-4 w-full ">
        <div class="flex flex-row gap-4 w-full   p-2">
            <div class="flex gap-2 w-full  ">
                <div class="flex w-full">
                    <x-select class="w-full" label="Primeiro Mês" wire:model="mes_inicial" :options="$meses"
                              icon="o-calendar"/>
                </div>
                <div class="flex w-full">
                    <x-input label="Ano" wire:model="ano_inicial" icon="o-calendar" type="number"/>
                </div>

            </div>
            <div class="flex gap-2 w-full">
                <div class="w-full">
                    <x-select label="Segundo Mês" wire:model="mes_final" :options="$meses" icon="o-calendar"
                              class="w-full"/>
                </div>
                <div class="w-full">
                    <x-input label="Ano" wire:model="ano_final" icon="o-calendar" type="number"/>
                </div>
            </div>
        </div>

        <div class="flex gap-4 w-full p-2">
            <div>
                <x-select label="Grupo" wire:model="grupo_id" :options="$groups" icon="o-calendar"
                          placeholder="Selecione um Grupo" class="w-full"/>
            </div>
            <div class="w-full">
                <x-choices label="Filiais" wire:model="filiais_multi_ids" :options="$filiais" clearable
                           class="w-full"/>
            </div>
            <div class="w-full">
                <x-choices label="Vendedores" wire:model="vendedores_multi_ids" :options="$vendedores" clearable
                           class="w-full"/>
            </div>

        </div>


        <div class="w-1/5 flex justify-end  p-2 ">
            <div>
                <x-button label="Comparar" class="btn-primary mt-6" wire:click="comparar"/>
            </div>
        </div>

    </div>

    @if($grupo_id)
        <div class="flex mt-4 gap-4">
            <div class="bg-white rounded shadow-lg w-full p-4">
                <span class="text-4xl text-primary font-bold">{{$mes_1}}</span>
                <div class="p-4 flex flex-col gap-2">
                    <p class="font-semibold text-2xl"><span class="font-bold">Valor: </span>
                        R$ {{ number_format($total_1, 2, ',', '.') }}</p>
                    <p class="font-semibold text-2xl"><span class="font-bold">Quantidade: </span>{{$quantidade_1}}</p>
                </div>

            </div>
            <div class="bg-gray-400 rounded shadow-lg w-1/4 p-4">
                <div class="flex flex-col gap-4 justify-center items-center w-full">
                    @php
                        $percentualTotal = (($total_1 - $total_2) / ($total_2 == 0 ? 1 : $total_2)) * 100;

                    @endphp
                    <div class="bg-primary w-full rounded p-2 text-white font-bold text-2xl ">
                        <p>Valor: {{number_format($percentualTotal,2,',', '.').'%'}}</p>
                    </div>
                    @php
                        $percentualQuantidade = (($quantidade_1 - $quantidade_2) / ($quantidade_2 == 0 ? 1 : $quantidade_2)) * 100;

                    @endphp
                    <div class="bg-secondary w-full rounded p-2 text-white font-bold text-2xl ">
                        <p>Quantidade: {{number_format($percentualQuantidade,2,',', '.').'%'}}</p>
                    </div>


                </div>
            </div>
            <div class="bg-white rounded shadow-lg w-full p-4">
                <span class="text-4xl text-primary font-bold">{{$mes_2}}</span>
                <div class="p-4 flex flex-col gap-2">
                    <p class="font-semibold text-2xl"><span class="font-bold">Valor: </span>
                        R$ {{ number_format($total_2, 2, ',', '.') }}</p>
                    <p class="font-semibold text-2xl"><span class="font-bold">Quantidade: </span>{{$quantidade_2}}</p>
                </div>

            </div>
        </div>
    @endif


    <div class="p-4 bg-white rounded-lg shadow-md mt-4">
        <span class="font-semibold">Valor de Vendas  </span>

        <livewire:app.charts.chart-bar-valor-comparar :grupo_id="$grupo_id" :mes_inicial="$mes_inicial"
                                                      :ano_inicial="$ano_inicial"
                                                      :mes_final="$mes_final" :ano_final="$ano_final"
                                                      :filiais_multi_ids="$filiais_multi_ids"/>

    </div>

    <div class="p-4 bg-white rounded-lg shadow-md mt-4">
        <span class="font-semibold">Quantidade de Vendas  </span>
        <livewire:app.charts.chart-bar-quantidade-comparar :grupo_id="$grupo_id" :mes_inicial="$mes_inicial"
                                                           :ano_inicial="$ano_inicial"
                                                           :mes_final="$mes_final" :ano_final="$ano_final"
                                                           :filiais_multi_ids="$filiais_multi_ids"/>

    </div>


</div>
