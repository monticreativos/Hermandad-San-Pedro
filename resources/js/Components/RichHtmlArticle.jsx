export default function RichHtmlArticle({ html, locale }) {
    if (typeof html === 'string' && html.trim() !== '') {
        return (
            <div
                className={[
                    'rich-html',
                    // Base: tipografía legible (requiere @tailwindcss/typography)
                    'prose prose-zinc max-w-none prose-lg',
                    // Títulos: jerarquía clara y color de marca
                    'prose-headings:font-serif prose-headings:font-semibold prose-headings:text-[#3b1758]',
                    'prose-headings:tracking-tight',
                    'prose-h2:mt-12 prose-h2:mb-4 prose-h2:scroll-mt-24',
                    'prose-h2:border-b prose-h2:border-[#4b1f6f]/20 prose-h2:pb-3',
                    'prose-h2:text-xl sm:prose-h2:text-2xl',
                    'prose-h3:mt-10 prose-h3:mb-3 prose-h3:text-lg sm:prose-h3:text-xl',
                    'prose-h4:mt-8 prose-h4:mb-2',
                    // Primer h2 sin margen superior extra (debajo del hero / título de página)
                    '[&>h2:first-child]:mt-0',
                    // Párrafos: interlineado y separación entre bloques
                    'prose-p:my-0 prose-p:mb-6 prose-p:text-zinc-700 prose-p:leading-[1.8]',
                    'prose-p:text-[1.05rem]',
                    // Último párrafo sin margen inferior huérfano
                    '[&>p:last-child]:mb-0',
                    'prose-strong:font-semibold prose-strong:text-zinc-900',
                    'prose-a:text-[#4b1f6f] prose-a:font-medium prose-a:no-underline hover:prose-a:underline',
                    'prose-ul:my-6 prose-ol:my-6 prose-li:my-1.5 prose-li:leading-relaxed',
                    'prose-li:marker:text-[#4b1f6f]',
                    'prose-blockquote:border-l-[#4b1f6f] prose-blockquote:text-zinc-600',
                    // Imágenes: por defecto no más anchas que el texto; el escudo se acota en app.css
                    'prose-img:max-w-full prose-img:rounded-lg prose-img:h-auto',
                ].join(' ')}
                // eslint-disable-next-line react/no-danger -- HTML generado en servidor con sanitize
                dangerouslySetInnerHTML={{ __html: html }}
            />
        );
    }

    return (
        <p className="text-zinc-500">
            {locale === 'es' ? 'Contenido no disponible.' : 'Content not available.'}
        </p>
    );
}
