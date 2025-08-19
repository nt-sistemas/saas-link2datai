<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'categorias';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'order',
        'active',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function groups()
    {
        return $this->hasMany(Grupo::class);
    }
}
