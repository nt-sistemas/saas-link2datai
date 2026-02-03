<div class="p-4 flex flex-col gap-2">

  <x-header title="Dashboard" subtitle="Gerencie seus dados e visualizações" separator>
    <x-slot:actions>
      @if(auth()->user()->is_admin)
      <x-button label="Backoffice" class="bg-orange-500 text-primary" link="/backoffice" />
      @endif

      @if(auth()->user()->cargo_id === 1 || auth()->user()->is_admin)
      <x-button label="Painel" class="btn-secondary text-primary" link="/admin" />
      @endif
    </x-slot:actions>
  </x-header>
  <div class="bg-gray-200 mb-4 p-4 rounded-lg flex flex-col gap-2 lg:flex-row justify-between">
    @if ($lastUpdated)
    <div class="lg:text-xl text-xs font-bold text-primary">Data da última venda
      registrada: {{ \Carbon\Carbon::parse($lastUpdated->data_pedido)->format('d/m/Y') }}
    </div>
    @if (intval($daysOfData) >= 5)
    <div class=" text-xs lg:text-md font-bold text-red-600">
      O SISTEMA ESTÁ HÁ <span>{{ intval($daysOfData) }}</span> SEM SER ATUALIZADO COM NOVOS DADOS.
    </div>
    @else
    <div class="text-xs lg:text-md font-bold text-green-600">
      Sistema atualizado há <span>{{ intval($daysOfData) }}</span> dias.
    </div>
    @endif
    @endif


  </div>
  <div class="flex flex-col lg:flex-row gap-2 mb-4 justify-between bg-white p-4 rounded-lg shadow-md">
    <div class="flex flex-col lg:flex-row gap-2 flex-1 flex-wrap">
      <div>
        <x-datetime label="Data Inicial" wire:model="date_ini" />
      </div>
      <div>
        <x-datetime label="Data Final" wire:model="date_fim" />
      </div>
      <div class="w-full lg:w-1/3">
        <x-choices label="Selecione a Filial" wire:model="filiais_multi_ids" :options="$this->getFiliais()"
          option-label="name" clearable />
      </div>
    </div>
    <div class="w-full lg:w-auto flex items-end gap-4">
      <x-button label="Atualizar" class="btn-primary mt-6" wire:click="updateDashboard" />
      <x-button label="Comparar Meses" class="btn-secondary text-black mt-6" link="{{route('app.comparar')}}" />
    </div>
  </div>
  <div class="flex flex-wrap gap-2 mb-4">
    @foreach ($this->getFiliais() as $filial)
    <a href="{{ route('app.filiais', $filial->id) }}" class="">
      <x-badge value="{{ $filial->name }}"
        class="badge-primary text-sm hover:bg-secondary transition-colors hover:text-primary" />
    </a>
    @endforeach
  </div>

  <div wire:sortable="reorderCategories" wire:sortable-group="reorderGroups" class="flex flex-col gap-4"
    wire:sortable.options="{ animation: 50 }">
    @foreach ($this->categories as $category)
    <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}"
      class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <h2 class="text-primary text-lg font-bold">.:: {{ $category->name }} | Total: R$
          {{ number_format($this->totalCategoria($category->id), 2, ',', '.') }} | Quantidade:
          {{ $this->quantidadeCategoria($category->id) }} ::. </h2>
        <x-icon wire:sortable.handle name="s-hand-raised" class="hover:text-primary text-gray-200 handle cursor-move" />
      </div>
      <ul wire:sortable-group.item-group="{{ $category->id }}" class="grid grid-cols-1 lg:grid-cols-3 gap-2"
        wire:sortable-group.options="{ animation: 100 }">
        @foreach ($category->groups()->orderBy('order')->get() as $group)
        <li wire:sortable-group.item="{{ $group->id }}" wire:key="group-{{ $group->id }}"
          class="bg-white w-full p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow hover:bg-secondary/50 ">
          <div class="flex items-center justify-between p-2 ">
            <h2 class="text-primary text-lg font-bold">{{ $group->name }}</h2>
            <div class="flex gap-2">
              <x-button class="btn btn-sm btn-primary text-sm" label="Detalhes"
                wire:click="clickDetalhes('{{ $group->id }}')" />
              <x-icon wire:sortable-group.handle name="s-hand-raised"
                class="hover:text-primary text-gray-200 handle cursor-move" />
            </div>
          </div>
          <div>
            <livewire:app.charts.totalizador wire:key="{{ $group->id }}" :grupo_id="$group->id" :dt_ini="$date_ini"
              :dt_fim="$date_fim" :filiais_zones="$filiais_zones" />
          </div>

        </li>
        @endforeach
      </ul>
    </div>
    @endforeach
  </div>


  <div class="flex flex-col gap-4">
    <livewire:app.components.panel-pedidos date_ini="{{ $date_ini }}" date_fim="{{ $date_fim }}" />
    <livewire:app.components.panel-estoque date_ini="{{ $date_ini }}" date_fim="{{ $date_fim }}" />

  </div>


</div>

<script></script>