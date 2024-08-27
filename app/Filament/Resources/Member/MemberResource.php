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

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Adhérents';

    public static ?string $slug = 'membres';

    protected static string $relationship = 'documents';

    public static function getGloballySearchableAttributes(): array
    {
        return ['lastname', 'firstname', 'email'];
    }

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
                                ->relationship('documents', 'label')
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
            ->striped()
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
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de modification</span>'))
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
                    ->getStateUsing(function (Member $record) {
                        return $record->hasAllRequiredDocuments();
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
                        return 'Suppression de ' . $record->lastname . ' ' . $record->lastname;
                    })
                    ->modalDescription("Êtes-vous sur de vouloir supprimer cet adhérent ?")
                    ->successNotificationTitle(function ($record) {
                        return 'L\'adhérent ' . $record->lastname . ' ' . $record->firstname . ' a été supprimé';
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/créer'),
            'view' => ViewMember::route('/{record}'),
            'edit' => EditMember::route('/{record}/modifier'),
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
        ]);
    }
}
