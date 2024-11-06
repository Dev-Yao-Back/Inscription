<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Formateur;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormateurResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormateurResource\RelationManagers;

class FormateurResource extends Resource
{
    protected static ?string $model = Formateur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nom')
                    ->required()
                    ->label('Nom'),
                TextInput::make('prenom')
                    ->required()
                    ->label('Prénom'),
                DatePicker::make('date_naissance')
                    ->label('Date de naissance')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(Formateur::class, 'email', ignoreRecord: true)
                    ->label('Email'),
                TextInput::make('telephone')
                    ->tel()
                    ->label('Téléphone')
                    ->nullable(),
                Textarea::make('adresse')
                    ->label('Adresse')
                    ->nullable(),
                Textarea::make('specialite')
                    ->label('Spécialité')
                    ->nullable(),
                Textarea::make('experience')
                    ->label('Expérience')
                    ->nullable(),
                Select::make('ecole_id')
                    ->relationship('ecole', 'nom')
                    ->label('École')
                    ->required(),
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
                Tables\Columns\TextColumn::make('prenom')
                    ->label('Prénom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_naissance')
                    ->label('Date de naissance')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specialite')
                    ->label('Spécialité'),
                Tables\Columns\TextColumn::make('ecole.nom')
                    ->label('École de provenance'),
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
            'index' => Pages\ListFormateurs::route('/'),
            'create' => Pages\CreateFormateur::route('/create'),
            'edit' => Pages\EditFormateur::route('/{record}/edit'),
        ];
    }
}