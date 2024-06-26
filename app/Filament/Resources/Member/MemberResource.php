<?php

namespace App\Filament\Resources\Member;

use App\Models\Member;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\Member\MemberResource\Pages\EditMember;
use App\Filament\Resources\Member\MemberResource\Pages\ViewMember;
use App\Filament\Resources\Member\MemberResource\Pages\ListMembers;
use App\Filament\Resources\Member\MemberResource\Pages\CreateMember;
use App\Filament\Resources\Member\MemberResource\Pages\MemberDocument;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Adhérents';

    public static ?string $slug = 'membres';

    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'code';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('lastname')
                ->label(new HtmlString('<span class="text-gray-400">Ajouter un utilisateur</span>'))
                ->schema([
                    TextInput::make('lastname')
                        ->label(new HtmlString('<span class="text-gray-400">Nom</span>'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('firstname')
                        ->label(new HtmlString('<span class="text-gray-400">Prénom</span>'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label(new HtmlString('<span class="text-gray-400">Email</span>'))
                        ->email()
                        ->required()
                        ->maxLength(255),
                    ]),
                    Fieldset::make('Documents')
                        ->schema([
                            CheckboxList::make('documents')
                                ->label('Documents')
                                // ->multiple()
                                ->relationship('documents', 'label')
                                // ->preload()
                                ->options(function () {
                                    return Document::all()->pluck('label', 'id');
                                })
                                ->gridDirection('row'),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lastname')
                    ->label(new HtmlString('<span class="text-gray-400">Nom</span>'))
                    ->searchable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('firstname')
                    ->label(new HtmlString('<span class="text-gray-400">Prénom</span>'))
                    ->searchable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('email')
                    ->label(new HtmlString('<span class="text-gray-400">Email</span>'))
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
                    ->color(function (Member $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
                IconColumn::make('documents.label')
                    ->label(new HtmlString('<span class="text-gray-400">Documents</span>'))
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(['class' => 'flex justify-center'])
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
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
                        return 'L\'utilisateur ' . $record->lastname . ' a été supprimé';
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {

    //     return $infolist
    //         ->schema([
    //             Split::make([
    //                 FieldsetMember::make('')
    //                     ->columns(5)
    //                     ->schema([
    //                         TextEntry::make('lastname')
    //                             ->label(new HtmlString('<span class="text-gray-400">Nom</span>')),
    //                         TextEntry::make('firstname')
    //                             ->label(new HtmlString('<span class="text-gray-400">Prénom</span>')),
    //                         TextEntry::make('email')
    //                             ->label(new HtmlString('<span class="text-gray-400">Email</span>')),
    //                         // IconEntry::make('email')
    //                         //     ->label(new HtmlString('<span class="text-gray-400">Documents</span>'))
    //                         //     ->boolean()
    //                         //         ->trueColor('success')
    //                         //         ->falseColor('danger'),
    //                     ]),
    //             ]),
    //             Split::make([
    //                 FieldsetMember::make('Document(s)')
    //                     ->columns(5)
    //                     ->schema([
    //                         IconEntry::make('documents.label')
    //                             ->label('Charte de l\'adhérent')
    //                             ->boolean(function ($record) {
    //                     foreach ($record->documents as $document) {
    //                         if ($document->label === 'Charte de l\'adhérent'
    //                         ) {
    //                             return true;
    //                         }
    //                     }
    //                     return false;
    //                             })
    //                             // ->trueColor('success')
    //                             ->falseColor('danger'),
    //                         IconEntry::make('documents.label')
    //                             ->label('Fiche d\'inscription')
    //                             ->boolean(function ($record) {
    //                                 return $record->documents->contains('label', "Charte de l'adhérent");
    //                             })
    //                             ->trueColor('success')
    //                             ->falseColor('danger'),
    //                         IconEntry::make('documents.cover_letter')
    //                             ->label('Lettre d\'accompagnement')
    //                             ->boolean(function ($record) {
    //                                 return $record->documents->contains('label', 'Charte de l\'adhérent');
    //                             })
    //                             ->boolean(fn ($record) => $record->documents->contains('label', 'Lettre d\'accompagnement'))
    //                             ->trueColor('success')
    //                             ->falseColor('danger'),
    //                         IconEntry::make('documents.partner_document')
    //                             ->label('Documents partenaires')
    //                             ->boolean(fn ($record) => $record->documents->contains('label', 'Documents partenaires'))
    //                             ->trueColor('success')
    //                             ->falseColor('danger'),
    //                         IconEntry::make('documents.medical_certificat')
    //                             ->label('Certificat médical')
    //                             ->boolean(fn ($record) => $record->documents->contains('label', 'Certificat médical'))
    //                             ->trueColor('success')
    //                             ->falseColor('danger'),
    //                     ]),
    //                 ]),
    //         ])->columns(1);
    // }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/créer'),
            'view' => ViewMember::route('/{record}'),
            'edit' => EditMember::route('/{record}/modifier'),
            'document' => MemberDocument::route('/{record}/document'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewMember::class,
            // MemberDocument::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            MemberDocument::class,
        ];
    }
}
