import { usePage } from '@inertiajs/react';
import HomePanelBlocks, { HomePanelFooterButton } from './HomePanelBlocks';

const titleFromSettings = (obj, locale) =>
    obj?.[locale] ?? obj?.es ?? '';

export default function ChapelMassesPanel() {
    const { locale = 'es', translations = {}, webSettings } = usePage().props;
    const tr = (key, fallback) => translations[key] ?? fallback;

    const blocks = webSettings?.chapel_blocks;
    const hasBlocks = Array.isArray(blocks) && blocks.length > 0;

    const title =
        titleFromSettings(webSettings?.chapel_card_title, locale) ||
        tr('home.chapel.title', 'Capilla y misas');

    return (
        <div className="flex h-full w-full flex-col rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-sm sm:p-7 lg:p-8">
            <h2 className="text-lg font-bold uppercase tracking-[0.12em] text-[#4b1f6f] sm:text-xl">
                {title}
            </h2>

            <div className="mt-6 flex-1">
                {hasBlocks ? (
                    <HomePanelBlocks blocks={blocks} locale={locale} />
                ) : (
                    <div className="space-y-5 text-sm leading-relaxed text-zinc-600 sm:text-base">
                        <div className="space-y-2">
                            <p className="font-semibold text-[#4b1f6f]">
                                {tr('home.chapel.weekdaysIntro', 'De lunes a sabado:')}
                            </p>
                            <p>{tr('home.chapel.weekdaysChapel', '')}</p>
                            <p>{tr('home.chapel.weekdayMass', '')}</p>
                        </div>
                        <div className="space-y-2">
                            <p className="font-semibold text-[#4b1f6f]">
                                {tr('home.chapel.sundaysIntro', 'Domingos')}
                            </p>
                            <p>{tr('home.chapel.sundayChapel', '')}</p>
                            <p>{tr('home.chapel.sundayMass', '')}</p>
                        </div>
                        <p className="border-l-[3px] border-[#c9a227] bg-amber-50/50 py-2 pl-4 pr-2 text-zinc-700">
                            {tr('home.chapel.adoration', '')}
                        </p>
                    </div>
                )}
            </div>

            <HomePanelFooterButton
                footer={webSettings?.chapel_footer}
                locale={locale}
                fallbackLabel={tr('home.chapel.cta', 'Ver todos los cultos')}
                fallbackRouteName="agenda.index"
            />
        </div>
    );
}
