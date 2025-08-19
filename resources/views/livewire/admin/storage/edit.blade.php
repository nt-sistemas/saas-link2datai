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
    {{-- The best athlete wants his opponent at his best. --}}
</div>
