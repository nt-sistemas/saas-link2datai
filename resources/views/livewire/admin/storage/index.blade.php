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
    {{-- In work, do what you enjoy. --}}
</div>
