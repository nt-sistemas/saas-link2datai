<?php

namespace App\Livewire\Categories;

use App\Livewire\App\Charts\ChartBar;
use App\Models\Grupo;
use App\Models\Meta;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;

#[Lazy]
class Show extends Component
{
    public $id;
    public $group;
    public $lastUpdated;
    public $data_ini;
    public $data_fim;

    public $filial_id = null;
    public $vendedor_id = null;
    public $filiais_multi_ids = [];

    public array $chartPeriodo;
    public array $chartRankingFiliaisValores;
    public array $chartRankingFiliaisQuantidades;
    public array $chartRankingVendedoresValores;
    public array $chartRankingVendedoresQuantidades;



    public function mount()
    {
        $this->data_ini = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->data_fim = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = Redis::get(auth()->user()->id . '_show_details');
        $this->filiais_multi_ids = is_null($data) ? [] : array_filter(json_decode($data, true)['filial_multi_ids']);
        $this->data_ini = is_null($data) ? $this->data_ini : json_decode($data, true)['dt_inicio'];
        $this->data_fim = is_null($data) ? $this->data_fim : json_decode($data, true)['dt_fim'];






        $this->group = Grupo::find($this->id);


        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();




        $this->chartPeriodo = $this->getDataChart();
        //$this->chartRankingFiliaisQuantidades = $this->getRankingFiliaisQuantidades();
        $this->chartRankingFiliaisValores = $this->getRankingFiliaisValores();
        $this->chartRankingVendedoresValores = $this->getRankingVendedoresValores();
        $this->chartRankingVendedoresQuantidades = $this->getRankingVendedoresQuantidades();
    }
    public function render()
    {


        return view('livewire.categories.show');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center h-screen">
                    <div class="p-4  animate-pulse max-w-sm w-full mx-auto">
                        <div>
                            <img src="{{asset('/assets/loading.svg')}}" alt="loading"/>

                        </div>
                    </div>
                </div>
            HTML;
    }

    #[Computed]
    public function getDataChart()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('SUM(' . $this->group->campo_valor_id . ') as total, DATE(data_pedido) as data,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->whereBetween('data_pedido', [$this->data_ini, $this->data_fim])
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->orderBy('data', 'asc')
            ->groupBy('data')
            ->get();

        if ($vendas->isEmpty()) {
            return [
                'valor_total' => [
                    'type' => 'bar',

                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Total - R$',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ],
                'quantidade_total' => [
                    'type' => 'bar',
                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Quantidade - Unidades',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ]
            ];
        }

        $chart = [];


        foreach ($vendas as $venda) {
            $chart['labels'][] = Carbon::parse($venda->data)->format('d/m') ?? '';
            $chart['data'][] = $venda->total ?? 0;
            $chart['quantidade'][] = $venda->quantidade ?? 0;
        }


        return [
            'valor_total' => [
                'type' => 'bar',

                'options' => [
                    'width' => '100%',
                    'maintainAspectRatio' => false,
                    'responsive' => true,

                ],
                'data' => [
                    'labels' => $chart['labels'],
                    'datasets' => [
                        [
                            'label' => $grupo->name . ' | Total - R$',
                            'data' => $chart['data'],
                            'backgroundColor' => ['#002855'],
                        ],

                    ]
                ]
            ],
            'quantidade_total' => [
                'type' => 'bar',
                'options' => [
                    'width' => '100%',
                    'maintainAspectRatio' => false,
                    'responsive' => true,

                ],
                'data' => [
                    'labels' => $chart['labels'],
                    'datasets' => [
                        [
                            'label' => $grupo->name . ' | Quantidade - Unidades',
                            'data' => $chart['quantidade'],
                            'backgroundColor' => ['#002855'],
                        ],

                    ]
                ]
            ]
        ];
    }

    #[Computed]
    public function getRankingFiliaisValores()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('SUM(' . $this->group->campo_valor_id . ') as total, filial_id,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->data_ini, $this->data_fim])
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->orderBy('total', 'desc')
            ->groupBy('filial_id')
            ->with('filial')
            ->get();



        if ($vendas->isEmpty()) {
            return [
                'valor_total' => [
                    'type' => 'bar',

                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Total - R$',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ],
                'quantidade_total' => [
                    'type' => 'bar',
                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Quantidade - Unidades',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ]
            ];
        }

        $chart = [];
        $collect = collect();

        foreach ($vendas as $venda) {
            $metas = Meta::query()
                ->selectRaw('SUM(valor_meta) as meta_valor')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('filial_id', $venda->filial_id)
                ->where('grupo_id', $this->id)
                ->whereBetween('mes', [Carbon::parse($this->data_ini)->month, Carbon::parse($this->data_fim)->month])
                ->whereBetween('ano', [Carbon::parse($this->data_ini)->year, Carbon::parse($this->data_fim)->year])
                ->get();



            $collect->push([
                'filial' => $venda->filial->name,
                'total' => $venda->total,
                'quantidade' => $venda->quantidade,
                'atingimento_valor' => $this->atingimento_meta($metas->first()->meta_valor ?? 0, $venda->total),
            ]);
        }

        foreach ($collect->sortByDesc('atingimento_valor')->slice(0, 10) as $item) {
            $chart['labels'][] = $item['filial'];
            $chart['data'][] = $item['total'];
            $chart['quantidade'][] = $item['quantidade'];
            $chart['atingimento_valor'][] = $item['atingimento_valor'];
        }





        return [
            'type' => 'bar',

            'options' => [
                'indexAxis' => 'y',
                'width' => '100%',
                'maintainAspectRatio' => true,
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'onHover' => $this->hover(),
                    ],
                ],

            ],

            'data' => [
                'labels' => $chart['labels'],
                'datasets' => [
                    [
                        'label' => $grupo->name . ' | Meta Valor - %',
                        'data' => $chart['atingimento_valor'],
                        'backgroundColor' => ['#002855'],
                    ],

                ]
            ]


        ];
    }

    #[Computed]
    public function getRankingFiliaisQuantidades()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('filial_id,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->data_ini, $this->data_fim])
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->groupBy('filial_id')
            ->with('filial')
            ->get();



        if ($vendas->isEmpty()) {
            return [
                'valor_total' => [
                    'type' => 'bar',

                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => true,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Total - R$',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ],

            ];
        }

        $chart = [];
        $collect = collect();

        foreach ($vendas as $venda) {

            $metas = Meta::query()
                ->selectRaw('SUM(quantidade) as meta_quantidade')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('filial_id', $venda->filial_id)
                ->where('grupo_id', $this->id)
                ->whereBetween('mes', [Carbon::parse($this->data_ini)->month, Carbon::parse($this->data_fim)->month])
                ->whereBetween('ano', [Carbon::parse($this->data_ini)->year, Carbon::parse($this->data_fim)->year])
                ->get();



            $collect->push([
                'filial' => $venda->filial->name,
                'total' => $venda->total,
                'quantidade' => $venda->quantidade,
                'atingimento_quantidade' => $this->atingimento_meta($metas->first()->meta_quantidade ?? 0, $venda->quantidade),
            ]);
        }



        foreach ($collect->sortByDesc('atingimento_quantidade')->slice(0, 10) as $item) {
            $chart['labels'][] = $item['filial'];
            $chart['data'][] = $item['total'];
            $chart['quantidade'][] = $item['quantidade'];
            $chart['atingimento_quantidade'][] = $item['atingimento_quantidade'];
        }





        return [
            'type' => 'bar',
            'options' => [
                'indexAxis' => 'y',
                'width' => '100%',
                'maintainAspectRatio' => true,
                'responsive' => true,

            ],

            'data' => [
                'labels' => $chart['labels'],
                'datasets' => [
                    [
                        'label' => $grupo->name . ' | Quantidade - %',
                        'data' => $chart['quantidade'],
                        'backgroundColor' => ['#002855'],
                    ],

                ]
            ]

        ];
    }

    #[Computed]
    public function getRankingVendedoresValores()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('SUM(' . $this->group->campo_valor_id . ') as total, vendedor_id,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->data_ini, $this->data_fim])
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->groupBy('vendedor_id')
            ->with('vendedor')
            ->get();



        if ($vendas->isEmpty()) {
            return [
                'valor_total' => [
                    'type' => 'bar',

                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Total - R$',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ],
                'quantidade_total' => [
                    'type' => 'bar',
                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Quantidade - Unidades',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ]
            ];
        }

        $chart = [];
        $collect = collect();

        foreach ($vendas as $venda) {
            $metas = Meta::query()
                ->selectRaw('SUM(valor_meta) as meta_valor,SUM(quantidade) as meta_quantidade')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('vendedor_id', $venda->vendedor_id)
                ->where('grupo_id', $this->id)
                ->whereBetween('mes', [Carbon::parse($this->data_ini)->month, Carbon::parse($this->data_fim)->month])
                ->whereBetween('ano', [Carbon::parse($this->data_ini)->year, Carbon::parse($this->data_fim)->year])
                ->get();

            $collect->push([
                'vendedor' => $venda->vendedor->name,
                'total' => $venda->total,
                'quantidade' => $venda->quantidade,
                'atingimento_valor' => $this->atingimento_meta($metas->first()->meta_valor ?? 0, $venda->total),
                'atingimento_quantidade' => $this->atingimento_meta($metas->first()->meta_quantidade ?? 0, $venda->quantidade),
            ]);
        }

        foreach ($collect->sortByDesc('atingimento_valor')->slice(0, 10) as $item) {
            $chart['labels'][] = $item['vendedor'];
            $chart['data'][] = $item['total'];
            $chart['quantidade'][] = $item['quantidade'];
            $chart['atingimento_valor'][] = $item['atingimento_valor'];
            $chart['atingimento_quantidade'][] = $item['atingimento_quantidade'];
        }



        return  [
            'type' => 'bar',

            'options' => [
                'indexAxis' => 'y',
                'width' => '100%',
                'maintainAspectRatio' => true,
                'responsive' => true,
                'animation' => [
                    'delay' => 500,
                    'duration' => 500
                ],



            ],
            'data' => [
                'labels' => $chart['labels'],
                'datasets' => [
                    [
                        'label' => $grupo->name . ' | Total - R$',
                        'data' => $chart['atingimento_valor'],
                        'backgroundColor' => ['#002855'],
                    ],

                ]
            ]



        ];
    }
    #[Computed]
    public function getRankingVendedoresQuantidades()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo->pluck('id')->toArray();

        $grupo_estoque_ids = $grupo->grupo_estoque->pluck('id')->toArray();
        $modalidade_venda_ids = $grupo->modalidade_venda->pluck('id')->toArray();
        $plano_habilitado_ids = $grupo->plano_habilitados->pluck('id')->toArray();

        $vendas = Venda::query()
            ->selectRaw('vendedor_id,Count(id) as quantidade')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->whereBetween('data_pedido', [$this->data_ini, $this->data_fim])
            ->when($this->filial_id, function ($query, $filial_id) {
                $query->where('filial_id', $filial_id);
            })
            ->when($this->vendedor_id, function ($query, $vendedor_id) {
                $query->where('vendedor_id', $vendedor_id);
            })
            ->when($tipo_grupo_id, function ($query) use ($tipo_grupo_id) {
                $query->whereIn('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->groupBy('vendedor_id')
            ->with('vendedor')
            ->get();

        if ($vendas->isEmpty()) {
            return [
                'valor_total' => [
                    'type' => 'bar',

                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Total - R$',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ],
                'quantidade_total' => [
                    'type' => 'bar',
                    'options' => [
                        'width' => '100%',
                        'maintainAspectRatio' => false,
                        'responsive' => true,

                    ],
                    'data' => [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => $grupo->name . ' | Quantidade - Unidades',
                                'data' => [],
                                'backgroundColor' => ['#002855'],
                            ],

                        ]
                    ]
                ]
            ];
        }

        $chart = [];
        $collect = collect();

        foreach ($vendas as $venda) {
            $metas = Meta::query()
                ->selectRaw('SUM(quantidade) as meta_quantidade')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('vendedor_id', $venda->vendedor_id)
                ->where('grupo_id', $this->id)
                ->whereBetween('mes', [Carbon::parse($this->data_ini)->month, Carbon::parse($this->data_fim)->month])
                ->whereBetween('ano', [Carbon::parse($this->data_ini)->year, Carbon::parse($this->data_fim)->year])
                ->get();

            $collect->push([
                'vendedor' => $venda->vendedor->name,
                'total' => $venda->total,
                'quantidade' => $venda->quantidade,
                'atingimento_quantidade' => $this->atingimento_meta($metas->first()->meta_quantidade ?? 0, $venda->quantidade),
            ]);
        }

        foreach ($collect->sortByDesc('atingimento_quantidade')->slice(0, 10) as $item) {
            $chart['labels'][] = $item['vendedor'];
            $chart['data'][] = $item['total'];
            $chart['quantidade'][] = $item['quantidade'];
            $chart['atingimento_quantidade'][] = $item['atingimento_quantidade'];
        }





        return  [
            'type' => 'bar',
            'options' => [
                'indexAxis' => 'y',
                'width' => '100%',
                'maintainAspectRatio' => true,
                'responsive' => true,

            ],
            'data' => [
                'labels' => $chart['labels'],
                'datasets' => [
                    [
                        'label' => $grupo->name . ' | Quantidade - Unidades',
                        'data' => $chart['quantidade'],
                        'backgroundColor' => ['#002855'],
                    ],

                ]
            ]

        ];
    }

    public function filter()
    {
        $this->chartPeriodo = $this->getDataChart();
        $this->dispatch('show-filter-chart-bar', [
            'dt_inicio' => $this->data_ini,
            'dt_fim' => $this->data_fim,
            'filiais_multi_ids' => $this->filiais_multi_ids,
        ]);
        //$this->dispatch('refresh:chart', ['min' => rand(1, 5), 'max' => rand(1, 30)])->to(ChartBar::class);

        //$this->chartRankingFiliais = $this->getRankingFiliais();
        //$this->chartRankingVendedores = $this->getRankingVendedores();
    }

    public function hover()
    {
        return 'function(event, chartElement) {
            event.native.target.style.cursor = chartElement[0] ? "pointer" : "default";
        }';
    }

    public function atingimento_meta($meta, $venda)
    {
        $percentual =
            (($venda - $meta) / ($meta == 0 ? 1 : $meta)) * 100;
        return number_format($percentual, 2, '.', '');
    }
}
