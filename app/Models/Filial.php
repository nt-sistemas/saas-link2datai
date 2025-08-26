<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    /** @use HasFactory<\Database\Factories\FilialFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    public function metas()
    {
        return $this->hasMany(MetaFilial::class);
    }

    public function metas_grupo()
    {
        return $this->belongsToMany(MetaGrupo::class, 'meta_filial_grupo', 'filial_id', 'meta_grupo_id')->withPivot('month', 'year', 'quant', 'valor');
    }
}
