<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(Request $request, string $locale): Response
    {
        $search = trim((string) $request->string('search', ''));

        $paginator = News::query()
            ->where('is_published', true)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested
                        ->where('title->es', 'like', "%{$search}%")
                        ->orWhere('title->en', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        /** @var LengthAwarePaginator $paginator */
        $news = $paginator->through(function (News $item) use ($locale): array {
            $title = $this->getTranslated($item->title, $locale);
            $content = $this->getTranslated($item->content, $locale);
            $plainContent = $this->extractPlainText($content);

            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $title,
                'excerpt' => Str::limit($plainContent, 120),
                'image_path' => $item->image_path,
                'created_at' => $item->created_at?->toIso8601String(),
            ];
        });

        return Inertia::render('News/Index', [
            'news' => $news,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $news = News::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $title = $this->getTranslated($news->title, $locale);
        $content = $this->getTranslated($news->content, $locale);
        $description = Str::limit($this->extractPlainText($content), 160);
        $relatedTopics = $this->getTranslated($news->related_topics, $locale);

        return Inertia::render('News/Show', [
            'news' => [
                'id' => $news->id,
                'slug' => $news->slug,
                'title' => $title,
                'content' => $content,
                'description' => $description,
                'image_path' => $news->image_path,
                'created_at' => $news->created_at?->toIso8601String(),
                'related_topics' => is_array($relatedTopics) ? $relatedTopics : [],
            ],
        ]);
    }

    private function getTranslated(array | null $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        return $value[$locale] ?? $value['es'] ?? reset($value);
    }

    private function extractPlainText(mixed $content): string
    {
        if (is_string($content)) {
            return trim(strip_tags($content));
        }

        if (! is_array($content)) {
            return '';
        }

        $buffer = '';

        $walk = function (array $node) use (&$walk, &$buffer): void {
            if (($node['type'] ?? null) === 'text' && isset($node['text'])) {
                $buffer .= $node['text'] . ' ';
            }

            foreach (($node['content'] ?? []) as $child) {
                if (is_array($child)) {
                    $walk($child);
                }
            }
        };

        $walk($content);

        return trim(preg_replace('/\s+/', ' ', $buffer) ?? '');
    }
}
