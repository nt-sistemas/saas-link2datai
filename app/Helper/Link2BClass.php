<?php

namespace App\Helper;

use App\Models\Venda;
use Carbon\Carbon;

class Link2BClass
{
    public $tenant_id;
    /**
     * Create a new class instance.
     */
    public function __construct($tenant_id)
    {
        $this->tenant_id = $tenant_id;
    }

    public function getLastUpdated()
    {
         $lastUpdated = Venda::query()
         ->select('data_pedido')
            ->where('tenant_id', $this->tenant_id)
            ->orderBy('data_pedido', 'desc')
            ->first();


        return $lastUpdated;
    }

    public function getDaysOfData()
    {
        $lastUpdated = $this->getLastUpdated();

        if ($lastUpdated) {
            return Carbon::parse($lastUpdated->data_pedido)->diffInDays(Carbon::now());
        }

        return null;
    }

    public function getDateRange()
    {
        $lastUpdated = $this->getLastUpdated();

        if ($lastUpdated) {
            $date_ini = Carbon::parse($lastUpdated->data_pedido)->startOfMonth()->format('Y-m-d');
            $date_fim = Carbon::parse($lastUpdated->data_pedido)->endOfMonth()->format('Y-m-d');

            return [
                'date_ini' => $date_ini,
                'date_fim' => $date_fim,
            ];
        }

        return null;
    }
}
