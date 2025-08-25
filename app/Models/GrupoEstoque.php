<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoEstoque extends Model
{
    /** @use HasFactory<\Database\Factories\GrupoEstoqueFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'grupo_estoques';
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'grupo_estoque_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_grupo_estoque', 'grupo_estoque_id', 'group_id');
    }


}
