<div>
    @php
        $breadcrumbs = [
            [
                'icon' => 's-rectangle-group',
                'link' => '/#',
            ],
        ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="Pagina" separator />
    {{-- Because she competes with no one, no one can compete with her. --}}
</div>
