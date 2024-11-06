<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use League\Csv\Writer;
use SplTempFileObject;
use Actions\BulkAction;
use App\Models\Etudiant;
use Filament\Forms\Form;
use App\Models\Formation;
use Filament\Tables\Table;
use App\Exports\EtudiantsExport;
use Filament\Resources\Resource;

use Filament\Forms\Components\Split;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Notifications\Collection;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EtudiantResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtudiantResource\RelationManagers;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Information Eudiantt')
                    ->icon('heroicon-m-identification')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([

                            Split::make([
                                Section::make([

                                    Forms\Components\TextInput::make('nom')
                                    ->required(),
                                    Forms\Components\TextInput::make('prenom')
                                        ->required(),
                                    Forms\Components\DatePicker::make('date_naissance')
                                        ->required(),
                                    Forms\Components\TextInput::make('email')
                                        ->required()
                                        ->email()
                                        ,


                                ]),
                                Section::make([
                                    Forms\Components\TextInput::make('telephone')
                                        ->nullable(),
                                    Forms\Components\TextInput::make('adresse')
                                        ->nullable(),
                                    Forms\Components\TextInput::make('niveau_etude')
                                        ->nullable(),
                                    Forms\Components\TextInput::make('annee_academique')
                                        ->required(),

                                ]),
                                Section::make([
                                    Forms\Components\Select::make('filliere_id')
                                    ->relationship('filliere', 'nom')
                                    ->required(),
                                Forms\Components\Select::make('ecole_id')
                                    ->relationship('ecole', 'nom')
                                    ->required(),
                                Forms\Components\FileUpload::make('photo')
                                    ->nullable()
                                    ->disk('public')
                                    ->directory('photos')
                                    ->preserveFilenames()
                                    ->maxSize(1024),

                                 ])
                            ])
                            ->columnSpan('full'),

                        ])
                        ->columns(2),
                    Wizard\Step::make('Information Formation & Paiement')
                        ->icon('heroicon-m-queue-list')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([

                            Fieldset::make('Choisir la formation ')
                                ->schema([

                                    static::getFormationEtudiantsRepeater(),

                                ])
                                ->columns(3),



                        ])
                        ->columns(1),

                    ])
                    ->columnSpan('full'),

                 // Déplace extraAttributes ici
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom'),
                Tables\Columns\TextColumn::make('prenom'),
                Tables\Columns\TextColumn::make('date_naissance'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('telephone'),
                Tables\Columns\TextColumn::make('adresse'),
                Tables\Columns\TextColumn::make('niveau_etude'),
                Tables\Columns\TextColumn::make('annee_academique'),
                Tables\Columns\TextColumn::make('filliere.nom')->label('Filière'),
                Tables\Columns\TextColumn::make('ecole.nom')->label('École'),
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
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }

    public static function getFormationEtudiantsRepeater(): Repeater
    {
        return Repeater::make('FormationEtudiant')
            ->relationship()
            ->schema([
                // Sélection de la formation
                Forms\Components\Select::make('formation_id')
                    ->label('Choisir une Formation')
                    ->options(Formation::all()->pluck('nom', 'id'))
                    ->reactive()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $formation = Formation::find($state);

                        if ($formation) {
                            $set('description', $formation->description);
                            $set('duree', $formation->duree);
                            $set('prix_a_paye', $formation->cout);
                            $set('montant_reste', $formation->cout);

                            if ($formation->formateur) {
                                $set('formateur_nom', $formation->formateur->nom . ' ' . $formation->formateur->prenom);
                                $set('formateur_specialite', $formation->formateur->specialite);
                                $set('formateur_experience', $formation->formateur->experience);
                                $set('ecole_nom', $formation->formateur->ecole->nom);
                            }
                        }
                    })
                    ->columnSpan(3), // 1er champ

                // Description de la formation
                Forms\Components\Textarea::make('description')
                    ->label('Description de la Formation')
                    ->disabled()
                    ->columnSpan(3), // 2e champ

                // Durée de la formation
                Forms\Components\TextInput::make('duree')
                    ->label('Durée (mois)')
                    ->numeric()
                    ->disabled()
                    ->columnSpan(3), // 3e champ

                // Prix à payer
                Forms\Components\TextInput::make('prix_a_paye')
                    ->label('Prix à Payer')
                    ->numeric()

                    ->columnSpan(3), // 4e champ

                // Montant payé
                Forms\Components\TextInput::make('montant')
                    ->label('Montant Payé')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $prixAPayer = $get('prix_a_paye');
                        if ($prixAPayer) {
                            $montantReste = $prixAPayer - $state;
                            $set('montant_reste', $montantReste);

                            if ($montantReste <= 0) {
                                $set('status', 'Payé');
                                $set('montant_reste', 0);
                            } else {
                                $set('status', 'En attente');
                            }
                        }
                    })
                    ->columnSpan(3), // 1er champ de la 2e ligne

                // Montant restant
                Forms\Components\TextInput::make('montant_reste')
                    ->label('Montant Restant')
                    ->numeric()

                    ->columnSpan(3), // 2e champ de la 2e ligne

                // Nom du formateur
                Forms\Components\TextInput::make('formateur_nom')
                    ->label('Formateur')
                    ->disabled()
                    ->columnSpan(3), // 3e champ de la 2e ligne

                // Spécialité du formateur
                Forms\Components\TextInput::make('formateur_specialite')
                    ->label('Spécialité Formateur')
                    ->disabled()
                    ->columnSpan(3), // 4e champ de la 2e ligne

                // Année d'expérience du formateur
                Forms\Components\TextInput::make('formateur_experience')
                    ->label('Expérience (années)')
                    ->disabled()
                    ->columnSpan(3), // 1er champ de la 3e ligne

                // École du formateur
                Forms\Components\TextInput::make('ecole_nom')
                    ->label('École')
                    ->disabled()
                    ->columnSpan(3), // 2e champ de la 3e ligne

                // Moyen de paiement
                Forms\Components\Select::make('moyen_paiement')
                    ->label('Moyen de Paiement')
                    ->options([
                        'Carte de crédit' => 'Carte de crédit',
                        'Mobile Money' => 'Mobile Money',
                        'Virement Bancaire' => 'Virement Bancaire',
                        'Espèces' => 'Espèces',
                    ])
                    ->required()
                    ->columnSpan(3), // 3e champ de la 3e ligne

                // Statut du paiement
                Forms\Components\Select::make('status')
                    ->label('Statut du paiement')
                    ->options([
                        'En attente' => 'En attente',
                        'Payé' => 'Payé',
                        'Échoué' => 'Échoué',
                    ])

                    ->default('En attente')
                    ->columnSpan(3), // 4e champ de la 3e ligne

                // Date du paiement
                Forms\Components\DatePicker::make('date')
                    ->label('Date de paiement')
                    ->default(now())

                    ->required()
                    ->columnSpan(3), // 1er champ de la 4e ligne
            ])
            ->defaultItems(1)
            ->columns(12)
            ->hiddenLabel()
            ->columnSpan('full')
            ->required();
    }



}