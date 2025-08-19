<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 's-rectangle-group',
                'link' => '/admin',
            ],
            [
                'label' => 'Paineis',
                'link' => 'default',
                'icon' => 's-chart-bar-square',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4"/>
    <x-header title="Paineis" separator/>
    {{-- Success is as dangerous as failure. --}}
</div>
