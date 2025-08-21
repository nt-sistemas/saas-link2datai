<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 'o-home-modern',
                'link' => '/',
            ],
            [
                'label' => 'Vendedores',
                'link' => '#default',
                'icon' => 's-briefcase',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Vendedores" separator>
        <x-slot:actions>
            <x-button label="Home" class="btn-primary" link="{{route('app.dashboard')}}"/>
            <x-button label="Filiais" class="btn-primary" link="{{route('app.filiais')}}"/>
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($vendedores as $vendedor)
            <div
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <h2 class="text-primary text-md font-bold mb-2">{{  $vendedor->name }}</h2>

                </div>
                <div class="mt-auto">
                    <x-button label="Ver Detalhes" class="btn-primary w-full"
                              link="{{route('app.vendedores.show',$vendedor->id)}}"/>
                </div>
            </div>
        @endforeach
    </div>

</div>
