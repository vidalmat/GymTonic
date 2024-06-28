<?php

namespace App\Filament\Resources\Lesson;

use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\Lesson\LessonResource\Pages\EditLesson;
use App\Filament\Resources\Lesson\LessonResource\Pages\ViewLesson;
use App\Filament\Resources\Lesson\LessonResource\Pages\ListLessons;
use App\Filament\Resources\Lesson\LessonResource\Pages\CreateLesson;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $modelLabel = 'Cours';

    public static ?string $slug = 'cours';

    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'code';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                ->label(new HtmlString('<span class="text-gray-400">Ajouter un cours</span>'))
                ->schema([
                    TextInput::make('label')
                        ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                        ->maxLength(255),

                    TextInput::make('duration')
                        ->label(new HtmlString('<span class="text-gray-400">Durée</span>'))
                        ->maxLength(255),

                    Fieldset::make()
                        ->schema([
                            Select::make('user_id')
                                ->label(new HtmlString('<span class="text-gray-400">Professeur</span>'))
                                ->searchable()
                                ->relationship('users')
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->lastname}")
                                ->preload(),
                        ]),

                    DateTimePicker::make('start')
                        ->label(new HtmlString('<span class="text-gray-400">Début</span>'))
                        ->seconds(false)
                        ->native(false),

                    DateTimePicker::make('end')
                        ->label(new HtmlString('<span class="text-gray-400">Fin</span>'))
                        ->seconds(false)
                        ->native(false),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),

                TextColumn::make('users.lastname')
                    ->label(new HtmlString('<span class="text-gray-400">Professeur</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),

                TextColumn::make('duration')
                    ->label(new HtmlString('<span class="text-gray-400">Durée</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('start')
                    ->label(new HtmlString('<span class="text-gray-400">Début</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),

                TextColumn::make('end')
                    ->label(new HtmlString('<span class="text-gray-400">Fin</span>'))
                    ->searchable()
                    ->sortable()
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
                    ->color(function (Lesson $record) {
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
            'index' => ListLessons::route('/'),
            'create' => CreateLesson::route('/créer'),
            'view' => ViewLesson::route('/{record}'),
            'edit' => EditLesson::route('/{record}/modifier'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }
}
