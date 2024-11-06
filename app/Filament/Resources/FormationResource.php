<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Formation;
use Filament\Tables\Table;
use Ramsey\Uuid\Type\Integer;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormationResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormationResource\RelationManagers;

class FormationResource extends Resource
{
    protected static ?string $model = Formation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nom')
                ->required()
                ->label('Nom'),
            Textarea::make('description')
                ->label('Description')
                ->nullable(),
            TextInput::make('duree')
                ->required()
                ->label('Durée (en heures)'),
            TextInput::make('cout')
                ->required()
                ->label('Coût'),
            Select::make('formateur_id')
                ->relationship('formateur', 'nom')
                ->label('Formateur')
                ->required(),
            TextInput::make('annee_academique')
                ->required()
                ->label('Année Académique'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                ->label('Nom')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('description')
                ->label('Description'),
            Tables\Columns\TextColumn::make('duree')
                ->label('Durée')
                ->sortable(),
            Tables\Columns\TextColumn::make('cout')
                ->label('Coût'),
            Tables\Columns\TextColumn::make('formateur.nom')
                ->label('Formateur'),
            Tables\Columns\TextColumn::make('annee_academique')
                ->label('Année Académique'),
            ])
            ->filters([
                Filter::make('nom')
                ->label('Filtrer par nom')
                ->query(fn ($query, $value) => $query->where('nom', 'like', "%{$value}%")),
            Filter::make('cout')
                ->label('Filtrer par coût')
                ->query(fn ($query, $value) => $query->where('cout', $value)),
            Filter::make('duree')
                ->label('Filtrer par durée')
                ->query(fn ($query, $value) => $query->where('duree', $value)),
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
            'index' => Pages\ListFormations::route('/'),
            'create' => Pages\CreateFormation::route('/create'),
            'edit' => Pages\EditFormation::route('/{record}/edit'),
        ];
    }
}
