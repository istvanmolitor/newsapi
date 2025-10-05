<?php

namespace App\Filament\Resources\RssFeedResource\Pages;

use App\Filament\Resources\RssFeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRssFeeds extends ListRecords
{
    protected static string $resource = RssFeedResource::class;

    protected static ?string $title = 'RSS csatornák';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Új csatorna'),
        ];
    }
}
