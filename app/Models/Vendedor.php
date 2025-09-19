<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    /** @use HasFactory<\Database\Factories\VendedorFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'vendedores';

    protected $fillable = [
        'tenant_id',
        'document',
        'name',
        'filial_id',
        'avatar',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'vendedor_id');
    }

    public function metas()
    {
        return $this->hasMany(Meta::class, 'vendedor_id');
    }

    public function metas_grupo()
    {
        return $this->belongsToMany(MetaGrupo::class, 'meta_vendedor_grupo', 'vendedor_id', 'meta_grupo_id')->withPivot('month', 'year', 'quant', 'valor');
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }
}
