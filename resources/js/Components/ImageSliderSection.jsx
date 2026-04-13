import { ChevronLeft, ChevronRight } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';

function slideToViewModel(slide, locale) {
    const src = slide.image?.startsWith('http') ? slide.image : `/storage/${slide.image}`;
    const alt =
        locale === 'en'
            ? slide.alt_en || slide.alt_es || 'Hero slide'
            : slide.alt_es || slide.alt_en || 'Slide principal';

    return { src, alt };
}

const defaultSlides = [
    {
        image: 'https://images.unsplash.com/photo-1512632578888-169bbbc64f33?auto=format&fit=crop&w=1600&q=80',
        alt_es: 'Salida procesional de la hermandad',
        alt_en: 'Brotherhood procession',
    },
    {
        image: 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80',
        alt_es: 'Detalle patrimonial y ambiente solemne',
        alt_en: 'Heritage and solemn atmosphere',
    },
    {
        image: 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=80',
        alt_es: 'Comunidad reunida en acto religioso',
        alt_en: 'Community gathered in religious event',
    },
];

export default function ImageSliderSection() {
    const { locale = 'es', webSettings } = usePage().props;
    const [current, setCurrent] = useState(0);

    const resolvedSlides = useMemo(() => {
        const raw = webSettings?.hero_slides ?? [];
        const filtered = raw
            .filter((slide) => slide?.is_active !== false && slide?.image)
            .sort((a, b) => (a?.sort ?? 999) - (b?.sort ?? 999));

        if (filtered.length === 0) {
            return defaultSlides.map((s) => slideToViewModel(s, locale));
        }

        return filtered.map((s) => slideToViewModel(s, locale));
    }, [webSettings?.hero_slides, locale]);

    useEffect(() => {
        if (resolvedSlides.length === 0) {
            return undefined;
        }
        setCurrent((i) => (i >= resolvedSlides.length ? 0 : i));
    }, [resolvedSlides.length]);

    useEffect(() => {
        if (resolvedSlides.length <= 1) {
            return undefined;
        }
        const timer = setInterval(() => {
            setCurrent((prev) => (prev + 1) % resolvedSlides.length);
        }, 5000);

        return () => clearInterval(timer);
    }, [resolvedSlides.length]);

    const goNext = () => setCurrent((prev) => (prev + 1) % resolvedSlides.length);
    const goPrev = () =>
        setCurrent((prev) => (prev - 1 + resolvedSlides.length) % resolvedSlides.length);

    if (resolvedSlides.length === 0) {
        return null;
    }

    return (
        <section className="mx-auto w-full max-w-[90rem] px-4 pb-4 pt-10 sm:px-6 sm:pb-6 lg:px-8">
            <div className="relative overflow-hidden rounded-2xl bg-zinc-200">
                <div className="relative h-72 w-full sm:h-96 lg:h-[32rem]">
                    {resolvedSlides.map((slide, i) => (
                        <img
                            key={`${i}-${slide.src}`}
                            src={slide.src}
                            alt={i === current ? slide.alt : ''}
                            aria-hidden={i !== current}
                            className={`absolute inset-0 h-full w-full object-cover object-center transition-opacity duration-700 ease-in-out motion-reduce:transition-none ${
                                i === current
                                    ? 'z-[1] opacity-100'
                                    : 'z-0 opacity-0 pointer-events-none'
                            }`}
                            loading={i < 3 ? 'eager' : 'lazy'}
                            decoding="async"
                        />
                    ))}
                </div>

                <button
                    type="button"
                    onClick={goPrev}
                    className="absolute left-3 top-1/2 z-[2] -translate-y-1/2 rounded-full bg-black/35 p-2 text-white transition hover:bg-black/50"
                    aria-label="Imagen anterior"
                >
                    <ChevronLeft size={18} />
                </button>

                <button
                    type="button"
                    onClick={goNext}
                    className="absolute right-3 top-1/2 z-[2] -translate-y-1/2 rounded-full bg-black/35 p-2 text-white transition hover:bg-black/50"
                    aria-label="Imagen siguiente"
                >
                    <ChevronRight size={18} />
                </button>

                <div className="absolute bottom-3 left-1/2 z-[2] flex -translate-x-1/2 gap-2">
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
