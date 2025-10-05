<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RssFeedResource\Pages;
use App\Filament\Resources\RssFeedResource\RelationManagers;
use App\Models\RssFeed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RssFeedResource extends Resource
{
    protected static ?string $model = \Molitor\RssWatcher\Models\RssFeed::class;

    protected static ?string $navigationLabel = 'RSS csatornák';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('enabled')
                    ->label('Engedélyezve')
                    ->default(true),
                Forms\Components\TextInput::make('name')
                    ->label('Név')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->label('RSS url')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\CheckboxColumn::make('enabled')->label('Engedélyezve'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('url'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRssFeeds::route('/'),
            'create' => Pages\CreateRssFeed::route('/create'),
            'edit' => Pages\EditRssFeed::route('/{record}/edit'),
        ];
    }
}
