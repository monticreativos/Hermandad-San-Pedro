<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Inertia\Inertia;
use Inertia\Response;

class DocumentationController extends Controller
{
    public function index(): Response
    {
        $categories = DocumentCategory::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with([
                'documents' => fn ($q) => $q
                    ->where('is_published', true)
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->get();

        return Inertia::render('Documentation/Index', [
            'documentCategories' => $categories,
        ]);
    }
}
