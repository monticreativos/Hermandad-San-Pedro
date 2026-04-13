<?php

namespace App\Filament\Resources\DocumentCategories\Pages;

use App\Filament\Resources\DocumentCategories\DocumentCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditDocumentCategory extends EditRecord
{
    protected static string $resource = DocumentCategoryResource::class;
}
