<?php

namespace App\Filament\Resources\User;

use App\Models\User;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\User\UserResource\Pages\MailPage;
use App\Filament\Resources\User\UserResource\Pages\EditUser;
use App\Filament\Resources\User\UserResource\Pages\ListUsers;
use App\Filament\Resources\User\UserResource\Pages\CreateUser;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'Administrateur';

    public static ?string $slug = 'administrateurs';

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
            ->striped()
            ->columns([
                TextColumn::make('lastname')
                    ->label(new HtmlString('<span class="text-gray-400">Nom</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('firstname')
                    ->label(new HtmlString('<span class="text-gray-400">Prénom</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('email')
                    ->label(new HtmlString('<span class="text-gray-400">Email</span>'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Le texte a été copié')
                    ->copyMessageDuration(1500)
                    ->size(TextColumnSize::Small),
                TextColumn::make('roles.name')
                    ->label(new HtmlString('<span class="text-gray-400">Rôle</span>'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->size(TextColumnSize::Small)
                    ->color(fn (string $state): string => match ($state) {
                        'Manager' => 'warning',
                        'Professeur' => 'danger',
                        'Super Admin' => 'success',
                }),
                TextColumn::make('created_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de création</span>'))
                    // ->date('d-m-Y')
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de modification</span>'))
                    // ->date('d-m-Y')
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small)
                    ->color(function (User $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
            ])
            ->filters([
                SelectFilter::make('roles.name')
                    ->label('Rôle')
                    ->options([
                        '1' => 'Professeur',
                        '2' => 'Manager',
                        '3' => 'Super Admin',
                    ]),
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
                        return 'L\'utilisateur ' . $record->lastname . $record->firstname . ' a été supprimé';
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
            // 'mail' => MailPage::route('/email'),
            'edit' => EditUser::route('/{record}/modifier'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

}
