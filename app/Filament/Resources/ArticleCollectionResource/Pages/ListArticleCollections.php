<?php

namespace App\Filament\Resources\ArticleCollectionResource\Pages;

use App\Filament\Resources\ArticleCollectionResource;
use Filament\Resources\Pages\ListRecords;

class ListArticleCollections extends ListRecords
{
    protected static string $resource = ArticleCollectionResource::class;
}
