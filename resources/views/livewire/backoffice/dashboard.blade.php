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
</div>