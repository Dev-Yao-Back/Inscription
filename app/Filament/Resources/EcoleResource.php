<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ecole;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EcoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EcoleResource\RelationManagers;
use App\Models\Formation;

class EcoleResource extends Resource
{
    protected static ?string $model = Ecole::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type_etablissement')
                    ->options([
                        'Université' => 'Université',
                        'École' => 'École',
                        'Institut' => 'Institut',
                        'Centre de Formation' => 'Centre de Formation',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('adresse')
                    ->required(),

                Forms\Components\TextInput::make('telephone')
                    ->nullable()
                    ->tel(),

                Forms\Components\TextInput::make('email')
                    ->nullable()
                    ->email(),

                Forms\Components\TextInput::make('site_web')
                    ->nullable()
                    ->url(),

                Forms\Components\TextInput::make('responsable')
                    ->nullable(),

                Forms\Components\TextInput::make('annee_fondation')
                    ->nullable()
                    ->numeric(),

                Forms\Components\TextInput::make('nombre_etudiants')
                    ->default(0)
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('nombre_formations')
                    ->default(0)
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'actif' => 'Actif',
                        'inactif' => 'Inactif',
                    ])
                    ->default('actif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom'),
                Tables\Columns\TextColumn::make('type_etablissement'),
                Tables\Columns\TextColumn::make('adresse'),
                Tables\Columns\TextColumn::make('telephone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('site_web'),
                Tables\Columns\TextColumn::make('responsable'),
                Tables\Columns\TextColumn::make('annee_fondation'),
                Tables\Columns\TextColumn::make('nombre_etudiants'),
                Tables\Columns\TextColumn::make('nombre_formations'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Date de mise à jour'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'actif' => 'Actif',
                        'inactif' => 'Inactif',
                    ]),
                SelectFilter::make('type_etablissement')
                    ->options([
                        'Université' => 'Université',
                        'École' => 'École',
                        'Institut' => 'Institut',
                        'Centre de Formation' => 'Centre de Formation',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                     // Ajouter une action personnalisée pour activer/désactiver plusieurs écoles
                Tables\Actions\Action::make('toggleStatus')
                ->label('Activer/Désactiver')
                ->action(function (array $records) {
                    foreach ($records as $record) {
                        $record->update([
                            'status' => $record->status === 'actif' ? 'inactif' : 'actif',
                        ]);
                    }
                }),
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
            'index' => Pages\ListEcoles::route('/'),
            'create' => Pages\CreateEcole::route('/create'),
            'edit' => Pages\EditEcole::route('/{record}/edit'),
        ];
    }

   
}