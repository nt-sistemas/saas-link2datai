<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    /** @use HasFactory<\Database\Factories\GrupoFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'grupos';

    protected $fillable = [
        'tenant_id',
        'tipo_grupo_id',
        'name',
        'description',
        'order',
        'campo_valor_id'

    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tipoGrupo()
    {
        return $this->belongsTo(TipoGrupo::class);
    }

    public function grupo_estoque()
    {
        return $this->belongsToMany(GrupoEstoque::class, 'grupo_grupo_estoque', 'group_id', 'grupo_estoque_id');
    }

    public function plano_habilitados()
    {
        return $this->belongsToMany(PlanoHabilitado::class, 'grupo_plano_habilitado', 'group_id', 'grupo_plano_habilitado_id');
    }

    public function modalidade_venda()
    {
        return $this->belongsToMany(ModalidadeVenda::class, 'grupo_modalidade_vendas', 'group_id', 'modalidade_venda_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
