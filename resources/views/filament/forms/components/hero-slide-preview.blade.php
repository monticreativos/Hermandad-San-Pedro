@php
    use App\Support\HeroSlidePreview;

    $src = HeroSlidePreview::resolveImageSrc($get('image'));
    $focal = HeroSlidePreview::focalFromGet($get);
    $fx = $focal['fx'];
    $fy = $focal['fy'];
    $zoom = $focal['zoom'];
@endphp

@once('fi-hero-slide-preview-styles')
    <style>
        /* Mismas alturas que ImageSliderSection.jsx (h-72 / sm:h-96 / lg:h-[32rem]).
           CSS propio: el panel Filament no incluye en su bundle utilidades arbitrarias de otras vistas. */
        .fi-hero-slide-preview__viewport {
            position: relative;
            width: 100%;
            overflow: hidden;
            height: 18rem;
        }
        @media (min-width: 640px) {
            .fi-hero-slide-preview__viewport {
                height: 24rem;
            }
        }
        @media (min-width: 1024px) {
            .fi-hero-slide-preview__viewport {
                height: 32rem;
            }
        }
    </style>
@endonce

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
        Misma lógica que la web: object-fit cover, punto focal, zoom y las mismas alturas del carrusel (18rem → 24rem → 32rem según el ancho de la ventana del navegador).
        Si hace falta más ancho que la columna del formulario, aparecerá scroll horizontal; así el recorte se acerca al escritorio real (máx. 90rem como la portada).
    </p>

    @if (filled($src))
        <div class="overflow-x-auto overflow-y-hidden">
            {{-- Ancho tipo portada: min(90rem, viewport); al menos el 100% del contenedor del formulario --}}
            <div
                class="relative overflow-hidden rounded-2xl bg-zinc-200"
                style="width: min(90rem, max(100%, calc(100vw - 6rem)));"
            >
                <div class="fi-hero-slide-preview__viewport">
                    <img
                        src="{{ $src }}"
                        alt=""
                        decoding="async"
                        class="absolute inset-0 block h-full w-full"
                        style="max-width: none; object-fit: cover; object-position: {{ $fx }}% {{ $fy }}%; transform: scale({{ $zoom }}); transform-origin: {{ $fx }}% {{ $fy }}%;"
                    />
                </div>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500">
            Sube una imagen para ver aquí el resultado del encuadre.
        </p>
    @endif
</div>
