<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\FormationEtudiant;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Collection;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormationEtudiantResource\Pages;
use App\Filament\Resources\FormationEtudiantResource\RelationManagers;

class FormationEtudiantResource extends Resource
{
    protected static ?string $model = FormationEtudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Paiement';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('etudiant.nom')->label('Etudiant'),
                Tables\Columns\TextColumn::make('formation.nom')->label('Formation'),
                Tables\Columns\TextColumn::make('prix_a_paye')->label('Prix à payer'),
                Tables\Columns\TextColumn::make('montant')->label('Montant Payé'),
                Tables\Columns\TextColumn::make('montant_reste')->label('Montant Restant'),
                Tables\Columns\TextColumn::make('date')->label('Date de paiement'),
                Tables\Columns\TextColumn::make('moyen_paiement')->label('Moyen de paiement'),
                Tables\Columns\TextColumn::make('status')->label('Statut'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('valider')
                ->action(function (FormationEtudiant $record) {
                    if ($record->montant_reste == 0) {
                        $record->validation = 'validé';
                    } else {
                        $record->validation = 'non validé';
                    }
                    $record->save();
                })
                ->color('success')
                ->icon('heroicon-o-cursor-arrow-rays')
                ->requiresConfirmation()
                ->hidden(fn (FormationEtudiant $record): bool => $record->validation === 'validé'),

            // Action pour rejeter
            Action::make('rejeter')
                ->action(function (FormationEtudiant $record) {
                    $record->validation = 'rejeté';
                    $record->save();

                    // Supprimer le paiement associé
                    $record->delete();  // Supprime l'enregistrement FormationEtudiant
                })
                ->color('danger')
                ->icon('heroicon-o-cursor-arrow-rays')
                ->requiresConfirmation()
                ->modalHeading('Rejeter l\'inscription')
                ->modalSubheading('Êtes-vous sûr de vouloir rejeter cette inscription et supprimer le paiement associé ?')
                ->hidden(fn (FormationEtudiant $record): bool => $record->validation === 'validé'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                     // Actions en masse si nécessaire
            BulkAction::make('validerEnMasse')
            ->action(function (Collection $records) {
                foreach ($records as $record) {
                    if ($record->montant_reste == 0) {
                        $record->validation = 'validé';
                    } else {
                        $record->validation = 'non validé';
                    }
                    $record->save();
                }
            })
            ->color('success')
            ->icon('heroicon-o-check'),

        BulkAction::make('rejeterEnMasse')
            ->action(function (Collection $records) {
                foreach ($records as $record) {
                    $record->validation = 'rejeté';
                    $record->save();
                }
            })
            ->color('danger')
            ->icon('heroicon-o-cursor-arrow-rays'),
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
            'index' => Pages\ListFormationEtudiants::route('/'),
            'create' => Pages\CreateFormationEtudiant::route('/create'),
            'edit' => Pages\EditFormationEtudiant::route('/{record}/edit'),
        ];
    }
}