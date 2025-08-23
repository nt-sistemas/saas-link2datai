<?php

namespace App\Livewire\App\GrupoEstoque;

use App\Models\GrupoEstoque;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class Main extends Component
{
        public $id;

     public $date_ini;
    public $date_fim;

    public $data;
    public $chartData;
    public $chartDataFilial;
    public $chartDataVendedor;

    public $chartDataProduto;

    public $grupo_estoque;
    public $total;
    public $quantidade;
    public $mes_anterior;
    public $quantidade_anterior;
    public $mes_anterior_ini;
    public $mes_anterior_fim;

    public function mount($id)
    {
        $this->id = $id;
        $link2B = new \App\Helper\Link2BClass(auth()->user()->tenant_id);
        $this->grupo_estoque=GrupoEstoque::find($id);

       $this->date_ini = $link2B->getDateRange()['date_ini'];
       $this->date_fim = $link2B->getDateRange()['date_fim'];
       $this->mes_anterior_ini = Carbon::parse($this->date_ini)->subMonth()->startOfMonth()->format('Y-m-d');
       $this->mes_anterior_fim = Carbon::parse($this->date_ini)->subMonth()->endOfMonth()->format('Y-m-d');


       $this->chartData = $this->getChartData();
       $this->chartDataFilial = $this->getChartDataFilial();
       //$this->chartDataVendedor = $this->getChartDataVendedor();
       //$this->chartDataProduto = $this->getChartDataProduto();

       $mes_anterior = Carbon::parse($this->date_ini)->subMonth()->format('Y-m');

       $this->total = Venda::where('grupo_estoque_id', $this->id)
           ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
           ->sum('valor_total');

        $this->mes_anterior = Venda::where('grupo_estoque_id', $this->id)
               ->whereBetween('data_pedido', [$this->mes_anterior_ini, $this->mes_anterior_fim])
               ->sum('valor_total');

        $this->quantidade = Venda::where('grupo_estoque_id', $this->id)
               ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
               ->count();

        $this->quantidade_anterior = Venda::where('grupo_estoque_id', $this->id)
               ->whereBetween('data_pedido', [$this->mes_anterior_ini, $this->mes_anterior_fim])
               ->count();
    }
    public function render()
    {
        return view('livewire.app.grupo-estoque.main');
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
    public function getData()
    {


        return $this->tipo_grupo->vendas()
        ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
        ->orderBy('data_pedido','asc')
        ->get();
    }

    #[Computed]
    public function getChartData()
    {
        $data = Venda::query()
            ->selectRaw('data_pedido, sum(valor_total) as total')
            ->where('grupo_estoque_id', $this->id)
            ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
            ->groupBy('data_pedido')
            ->orderBy('data_pedido', 'asc')
            ->get()
            ->toArray();

        $data_anterior = Venda::query()
            ->selectRaw('data_pedido, sum(valor_total) as total')
            ->where('grupo_estoque_id', $this->id)
            ->whereBetween('data_pedido', [$this->mes_anterior_ini, $this->mes_anterior_fim])
            ->groupBy('data_pedido')
            ->orderBy('data_pedido', 'asc')
            ->get()
            ->toArray();

        $dataChart = [];
        foreach ($data as $item) {
            $dataChart['categories'][] = Carbon::parse($item['data_pedido'])->format('d/m');
            $dataChart['series'][] = (string) (number_format($item['total'], 2, ',', '.'));
        }
        foreach ($data_anterior as $item) {
            $dataChart['series_anterior'][] = (string) (number_format($item['total'], 2, ',', '.'));
        }






       return $dataChart;
    }

    #[Computed]
    public function getChartDataFilial()
    {
        $data = Venda::query()
        ->selectRaw('filial_id, sum(valor_total) as total')
        ->where('grupo_estoque_id', $this->id)
        ->whereBetween('data_pedido', [$this->date_ini, $this->date_fim])
        ->groupBy('filial_id')
        ->get();

        $data_anterior = Venda::query()
        ->selectRaw('filial_id, sum(valor_total) as total')
        ->where('grupo_estoque_id', $this->id)
        ->whereBetween('data_pedido', [$this->mes_anterior_ini, $this->mes_anterior_fim])
        ->groupBy('filial_id')
        ->get();

        ds($data_anterior);

        $dataChart = [];
        foreach ($data as $item) {
            $dataChart['categories'][] = $item->filial->name;
            $dataChart['series'][] = (string) (number_format($item->total, 2, ',', '.'));
        }
        foreach ($data_anterior as $item) {
            $dataChart['series_anterior'][] = (string) (number_format($item->total, 2, ',', '.'));
        }




        return $dataChart;
    }
}
