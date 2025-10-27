<?php

namespace App\Jobs;

use App\Models\Filial;
use App\Models\Import;
use App\Models\Venda;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProcessImportJob implements ShouldQueue
{
    use Queueable;


    public $data = [];
    public $tenantId;

    public $ties = 3;
    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Import::query()
            ->where('tenant_id', $this->tenantId)
            ->where('is_processed', false)
            ->limit(500)
            ->get()
            ->each(function ($import) {
                $this->processarVenda($import);
            });
    }

    public function processarVenda($import)
    {
        try {
            //$import = \App\Models\Import::find($import->id);

            $vendaExists = Venda::where('import_id', $import->id)
                ->where('tenant_id', $import->tenant_id)
                ->first();

            if ($vendaExists) {
                Log::info("Import id: {$import->id} Reprocessando venda existente.");
                $venda = $vendaExists;
                $venda->tenant_id = $import->tenant_id;
                $venda->filial_id = $this->processarFilial($import->data['filial'] ?? $import->data['Filial'], $import->tenant_id);
                $venda->vendedor_id = $this->processarVendedor($import->data['Nome Vendedor'], $import->data['CPF Vendedor'], $import->tenant_id);
                $venda->tipo_grupo_id = $this->processarTipoPedido($import->data['Tipo Pedido'], $import->tenant_id);
                $venda->grupo_estoque_id = $this->processarGrupoEstoque($import->data['Grupo Estoque'], $import->tenant_id);
                $venda->plano_habilitado_id = $this->processarPlanoHabilitado($import->data['Plano Habilitação'] ?? null, $import->tenant_id);
                $venda->modalidade_venda_id = $this->processarModalidadeVenda($import->data['Modalidade Venda'], $import->tenant_id);
                $venda->base_faturamento_compra = $import->data['Base Faturamento Compra'] ?? $import->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? 0.00;
                $venda->valor_franquia = $import->data['Valor Franquia'] ?? $import->data['ValorFranquia'] ?? 0.00;
                $venda->valor_total = $import->data['Valor Total'] ?? $import->data['Valor_x0020_Caixa'] ?? 0.00;
                $venda->data_pedido = Carbon::parse(Date::excelToDateTimeObject($import->data['Data Pedido']) ?? $import->data['Data_0x0020_pedido'])->format('Y-m-d');
                $venda->numero_pedido = $import->data['Número PV'] ?? $import->data['Numero_x0020_Pedido'];
                $venda->descricao_comercial = $import->data['Descricao Comercial'] ?? null;
                $venda->categoria = $import->data['Categoria'] ?? null;
                $venda->fabricante = $import->data['Fabricante'] ?? null;
                $venda->import_id = $import->id;
               
                $venda->save();
            } else {
                Log::info("Import id: {$import->id} processando nova venda.");
                $venda = new Venda();
                $venda->tenant_id = $import->tenant_id;
                $venda->filial_id = $this->processarFilial($import->data['filial'] ?? $import->data['Filial'], $import->tenant_id);
                $venda->vendedor_id = $this->processarVendedor($import->data['Nome Vendedor'], $import->data['CPF Vendedor'], $import->tenant_id);
                $venda->tipo_grupo_id = $this->processarTipoPedido($import->data['Tipo Pedido'], $import->tenant_id);
                $venda->grupo_estoque_id = $this->processarGrupoEstoque($import->data['Grupo Estoque'], $import->tenant_id);
                $venda->plano_habilitado_id = $this->processarPlanoHabilitado($import->data['Plano Habilitação'] ?? null, $import->tenant_id);
                $venda->modalidade_venda_id = $this->processarModalidadeVenda($import->data['Modalidade Venda'] ?? null, $import->tenant_id);
                $venda->base_faturamento_compra = $import->data['Base Faturamento Compra'] ?? $import->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? 0.00;
                $venda->valor_franquia = $import->data['Valor Franquia'] ?? $import->data['ValorFranquia'] ?? 0.00;
                $venda->valor_total = $import->data['Valor Total'] ?? $import->data['Valor_x0020_Caixa'] ?? 0.00;
                $venda->data_pedido = Carbon::parse(Date::excelToDateTimeObject($import->data['Data Pedido']) ?? $import->data['Data_0x0020_pedido'])->format('Y-m-d');
                $venda->numero_pedido = $import->data['Número PV'] ?? $import->data['Numero_x0020_Pedido'];
                $venda->descricao_comercial = $import->data['Descricao Comercial'] ?? null;
                $venda->categoria = $import->data['Categoria'] ?? null;
                $venda->fabricante = $import->data['Fabricante'] ?? null;
                $venda->import_id = $import->id;
              
                $venda->save();

                Log::info("Import id: {$import->id} processado com sucesso.");
                $import = \App\Models\Import::find($import->id);
                $import->is_processed = true;
                $import->message_error = null;
                $import->save();
            }
        } catch (\Exception $e) {
            if ($this->ties > 0) {
                $this->ties--;
                $this->handle();
            } else {

                Log::error("Erro ao processar import id: {$import->id} - Erro: {$e->getMessage()}");
                $import = \App\Models\Import::find($import->id);

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
