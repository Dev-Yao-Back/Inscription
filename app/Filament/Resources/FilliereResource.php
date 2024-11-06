<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FilliereResource\Pages;
use App\Filament\Resources\FilliereResource\RelationManagers;
use App\Models\Filliere;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FilliereResource extends Resource
{
    protected static ?string $model = Filliere::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                ->required(),
                Forms\Components\RichEditor::make('description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListFillieres::route('/'),
            'create' => Pages\CreateFilliere::route('/create'),
            'edit' => Pages\EditFilliere::route('/{record}/edit'),
        ];
    }
}