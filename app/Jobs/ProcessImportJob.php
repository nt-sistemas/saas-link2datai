<?php

namespace App\Jobs;

use App\Models\Filial;
use App\Models\Venda;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class ProcessImportJob implements ShouldQueue
{
    use Queueable;


    public $data = [];
    public $tenantId;

    public $importId;
    public $ties = 3;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $tenantId)
    {

        $this->data = $data->data;

        $this->data['data_pedido'] = Carbon::parse($data->data_pedido)->format('Y-m-d');
        $this->tenantId = $tenantId;
        $this->importId = $data['id'];;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $import = \App\Models\Import::find($this->importId);
            $venda = new Venda();
            $venda->tenant_id = $this->data['tenant_id'];
            $venda->filial_id = $this->processarFilial($this->data['filial'] ?? $this->data['Filial'], $this->tenantId);
            $venda->vendedor_id = $this->processarVendedor($this->data['nome_vendedor'], $this->data['cpf_vendedor'], $this->tenantId);
            $venda->tipo_grupo_id = $this->processarTipoPedido($this->data['tipo_pedido'], $this->tenantId);
            $venda->grupo_estoque_id = $this->processarGrupoEstoque($this->data['grupo_estoque'], $this->tenantId);
            $venda->plano_habilitado_id = $this->processarPlanoHabilitado($this->data['plano_habilitacao'], $this->tenantId);
            $venda->modalidade_venda_id = $this->processarModalidadeVenda($this->data['modalidade_venda'], $this->tenantId);
            $venda->base_faturamento_compra = $this->data['base_faturamento_compra'] ?? $this->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? 0.00;
            $venda->valor_franquia = $this->data['valor_franquia'] ?? $this->data['ValorFranquia'] ?? 0.00;
            $venda->valor_total = $this->data['valor_caixa'] ?? $this->data['Valor_x0020_Caixa'] ?? 0.00;
            $venda->data_pedido = date_format(date_create($this->data['data_pedido'] ?? $this->data['Data_0x0020_pedido']), 'Y-m-d');
            $venda->numero_pedido = $this->data['numero_pv'] ?? $this->data['Numero_x0020_Pedido'];
            $venda->descricao_comercial = $this->data['descricao_comercial'] ?? null;
            $venda->categoria = $this->data['categoria'] ?? null;
            $venda->fabricante = $this->data['fabricante'] ?? null;
            $venda->save();


            Log::info("Import id: {$this->importId} processado com sucesso.");
            $import = \App\Models\Import::find($this->importId);
            $import->is_processed = true;
            $import->message_error = null;
            $import->save();
        } catch (\Exception $e) {
            if ($this->ties > 0) {
                $this->ties--;
                $this->handle();
            } else {

                Log::error("Erro ao processar import id: {$this->importId} - Erro: {$e->getMessage()}");
                $import = \App\Models\Import::find($this->importId);

                $import->message_error = $e->getMessage();
                $import->is_processed = false;
                $import->save();
            }
        }
    }


    public function processarFilial(string $filial, $tenantId)
    {
        $data = explode('-', $filial);

        $filialExists = Filial::where('code', trim($data[0]))
            ->where('tenant_id', $tenantId)
            ->first();


        if ($filialExists) {

            return $filialExists->id;
        }

        $filial = Filial::updateOrCreate(
            ['code' => trim($data[0])],
            [
                'tenant_id' => $tenantId,
                'name' => Str::upper(trim($data[1])),
            ],
        );


        return $filial->id;
    }

    public function processarVendedor($nome, $document, $tenantId)
    {
        $vendedorData = [
            'name' => $nome,
            'document' => str_replace(['.', '-', ' ', "'"], '', $document),
            'tenant_id' => $tenantId,
        ];

        $vendedorExists = Vendedor::where('document', $vendedorData['document'])->first();

        if ($vendedorExists) {
            return $vendedorExists->id;
        }


        $vendedor = \App\Models\Vendedor::updateOrCreate(
            ['document' => $vendedorData['document']],
            [
                'name' => $vendedorData['name'],
                'tenant_id' => $vendedorData['tenant_id'],
                // outros campos...
            ]
        );

        return $vendedor->id;
    }

    public function processarTipoPedido($tipoPedido, $tenantId)
    {
        $tipoPedido = \App\Models\TipoGrupo::updateOrCreate(
            [
                'name' => Str::upper($tipoPedido),
                'tenant_id' => $tenantId
            ],

        );


        return $tipoPedido->id;
    }

    public function processarGrupoEstoque($grupoEstoque, $tenantId)
    {
        $grupoEstoque = \App\Models\GrupoEstoque::updateOrCreate(
            [
                'name' => Str::upper($grupoEstoque),
                'tenant_id' => $tenantId
            ],
        );

        return $grupoEstoque->id;
    }

    public function processarPlanoHabilitado($planoHabilitado, $tenantId)
    {
        if (empty($planoHabilitado)) {
            return null;
        }
        $planoHabilitado = \App\Models\PlanoHabilitado::updateOrCreate(
            [
                'name' => Str::upper($planoHabilitado),
                'tenant_id' => $tenantId
            ],
        );
        ds($planoHabilitado);

        return $planoHabilitado->id;
    }

    public function processarModalidadeVenda($modalidadeVenda, $tenantId)
    {
        $modalidadeVenda = \App\Models\ModalidadeVenda::updateOrCreate(
            [
                'name' => Str::upper($modalidadeVenda),
                'tenant_id' => $tenantId
            ],
        );

        return $modalidadeVenda->id;
    }
}
