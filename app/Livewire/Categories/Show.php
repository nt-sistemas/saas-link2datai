<?php

namespace App\Livewire\Categories;

use App\Models\Grupo;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


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

    public array $chartPeriodo;
    public array $chartRankingFiliais;
    public array $chartRankingVendedores;

    public function mount()
    {
        $this->group = Grupo::find($this->id);


        $this->lastUpdated = Venda::query()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();

        $this->data_ini = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->data_fim = $this->lastUpdated ? Carbon::parse($this->lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');


        $this->chartPeriodo = $this->getDataChart();
        $this->chartRankingFiliais = $this->getRankingFiliais();
        $this->chartRankingVendedores = $this->getRankingVendedores();
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

        $tipo_grupo_id = $grupo->tipoGrupo()->pluck('id')->first();

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
                $query->where('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
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
    public function getRankingFiliais()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo()->pluck('id')->first();

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
                $query->where('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->orderBy('total', 'desc')
            ->groupBy('filial_id')
            ->with('filial')
            ->limit(10)
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
            $chart['labels'][] = $venda->filial->name;
            $chart['data'][] = $venda->total;
            $chart['quantidade'][] = $venda->quantidade;
        }



        return [
            'valor_total' => [
                'type' => 'bar',

                'options' => [
                    'indexAxis' => 'y',
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
                    'indexAxis' => 'y',
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
    public function getRankingVendedores()
    {
        $grupo = Grupo::find($this->id);

        $tipo_grupo_id = $grupo->tipoGrupo()->pluck('id')->first();

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
                $query->where('tipo_grupo_id', $tipo_grupo_id);
            })
            ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
            })
            ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
            })
            ->when($plano_habilitado_ids, function ($query) use ($plano_habilitado_ids) {
                $query->whereIn('plano_habilitado_id', $plano_habilitado_ids);
            })
            ->orderBy('total', 'desc')
            ->groupBy('vendedor_id')
            ->with('vendedor')
            ->limit(10)
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
            $chart['labels'][] = $venda->vendedor->name;
            $chart['data'][] = $venda->total;
            $chart['quantidade'][] = $venda->quantidade;
        }



        return [
            'valor_total' => [
                'type' => 'bar',

                'options' => [
                    'indexAxis' => 'y',
                    'width' => '100%',
                    'maintainAspectRatio' => false,
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
                            'data' => $chart['data'],
                            'backgroundColor' => ['#002855'],
                        ],

                    ]
                ]
            ],
            'quantidade_total' => [
                'type' => 'bar',
                'options' => [
                    'indexAxis' => 'y',
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

    public function filter()
    {
        $this->chartPeriodo = $this->getDataChart();
        $this->chartRankingFiliais = $this->getRankingFiliais();
        $this->chartRankingVendedores = $this->getRankingVendedores();
    }
}
