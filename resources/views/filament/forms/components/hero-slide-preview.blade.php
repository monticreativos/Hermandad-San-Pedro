@php
    use App\Support\HeroSlidePreview;

    $src = HeroSlidePreview::resolveImageSrc($get('image'));
    $focal = HeroSlidePreview::focalFromGet($get);
    $fx = $focal['fx'];
    $fy = $focal['fy'];
    $zoom = $focal['zoom'];
    $itemStatePath = $schemaComponent->getContainer()->getStatePath();
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
    class="max-w-full rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-950/40"
    wire:key="hero-slide-preview-{{ md5($itemStatePath) }}"
>
    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
        Vista previa (como en la portada)
    </p>
    <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">
        Misma lógica que la web: object-fit cover, punto focal, zoom y las mismas alturas del carrusel (18rem → 24rem → 32rem según el ancho de la ventana).
        Al mover los controles, la vista previa se actualiza al instante (renderizado enlazado al slider).
    </p>

    @if (filled($src))
        {{-- Cliente: Filament solo vuelve a pintar este bloque cuando los sliders disparan partiallyRenderComponentsAfterStateUpdated.
             Si en algún entorno fallara, Alpine sincroniza con $wire.watch. --}}
        <div
            class="max-w-full overflow-hidden rounded-2xl bg-zinc-200"
            x-data="{
                fx: @js($fx),
                fy: @js($fy),
                zoom: @js($zoom),
                bindHeroPreviewWatch() {
                    const base = @js($itemStatePath);
                    const num = (v, d) => {
                        const x = Number(v);
                        return Number.isFinite(x) ? x : d;
                    };
                    $wire.watch(base + '.focus_x', (v) => { this.fx = num(v, 50); });
                    $wire.watch(base + '.focus_y', (v) => { this.fy = num(v, 50); });
                    $wire.watch(base + '.focus_zoom', (v) => { this.zoom = num(v, 100) / 100; });
                },
            }"
            x-init="bindHeroPreviewWatch()"
        >
            <div class="fi-hero-slide-preview__viewport">
                <img
                    src="{{ $src }}"
                    alt=""
                    decoding="async"
                    class="absolute inset-0 block h-full w-full"
                    x-bind:style="'max-width:none;object-fit:cover;object-position:' + fx + '% ' + fy + '%;transform:scale(' + zoom + ');transform-origin:' + fx + '% ' + fy + '%;'"
                />
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500">
            Sube una imagen para ver aquí el resultado del encuadre.
        </p>
    @endif
</div>
