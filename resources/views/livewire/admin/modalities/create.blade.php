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
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
