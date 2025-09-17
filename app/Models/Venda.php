<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    /** @use HasFactory<\Database\Factories\VendaFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'vendas';
    protected $fillable = [
        'tenant_id',
        'data_pedido',
        'numero_pedido',
        'tipo_grupo_id',
        'filial_id',
        'vendedor_id',
        'grupo_estoque_id',
        'modalidade_venda_id',
        'plano_habilitado_id',
        'grupo_id',
        'valor_total',
        'base_faturamento_compra',
        'valor_franquia',
        'descricao_comercial',
        'categoria',
        'fabricante',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'base_faturamento_compra' => 'decimal:2',
        'valor_franquia' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function grupoEstoque()
    {
        return $this->belongsTo(GrupoEstoque::class);
    }

    public function modalidadeVenda()
    {
        return $this->belongsTo(ModalidadeVenda::class);
    }

    public function planoHabilitado()
    {
        return $this->belongsTo(PlanoHabilitado::class);
    }

    public function tipoGrupo()
    {
        return $this->belongsTo(TipoGrupo::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function tipo_grupo()
    {
        return $this->belongsTo(TipoGrupo::class, 'tipo_grupo_id');
    }
}
