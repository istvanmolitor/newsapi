<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('author')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required(),
                Forms\Components\TextInput::make('hash')
                    ->maxLength(255),
                Forms\Components\Select::make('portal_id')
                    ->relationship('portal', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->seconds(false),
                Forms\Components\DateTimePicker::make('scraped_at')
                    ->seconds(false),
                Forms\Components\Textarea::make('lead')
                    ->rows(3),
                Forms\Components\TextInput::make('list_image_src')
                    ->label('List image URL')
                    ->maxLength(1024),
                Forms\Components\TextInput::make('main_image_src')
                    ->label('Main image URL')
                    ->maxLength(1024),
                Forms\Components\TextInput::make('main_image_alt')
                    ->maxLength(255),
                Forms\Components\TextInput::make('main_image_author')
                    ->maxLength(255),
                Forms\Components\Select::make('collections')
                    ->label('Gyűjtemények')
                    ->relationship('collections', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('keywords')
                    ->label('Kulcsszavak')
                    ->relationship('keywords', 'keyword')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('keyword')
                            ->label('Kulcsszó')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50)->sortable(),
                Tables\Columns\TextColumn::make('portal.name')->label('Portal')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('portal_id')
                    ->relationship('portal', 'name')
                    ->label('Portal'),
                Tables\Filters\Filter::make('has_images')
                    ->label('Has images')
                    ->query(fn(Builder $q) => $q->whereNotNull('main_image_src')->orWhereNotNull('list_image_src')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // You can add relation managers later for content elements, keywords, collections
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
