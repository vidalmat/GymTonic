<?php

namespace App\Filament\Resources\LessonResource\Pages\Calendar;

use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Lesson\LessonResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class Calendar extends ManageRelatedRecords
{
    protected static string $resource = LessonResource::class;

    // protected static string $view = 'filament.resources.lesson-resource.pages.calendar.calendar';

    protected static string $relationship = 'lessons';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Fieldset::make('')
                ->label(new HtmlString('<span class="text-gray-400">Ajouter un cours</span>'))
                ->schema([
                    TextInput::make('type')
                        ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                        ->maxLength(255),

                    DateTimePicker::make('start')
                        ->label(new HtmlString('<span class="text-gray-400">Début</span>')),

                    DateTimePicker::make('end')
                        ->label(new HtmlString('<span class="text-gray-400">Fin</span>')),
                ]),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('type')
                ->label(new HtmlString('<span class="text-gray-400">Titre</span>'))
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
        ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                ->modalHeading(function ($record) {
                    return 'Suppression de ' . $record->type;
                })
                    ->modalDescription("Êtes-vous sur de vouloir supprimer ce créneau ?")
                    ->successNotificationTitle(function ($record) {
                        return 'Le créneau ' . $record->type . ' a été supprimé';
                    }),
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
