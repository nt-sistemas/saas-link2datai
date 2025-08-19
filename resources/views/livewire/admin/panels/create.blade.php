<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 's-rectangle-group',
                'link' => '/admin',
            ],
            [
                'title' => 'Paineis',
                'link' => '/admin/panels',
                'icon' => 's-chart-bar-square',

            ],

        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Pagina" separator/>
    {{-- Stop trying to control. --}}
</div>
