<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-home-modern',
                'link' => '/',
            ],
            [
                'label' => 'Filiais',
                'link' => '#default',
                'icon' => 's-building-office-2',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Filiais" subtitle="Gerencie suas Filiais" separator>
        <x-slot:actions>
            <x-button label="Home" class="btn-primary" link="{{route('app.dashboard')}}"/>
            <x-button label="Vendedores" class="btn-primary" wire:click="irParaVendedores"/>
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($filiais as $filial)
            <div
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <h2 class="text-primary text-xl font-bold mb-2">{{  $filial->name }}</h2>

                </div>
                <div class="mt-auto">
                    <x-button label="Ver Detalhes" class="btn-primary w-full"
                              link="{{route('app.filiais.show',$filial->id)}}"/>
                </div>
            </div>
        @endforeach

    </div>
</div>
