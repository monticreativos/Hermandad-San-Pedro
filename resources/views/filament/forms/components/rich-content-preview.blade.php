@php
    $field = $previewField ?? null;
    $html = filled($field) ? \App\Support\RichContentPresenter::toHtml($get($field)) : '';
@endphp

<div
    class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-950/40"
>
    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
        Vista previa
    </p>

    @if ($html !== '')
        <div
            class="fi-prose prose prose-sm max-h-[32rem] max-w-none overflow-y-auto border-t border-gray-200 pt-4 dark:prose-invert dark:border-gray-600 prose-img:max-w-full prose-img:rounded-md prose-p:my-2"
        >
            {!! $html !!}
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-gray-500">
            Escribe o pega contenido para previsualizarlo en la web.
        </p>
    @endif
</div>
