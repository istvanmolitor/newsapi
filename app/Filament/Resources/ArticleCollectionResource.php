<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleCollectionResource\Pages;
use App\Models\ArticleCollection;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleCollectionResource extends Resource
{
    protected static ?string $model = ArticleCollection::class;

    //protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Tartalom';

    protected static ?string $modelLabel = 'Gyűjtemény';

    protected static ?string $pluralModelLabel = 'Gyűjtemények';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Cím')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('lead')
                    ->label('Leírás')
                    ->rows(3)
                    ->maxLength(2000),
                Forms\Components\Toggle::make('is_same')
                    ->label('Azonos témájú cikkek gyűjteménye')
                    ->default(false),
                Forms\Components\Select::make('articles')
                    ->label('Cikkek')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->relationship('articles', 'title')
                    ->helperText('Válaszd ki, mely cikkek tartozzanak a gyűjteményhez.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Cím')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lead')->label('Leírás')->limit(50),
                Tables\Columns\IconColumn::make('is_same')->boolean()->label('Azonos témájú?'),
                Tables\Columns\TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Cikkek száma'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('Y.m.d H:i')->label('Létrehozva')->sortable(),
            ])
            ->filters([
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleCollections::route('/'),
            'create' => Pages\CreateArticleCollection::route('/create'),
            'edit' => Pages\EditArticleCollection::route('/{record}/edit'),
        ];
    }
}
