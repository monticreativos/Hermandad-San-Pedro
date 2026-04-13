import { ChevronLeft, ChevronRight } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

/** Valores CSS reales: evitamos clases Tailwind (object-top, etc.) que a veces no van en el CSS de producción. */
const objectPositionStyle = {
    top: 'center top',
    center: 'center center',
    bottom: 'center bottom',
};

function normalizeSlideObjectPosition(raw) {
    const v = String(raw ?? 'center')
        .toLowerCase()
        .trim();
    if (v === 'top' || v === 'arriba') {
        return 'top';
    }
    if (v === 'bottom' || v === 'abajo') {
        return 'bottom';
    }

    return 'center';
}

const defaultSlides = [
    {
        src: 'https://images.unsplash.com/photo-1512632578888-169bbbc64f33?auto=format&fit=crop&w=1600&q=80',
        alt: 'Salida procesional de la hermandad',
        objectPosition: 'center',
    },
    {
        src: 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
        alt: 'Detalle patrimonial y ambiente solemne',
        objectPosition: 'center',
    },
    {
        src: 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=80',
        alt: 'Comunidad reunida en acto religioso',
        objectPosition: 'center',
    },
];

export default function ImageSliderSection() {
    const { locale = 'es', webSettings } = usePage().props;
    const [current, setCurrent] = useState(0);
    const slidesFromSettings = (webSettings?.hero_slides ?? [])
        .filter((slide) => slide?.is_active !== false && slide?.image)
        .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999))
        .map((slide) => ({
            src: slide.image?.startsWith('http')
                ? slide.image
                : `/storage/${slide.image}`,
            alt:
                locale === 'en'
                    ? slide.alt_en || slide.alt_es || 'Hero slide'
                    : slide.alt_es || slide.alt_en || 'Slide principal',
            objectPosition: normalizeSlideObjectPosition(slide.object_position),
        }));

    const resolvedSlides =
        slidesFromSettings.length > 0 ? slidesFromSettings : defaultSlides;

    useEffect(() => {
        const timer = setInterval(() => {
            setCurrent((prev) => (prev + 1) % resolvedSlides.length);
        }, 4500);

        return () => clearInterval(timer);
    }, [resolvedSlides.length]);

    const next = () => setCurrent((prev) => (prev + 1) % resolvedSlides.length);
    const prev = () =>
        setCurrent((prev) => (prev - 1 + resolvedSlides.length) % resolvedSlides.length);

    return (
        <section className="mx-auto w-full max-w-[90rem] px-4 pb-4 pt-10 sm:px-6 sm:pb-6 lg:px-8">
            <div className="relative overflow-hidden rounded-2xl bg-zinc-200">
                <img
                    src={resolvedSlides[current].src}
                    alt={resolvedSlides[current].alt}
                    className="hero-slider__img h-72 w-full object-cover sm:h-96 lg:h-[32rem]"
                    data-hero-focal={resolvedSlides[current].objectPosition}
                    style={{
                        objectPosition:
                            objectPositionStyle[resolvedSlides[current].objectPosition] ??
                            'center center',
                    }}
                    loading="lazy"
                />

                <button
                    type="button"
                    onClick={prev}
                    className="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/35 p-2 text-white transition hover:bg-black/50"
                    aria-label="Imagen anterior"
                >
                    <ChevronLeft size={18} />
                </button>

                <button
                    type="button"
                    onClick={next}
                    className="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/35 p-2 text-white transition hover:bg-black/50"
                    aria-label="Imagen siguiente"
                >
                    <ChevronRight size={18} />
                </button>

                <div className="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-2">
                    {resolvedSlides.map((_, index) => (
                        <button
                            key={index}
                            type="button"
                            aria-label={`Ir a imagen ${index + 1}`}
                            onClick={() => setCurrent(index)}
                            className={`h-2.5 w-2.5 rounded-full transition ${index === current ? 'bg-white' : 'bg-white/50'}`}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
