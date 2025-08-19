<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;
    use HasUuids;


    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'active',
        'logo',
        'domain',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    public function categories()
    {
        return $this->hasMany(Categoria::class);
    }

    public function groups()
    {
        return $this->hasMany(Grupo::class);
    }

    public function grupo_estoques()
    {
        return $this->hasMany(GrupoEstoque::class);
    }

    public function plano_habilitados()
    {
        return $this->hasMany(PlanoHabilitado::class);
    }

    public function modalidade_vendas()
    {
        return $this->hasMany(ModalidadeVenda::class);
    }

    public function tipos()
    {
        return $this->hasMany(TipoGrupo::class);
    }
}
