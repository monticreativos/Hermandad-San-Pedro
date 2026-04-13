@php
    use App\Support\HeroSlidePreview;

    $src = HeroSlidePreview::resolveImageSrc($get('image'));
    $focal = HeroSlidePreview::focalFromGet($get);
    $fx = $focal['fx'];
    $fy = $focal['fy'];
    $zoom = $focal['zoom'];
@endphp

<div
    class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-950/40"
    @if (filled($src))
        wire:key="hero-slide-preview-{{ md5($src . '-' . $fx . '-' . $fy . '-' . $zoom) }}"
    @endif
>
    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
        Vista previa (como en la portada)
    </p>
    <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">
        Misma proporción y encuadre que el carrusel público: object-fit cover, punto focal y zoom.
    </p>

    @if (filled($src))
        <div class="overflow-hidden rounded-xl bg-zinc-900">
            <div class="relative h-48 w-full sm:h-64 lg:h-80">
                <img
                    src="{{ $src }}"
                    alt=""
                    class="absolute inset-0 h-full w-full object-cover"
                    style="object-position: {{ $fx }}% {{ $fy }}%; transform: scale({{ $zoom }}); transform-origin: {{ $fx }}% {{ $fy }}%;"
                />
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500">
            Sube una imagen para ver aquí el resultado del encuadre.
        </p>
    @endif
</div>
