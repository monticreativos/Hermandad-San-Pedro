import { useMemo, useState } from 'react';
import { ChevronLeft, ChevronRight, X } from 'lucide-react';
import Modal from './Modal';
import RichHtmlArticle from './RichHtmlArticle';

function initialsFromName(name) {
    if (!name || typeof name !== 'string') return '?';
    const parts = name.trim().split(/\s+/).filter(Boolean);
    if (parts.length === 0) return '?';
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

export default function PatrimonioEnseresCatalog({ catalog, locale = 'es' }) {
    const [filterCategoryId, setFilterCategoryId] = useState(null);
    const [activeItem, setActiveItem] = useState(null);
    const [galleryIndex, setGalleryIndex] = useState(0);

    const labels =
        locale === 'en'
            ? {
                  all: 'All',
                  open: 'View detail',
                  year: 'Year',
                  author: 'Author',
                  category: 'Category',
                  gallery: 'Gallery',
                  prev: 'Previous image',
                  next: 'Next image',
                  close: 'Close',
                  empty: 'No pieces published yet.',
                  photoCount: (n) => `${n} photos`,
              }
            : {
                  all: 'Todos',
                  open: 'Ver ficha',
                  year: 'Año',
                  author: 'Autor',
                  category: 'Categoría',
                  gallery: 'Galería',
                  prev: 'Imagen anterior',
                  next: 'Imagen siguiente',
                  close: 'Cerrar',
                  empty: 'Aún no hay enseres publicados.',
                  photoCount: (n) => `${n} fotos`,
              };

    const categories = catalog?.categories ?? [];
    const items = catalog?.items ?? [];

    const filteredItems = useMemo(() => {
        if (filterCategoryId == null) return items;
        return items.filter((it) => it.category?.id === filterCategoryId);
    }, [items, filterCategoryId]);

    const openDetail = (item) => {
        setActiveItem(item);
        setGalleryIndex(0);
    };

    const closeDetail = () => {
        setActiveItem(null);
        setGalleryIndex(0);
    };

    const gallery = activeItem?.gallery ?? [];
    const hasGallery = gallery.length > 0;
    const mainSrc = hasGallery ? gallery[Math.min(galleryIndex, gallery.length - 1)] : null;

    const stepGallery = (delta) => {
        if (gallery.length <= 1) return;
        setGalleryIndex((i) => {
            const n = gallery.length;
            return (i + delta + n) % n;
        });
    };

    if (!catalog) {
        return null;
    }

    return (
        <div className="not-prose mt-10 border-t border-zinc-200 pt-10">
            {categories.length > 0 ? (
                <div className="flex flex-wrap gap-2">
                    <button
                        type="button"
                        onClick={() => setFilterCategoryId(null)}
                        className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                            filterCategoryId == null
                                ? 'bg-[#4b1f6f] text-white'
                                : 'border border-zinc-200 bg-white text-zinc-700 hover:border-[#4b1f6f]/40'
                        }`}
                    >
                        {labels.all}
                    </button>
                    {categories.map((c) => (
                        <button
                            key={c.id}
                            type="button"
                            onClick={() => setFilterCategoryId(c.id)}
                            className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                filterCategoryId === c.id
                                    ? 'bg-[#4b1f6f] text-white'
                                    : 'border border-zinc-200 bg-white text-zinc-700 hover:border-[#4b1f6f]/40'
                            }`}
                        >
                            {c.label}
                        </button>
                    ))}
                </div>
            ) : null}

            {filteredItems.length === 0 ? (
                <p className="mt-8 rounded-2xl border border-dashed border-zinc-200 bg-zinc-50 px-6 py-10 text-center text-zinc-600">
                    {labels.empty}
                </p>
            ) : (
                <ul className="mt-8 grid list-none grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    {filteredItems.map((item) => {
                        const thumb = item.gallery?.[0];
                        return (
                            <li key={item.id}>
                                <button
                                    type="button"
                                    onClick={() => openDetail(item)}
                                    className="group flex h-full w-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white text-left shadow-sm transition hover:border-[#4b1f6f]/30 hover:shadow-md"
                                >
                                    <div className="relative aspect-[4/3] w-full bg-zinc-100">
                                        {thumb ? (
                                            <img
                                                src={thumb}
                                                alt=""
                                                className="h-full w-full object-cover transition group-hover:scale-[1.02]"
                                                loading="lazy"
                                                decoding="async"
                                            />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#4b1f6f]/10 to-[#4b1f6f]/5 text-3xl font-bold text-[#4b1f6f]">
                                                {initialsFromName(item.name)}
                                            </div>
                                        )}
                                        {item.gallery?.length > 1 ? (
                                            <span className="absolute bottom-2 right-2 rounded-full bg-black/60 px-2 py-0.5 text-xs font-medium text-white">
                                                {labels.photoCount(item.gallery.length)}
                                            </span>
                                        ) : null}
                                    </div>
                                    <div className="flex flex-1 flex-col gap-2 p-4">
                                        {item.category?.label ? (
                                            <span className="text-xs font-semibold uppercase tracking-wide text-[#4b1f6f]">
                                                {item.category.label}
                                            </span>
                                        ) : null}
                                        <span className="text-lg font-semibold leading-snug text-zinc-900">{item.name}</span>
                                        <div className="mt-auto flex flex-wrap gap-x-3 gap-y-1 text-sm text-zinc-600">
                                            {item.year ? (
                                                <span>
                                                    <span className="font-medium text-zinc-500">{labels.year}: </span>
                                                    {item.year}
                                                </span>
                                            ) : null}
                                            {item.author ? (
                                                <span>
                                                    <span className="font-medium text-zinc-500">{labels.author}: </span>
                                                    {item.author}
                                                </span>
                                            ) : null}
                                        </div>
                                        <span className="mt-2 text-sm font-semibold text-[#4b1f6f] group-hover:underline">
                                            {labels.open}
                                        </span>
                                    </div>
                                </button>
                            </li>
                        );
                    })}
                </ul>
            )}

            <Modal show={!!activeItem} onClose={closeDetail} maxWidth="4xl">
                {activeItem ? (
                    <div className="max-h-[90vh] overflow-y-auto">
                        <div className="flex items-start justify-between gap-4 border-b border-zinc-100 px-4 py-3 sm:px-6">
                            <div>
                                <h2 className="text-xl font-bold text-zinc-900 sm:text-2xl">{activeItem.name}</h2>
                                <div className="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-zinc-600">
                                    {activeItem.category?.label ? (
                                        <span>
                                            <span className="font-medium text-zinc-500">{labels.category}: </span>
                                            {activeItem.category.label}
                                        </span>
                                    ) : null}
                                    {activeItem.year ? (
                                        <span>
                                            <span className="font-medium text-zinc-500">{labels.year}: </span>
                                            {activeItem.year}
                                        </span>
                                    ) : null}
                                    {activeItem.author ? (
                                        <span>
                                            <span className="font-medium text-zinc-500">{labels.author}: </span>
                                            {activeItem.author}
                                        </span>
                                    ) : null}
                                </div>
                            </div>
                            <button
                                type="button"
                                onClick={closeDetail}
                                className="rounded-full p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-800"
                                aria-label={labels.close}
                            >
                                <X size={22} />
                            </button>
                        </div>

                        <div className="px-4 py-4 sm:px-6">
                            {hasGallery ? (
                                <div className="mb-6">
                                    <p className="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.gallery}</p>
                                    <div className="relative overflow-hidden rounded-xl bg-zinc-100">
                                        <img
                                            src={mainSrc}
                                            alt=""
                                            className="max-h-[min(55vh,480px)] w-full object-contain"
                                        />
                                        {gallery.length > 1 ? (
                                            <>
                                                <button
                                                    type="button"
                                                    onClick={() => stepGallery(-1)}
                                                    className="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70"
                                                    aria-label={labels.prev}
                                                >
                                                    <ChevronLeft size={22} />
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => stepGallery(1)}
                                                    className="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-2 text-white hover:bg-black/70"
                                                    aria-label={labels.next}
                                                >
                                                    <ChevronRight size={22} />
                                                </button>
                                                <div className="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1.5 rounded-full bg-black/45 px-3 py-1 text-xs text-white">
                                                    {galleryIndex + 1} / {gallery.length}
                                                </div>
                                            </>
                                        ) : null}
                                    </div>
                                    {gallery.length > 1 ? (
                                        <div className="mt-3 flex gap-2 overflow-x-auto pb-1">
                                            {gallery.map((src, idx) => (
                                                <button
                                                    key={src}
                                                    type="button"
                                                    onClick={() => setGalleryIndex(idx)}
                                                    className={`h-14 w-14 shrink-0 overflow-hidden rounded-lg border-2 ${
                                                        idx === galleryIndex ? 'border-[#4b1f6f]' : 'border-transparent opacity-70'
                                                    }`}
                                                >
                                                    <img src={src} alt="" className="h-full w-full object-cover" />
                                                </button>
                                            ))}
                                        </div>
                                    ) : null}
                                </div>
                            ) : null}

                            <RichHtmlArticle html={activeItem.description_html} locale={locale} />
                        </div>
                    </div>
                ) : null}
            </Modal>
        </div>
    );
}
