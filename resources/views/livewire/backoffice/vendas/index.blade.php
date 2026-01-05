<div>
  @php
  $breadcrumbs = [
  [
  'icon' => 'o-rectangle-group',
  'link' => '/backoffice',
  ],
  [
  'label' => 'Vendas',
  'link' => '#default',
  'icon' => 's-building-office-2',
  ],
  ];
  @endphp

  <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
  <x-header title="Vendas" separator />

  <div class="grid lg:grid-cols-3 w-full gap-4">
    @foreach ($tenants as $tenant)
    <div class="w-full bg-white p-2 hover:bg-secondary shadow-lg rounded-lg cursor-pointer"
      wire:click="goToTenant('{{ $tenant->id }}')">
      <span class="text-xl font-bold">{{ $tenant->name }}</span>
      <x-menu-separator />

      <div class="flex flex-col">
        <p><span class="font-bold">Total vendas: </span>{{$tenant->vendas()->count()}} </p>
        <p><span class="font-bold">Total Meses: </span>10 </p>
        <p><span class="font-bold">Total Lojas: </span>{{$tenant->filials()->count()}} </p>
        <p><span class="font-bold">Total Vendedores: </span>{{$tenant->vendedores()->count()}} </p>
      </div>
    </div>

    @endforeach
  </div>
</div>