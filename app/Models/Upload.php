<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    //
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'filename',
        'attachment',
        'rows',
        'status',
        'message',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
