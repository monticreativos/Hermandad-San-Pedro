export default function PatrimonioPasoGallery({ images = [], locale = 'es' }) {
    if (!images.length) {
        return null;
    }

    const title = locale === 'en' ? 'Image gallery' : 'Galería de imágenes';

    return (
        <div className="not-prose mt-12 border-t border-zinc-200 pt-10">
            <h2 className="text-xl font-bold text-[#4b1f6f] sm:text-2xl">{title}</h2>
            <ul className="mt-6 grid list-none grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {images.map((src, idx) => (
                    <li key={`${src}-${idx}`} className="overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shadow-sm">
                        <img
                            src={src}
                            alt=""
                            className="h-full w-full object-cover object-center"
                            loading="lazy"
                            decoding="async"
                        />
                    </li>
                ))}
            </ul>
        </div>
    );
}
