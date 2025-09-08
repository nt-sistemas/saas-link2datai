<?php

namespace App\Livewire\Admin\Import;

use App\Models\Import;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Lazy;


#[Lazy]
class Show extends Component
{
    public $headers = [
        'ID',
        'Nome do Arquivo',
        'Status',
        'Linhas Importadas',
        'Linhas com Erro',
        'Usuário',
        'Criado em',
    ];

    public function render()
    {
        return view('livewire.admin.import.show');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center h-screen">
                    <div class="p-4  animate-pulse max-w-sm w-full mx-auto">
                        <div>
                            <img src="{{asset('/assets/loading.svg')}}" alt="loading"/>

                        </div>
                    </div>
                </div>
            HTML;
    }

    #[Computed]
    public function data(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Import::with('user')->latest()->paginate(10);
    }
}
