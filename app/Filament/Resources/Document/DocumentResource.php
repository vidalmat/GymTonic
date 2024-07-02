<?php

namespace App\Filament\Resources\Document;

use App\Models\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\Document\DocumentResource\Pages\EditDocument;
use App\Filament\Resources\Document\DocumentResource\Pages\ListDocuments;
use App\Filament\Resources\Document\DocumentResource\Pages\CreateDocument;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-c-document-duplicate';

    protected static ?string $modelLabel = 'Documents';

    public static ?string $slug = 'documents';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('label')
                ->label(new HtmlString('<span class="text-gray-400">Ajouter un document</span>'))
                ->schema([
                    TextInput::make('label')
                    ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                    ->required()
                        ->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('label')
                    ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                    ->searchable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('created_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de création</span>'))
                    // ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de modification</span>'))
                    // ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small)
                    ->color(function (Document $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading(function ($record) {
                        return 'Suppression de ' . $record->lastname;
                    })
                    ->modalDescription("Êtes-vous sur de vouloir supprimer cet utilisateur ?")
                    ->successNotificationTitle(function ($record) {
                        return 'Le document ' . $record->label . ' a été supprimé';
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/créer'),
            'edit' => EditDocument::route('/{record}/modifier'),
        ];
    }
}
