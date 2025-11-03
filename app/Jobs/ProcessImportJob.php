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
    public function __construct($tenantId, $data)
    {
        $this->queue = 'imports_process';
        $this->tenantId = $tenantId;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batchInsert = [];

        foreach ($this->data as $row) {

            // The input string
            $dateString = $row->data['Data Pedido'];

            // The format of the input string
            $format = 'dd/mm/Y H:i:s';
            $carbonDate = Carbon::createFromFormat($format, $dateString);


            $dataInsert = [
                'tenant_id' => $row->tenant_id,
                'filial_id' => $this->processarFilial($row->data['filial'] ?? $row->data['Filial'], $row->tenant_id),
                'vendedor_id' => $this->processarVendedor($row->data['Nome Vendedor'], $row->data['CPF Vendedor'], $row->tenant_id),
                'tipo_grupo_id' => $this->processarTipoPedido($row->data['Tipo Pedido'], $row->tenant_id),
                'grupo_estoque_id' => $this->processarGrupoEstoque($row->data['Grupo Estoque'], $row->tenant_id),
                'plano_habilitado_id' => $this->processarPlanoHabilitado($row->data['Plano Habilitação'] ?? null, $row->tenant_id),
                'modalidade_venda_id' => $this->processarModalidadeVenda($row->data['Modalidade Venda'] ?? null, $row->tenant_id),
                'base_faturamento_compra' => $this->convertToFloat($row->data['Base Faturamento Compra'] ?? $row->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? 0.00),
                'valor_franquia' => $this->convertToFloat($row->data['Valor Franquia'] ?? $row->data['ValorFranquia'] ?? 0.00),
                'valor_total' => $this->convertToFloat($row->data['Valor Caixa'] ?? $row->data['Valor_x0020_Caixa'] ?? 0.00),
                'data_pedido' => $carbonDate->format('Y-m-d'), //Carbon::parse(Date::excelToDateTimeObject($row->data['Data Pedido']) ?? $row->data['Data_0x0020_pedido'])->format('Y-m-d'),
                'numero_pedido' => $row->data['Número PV'] ?? $row->data['Numero_x0020_Pedido'],
                'descricao_comercial' => $row->data['Descricao Comercial'] ?? null,
                'categoria' => $row->data['Categoria'] ?? null,
                'fabricante' => $row->data['Fabricante'] ?? null,
                'import_id' => $row->id,
            ];


            $batchInsert[] = $dataInsert;
        }


        Venda::upsert(
            $batchInsert,
            ['import_id', 'tenant_id'],
            [
                'filial_id',
                'vendedor_id',
                'tipo_grupo_id',
                'grupo_estoque_id',
                'plano_habilitado_id',
                'modalidade_venda_id',
                'base_faturamento_compra',
                'valor_franquia',
                'valor_total',
                'data_pedido',
                'numero_pedido',
                'descricao_comercial',
                'categoria',
                'fabricante',
            ]
        );

        foreach ($batchInsert as $insertedRow) {
            $venda = Venda::where('tenant_id', $insertedRow['tenant_id'])
                ->where('import_id', $insertedRow['import_id'])
                ->first();

            if ($venda) {
                Import::atualizarStatusImportacaoPorRegistro($venda);
            }
        }

        $batchInsert = [];
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

    public function convertToFloat($value)
    {

        // Remove any non-numeric characters except for the decimal point and minus sign
        $currencyString = $value;

        // Remove the currency symbol and spaces (optional, but good practice)
        $numericString = str_replace(['R$', ' '], '', $currencyString);

        // Remove the thousands separator ('.')
        $numericString = str_replace('.', '', $numericString);

        // Replace the decimal separator (',') with a dot ('.')
        $numericString = str_replace(',', '.', $numericString);

        // Cast the string to a float (decimal)
        $decimalValue = (float) $numericString;


        return $decimalValue;
    }
}
