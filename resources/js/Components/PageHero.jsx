const FONDO_HERO = '/assets/img/bg-sub-header.png';

/**
 * Cabecera compacta: fondo claro, imagen a opacidad plena, degradado para legibilidad del título.
 */
export default function PageHero({ title }) {
    if (!title) {
        return null;
    }

    return (
        <section
            className="relative -mt-14 overflow-hidden border-b border-gray-200 bg-[#fafafa] pt-14"
            aria-labelledby="page-hero-heading"
        >
            <div className="pointer-events-none absolute inset-0" aria-hidden>
                <div
                    className="absolute inset-0 bg-cover bg-center bg-no-repeat sm:bg-[center_right]"
                    style={{ backgroundImage: `url('${FONDO_HERO}')` }}
                />
                <div className="absolute inset-0 bg-gradient-to-r from-white via-white/88 to-transparent" />
            </div>

            <div className="relative mx-auto max-w-[90rem] px-4 py-8 sm:px-8 sm:py-10 lg:px-12 lg:py-11">
                <h1
                    id="page-hero-heading"
                    className="max-w-4xl text-3xl font-bold leading-tight tracking-tight text-[#4b1f6f] sm:text-4xl"
                >
                    {title}
                </h1>
            </div>
        </section>
    );
}
