<?php

namespace App\Filament\Resources\Member;

use App\Models\Member;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\Member\MemberResource\Pages\EditMember;
use App\Filament\Resources\Member\MemberResource\Pages\ListMembers;
use App\Filament\Resources\Member\MemberResource\Pages\CreateMember;
use BezhanSalleh\FilamentShield\Support\Utils;
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

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Membres';

    public static ?string $slug = 'membres';

    protected static ?string $recordTitleAttribute = 'code';

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/créer'),
            'edit' => EditMember::route('/{record}/modifier'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
