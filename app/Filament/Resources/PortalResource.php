<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortalResource\Pages;
use App\Models\Portal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PortalResource extends Resource
{
    protected static ?string $model = Portal::class;

    protected static ?string $navigationGroup = 'Tartalom';

    protected static ?string $modelLabel = 'Portal';

    protected static ?string $pluralModelLabel = 'Portalok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Név')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('domain')
                    ->label('Domain')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Pl.: example.hu vagy index.hu'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Név')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('domain')->label('Domain')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('articles_count')->counts('articles')->label('Cikkek száma'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortals::route('/'),
            'create' => Pages\CreatePortal::route('/create'),
            'edit' => Pages\EditPortal::route('/{record}/edit'),
        ];
    }
}
