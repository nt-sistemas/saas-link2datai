<?php

namespace App\Jobs;

use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use App\Models\TipoGrupo;
use App\Models\Venda;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CriarVendasJob implements ShouldQueue
{
    use Queueable;

    public $tenant;

    public $loja;

    public $ano;

    public $m;

    public $i;

    /**
     * Create a new job instance.
     */
    public function __construct($tenant, $loja, $ano, $m, $i)
    {
        $this->tenant = $tenant;
        $this->loja = $loja;
        $this->ano = $ano;
        $this->m = $m;
        $this->i = $i;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->createSale();
    }

    public function failed(\Throwable $exception): void
    {
        //
    }

    public function createSale()
    {
        $venda = Venda::where('tenant_id', '07636f9a-d75e-466a-a15a-13bba3311c85')
            ->inRandomOrder()
            ->with('grupoEstoque', 'tipoGrupo', 'modalidadeVenda', 'planoHabilitado')
            ->first();

        Venda::create([
            'filial_id' => $this->loja->id,
            'tenant_id' => $this->tenant->id,
            'grupo_estoque_id' => $venda->grupo_estoque_id ? GrupoEstoque::where('tenant_id', $this->tenant->id)->where('name', $venda->grupoEstoque->name)->pluck('id')->first() : null,
            'modalidade_venda_id' => $venda->modalidade_venda_id ? ModalidadeVenda::where('tenant_id', $this->tenant->id)->where('name', $venda->modalidadeVenda->name)->pluck('id')->first() : null,
            'plano_habilitado_id' => $venda->plano_habilitado_id ? PlanoHabilitado::where('tenant_id', $this->tenant->id)->where('name', $venda->planoHabilitado->name)->pluck('id')->first() : null,
            'tipo_grupo_id' => $venda->tipo_grupo_id ? TipoGrupo::where('tenant_id', $this->tenant->id)->where('name', $venda->tipoGrupo->name)->pluck('id')->first() : null,
            'vendedor_id' => Vendedor::where('filial_id', $this->loja->id)->inRandomOrder()->first()->id,
            'valor_total' => $venda->valor_total,
            'valor_franquia' => $venda->valor_franquia,
            'base_faturamento_compra' => $venda->base_faturamento_compra,
            'fabricante' => $venda->fabricante,
            'descricao_comercial' => $venda->descricao_comercial,
            'categoria' => $venda->categoria,
            'data_pedido' => Carbon::create($this->ano, $this->m, $this->i)->format('Y-m-d'),
            'numero_pedido' => $venda->numero_pedido,
        ]);
    }
}
