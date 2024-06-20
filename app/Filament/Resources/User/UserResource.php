<?php

namespace App\Filament\Resources\User;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\User\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\User\UserResource\Pages\EditUser;
use App\Filament\Resources\User\UserResource\Pages\ListUsers;
use App\Filament\Resources\User\UserResource\Pages\CreateUser;
use App\Filament\Resources\User\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $modelLabel = 'Membres';

    public static ?string $slug = 'membres';

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

                    TextInput::make('password')
                    ->label(new HtmlString('<span class="text-gray-400">Mot de passe</span>'))
                    ->password()
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (Page $livewire) => ($livewire instanceof CreateUser)),
                    Fieldset::make()
                        ->schema([
                            Select::make('roles')
                                ->label(new HtmlString('<span class="text-gray-400">Rôle</span>'))
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload(),
                        ])
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
                TextColumn::make('roles.name')
                    ->label(new HtmlString('<span class="text-gray-400">Rôle</span>'))
                    ->badge()
                    ->size(TextColumnSize::Small)
                    ->color(fn (string $state): string => match ($state) {
                        'Manager' => 'warning',
                        'Membre' => 'danger',
                        'Super Admin' => 'success',
                }),
                TextColumn::make('created_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de création</span>'))
                    ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de modification</span>'))
                    ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading(function ($record) {
                        return 'Suppression de ' . $record->lastname ;
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/creer'),
            'edit' => EditUser::route('/{record}/modifier'),
        ];
    }
}
