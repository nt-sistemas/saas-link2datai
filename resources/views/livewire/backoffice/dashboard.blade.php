<div>
  @php
    $breadcrumbs = [
      [
        'label' => 'Dashboard',
        'link' => '#default',
        'icon' => 's-building-office-2',
      ],
    ];
  @endphp

  <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
  <x-header title="Backoffice Dashboard" separator />
  {{-- The whole world belongs to you. --}}
  <div class="flex gap-2">
    <x-stat title="Empresas" :value="$tenantsCount" icon="o-building-office-2" color="text-primary" />

    <x-stat title="Vendas" :value="$salesCount" icon="o-banknotes" />

    <x-stat title="Usuários" :value="$usersCount" icon="o-users" />

  </div>
  <div class="w-full mt-4 bg-base-100 p-4 rounded-lg shadow">
    <h4 class="mb-4 text-2xl font-black text-primary">Total de Importação</h4>
    <livewire:backoffice.charts.tenant-imports />
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
    <div class="bg-base-100 p-4 rounded-lg shadow mt-4 p-4 flex flex-col gap-4">
      @foreach ($this->lastFiliais() as $filial)
      <div class="flex items-center gap-4 mb-4 bg-gray-200 p-4 rounded-lg shadow">
        <div>
          <x-icon name="o-building-office-2" class="text-primary inline-block mr-2" />
        </div>
        <div class="flex flex-col">
          <h3 class="text-lg font-bold text-primary inline-block">{{ $filial->name }}</h3>
          <span class="text-gray-600 text-xs">Registrado: {{ \Carbon\Carbon::parse($filial->created_at)->format('d/m/Y') }}</span>
         
        </div>
      </div>
        
      @endforeach
    </div>
    <div class="bg-base-100 p-4 rounded-lg shadow mt-4 p-4 flex flex-col gap-4">
      @foreach ($this->lastUsers() as $user)
      <div class="flex items-center gap-4 mb-4 bg-gray-200 p-4 rounded-lg shadow">
        <div>
          <x-icon name="o-users" class="text-primary inline-block mr-2" />
        </div>
        <div class="flex flex-col">
          <h3 class="text-lg font-bold text-primary inline-block">{{ $user->name }}</h3>
          <span class="text-gray-600 text-xs">Registrado: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
         
        </div>
      </div>
        
      @endforeach
    </div>
  </div>
</div>