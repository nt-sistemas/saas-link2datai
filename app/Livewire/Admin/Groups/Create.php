<?php

namespace App\Livewire\Admin\Groups;

use App\Models\Grupo;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Mary\Traits\Toast;


#[Lazy]
class Create extends Component
{
    use Toast;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string|max:255')]
    public $description;
    #[Rule('required|string|max:20')]
    public $order;
    public $grupo_estoque_ids = [];
    public $grupo_estoques = [];

    public $plano_habilitado_ids = [];
    public $modalidade_venda_ids = [];
    public $planos_habilitados = [];
    public $modalidades_venda = [];
    public $select_campo_valor;
    public $campos = [
        [
            'id' => 'base_faturamento_compra',
            'name' => 'Base Faturamento Compra'
        ],
        [
            'id' => 'valor_franquia',
            'name' => 'Valor Franquia'
        ],
        [
            'id' => 'valor_caixa',
            'name' => 'Valor Caixa'
        ],
    ];

    public $tipos;
    public $selected_tipo = null;

    public bool $active = true;

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->grupo_estoques = Tenant::find(\Auth::user()->tenant_id)
            ->grupo_estoques()
            ->get();

        $this->planos_habilitados = Tenant::find(\Auth::user()->tenant_id)
            ->plano_habilitados()
            ->get();
        $this->modalidades_venda = Tenant::find(\Auth::user()->tenant_id)
            ->modalidade_vendas()
            ->get();

        $this->tipos = Tenant::find(\Auth::user()->tenant_id)->tipos()
            ->get();

        return view('livewire.admin.groups.create');
    }

    public function save()
    {
        //$this->validate();

        // Simulating a long-running process
        $tenant = Tenant::find(\Auth::user()->tenant_id);
        $grupo = $tenant->groups()->create([
            'name' => $this->name,
            'description' => $this->description,
            "tipo_grupo_id" => '0198ab01-2414-71ed-bffe-232fb0be2e15',
            "campo_valor_id" => 'base_faturamento_compra',
            'order' => $this->order,
            'active' => $this->active,
        ]);
        foreach ($this->grupo_estoque_ids as $grupo_estoque_id) {
            DB::table('grupo_grupo_estoque')
                ->insert(
                    [
                        'id' => \Illuminate\Support\Str::uuid(),
                        'group_id' => $grupo->id,
                        'grupo_estoque_id' => $grupo_estoque_id
                    ]
                );
        }


        $this->toast(
            type: 'success',
            title: 'Grupo criada com sucesso', // title
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

        return redirect()->route('admin.groups.index');
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
}
