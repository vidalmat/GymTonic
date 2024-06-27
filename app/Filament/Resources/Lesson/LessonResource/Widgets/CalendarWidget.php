<?php

namespace App\Filament\Resources\Lesson\LessonResource\Widgets;

use App\Models\Task;
use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Saade\FilamentFullCalendar\Data\EventData;
use App\Filament\Resources\Lesson\LessonResource;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.resources.lesson.lesson-resource.widgets.calendar-widget';

    protected static string $relationship = 'users';

    public Model | string | null $model = Lesson::class;

    public function fetchEvents(array $fetchInfo): array
    {
        return Lesson::query()
            ->where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Lesson $task) => [
                    'id'    => $task->id,
                    'title' => $task->label,
                    'start' => $task->start,
                    'end'   => $task->end,
                    'url' => LessonResource::getUrl(name: 'view', parameters: ['record' => $task]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }

    // public function fetchEvents(array $fetchInfo): array
    // {
    //     return Lesson::all()
    //         ->map(function (Lesson $task) {
    //             // dd($task);
    //             return [
    //                 'id'    => $task->id,
    //                 'label' => $task->label,
    //                 'type' => $task->type,
    //                 'start' => $task->start,
    //                 'end'   => $task->end,
    //             ];
    //         })
    //         ->toArray();
    // }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('label')
                ->label(new HtmlString('<span class="text-gray-400">Libellé</span>')),
            TextInput::make('duration')
                ->label(new HtmlString('<span class="text-gray-400">Durée</span>')),
            // Fieldset::make()
            //     ->schema([
            //         Select::make('user_id')
            //             ->label(new HtmlString('<span class="text-gray-400">Professeur</span>'))
            //             ->searchable()
            //             ->relationship('users')
            //             ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->lastname}")
            //             ->preload(),
            //     ]),
            Grid::make()
                ->schema([
                    DateTimePicker::make('start')
                        ->label(new HtmlString('<span class="text-gray-400">Début</span>')),
                    DateTimePicker::make('end')
                        ->label(new HtmlString('<span class="text-gray-400">Fin</span>')),
                ])
        ->statePath('data'),
        ];
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (Lesson $record, Form $form, array $arguments) {
                        $form->fill([
                            'label' => $record->label,
                            // 'users.lastname' => $record->lastname,
                            'duration' => $record->duration,
                            'start' => $arguments['event']['start'] ?? $record->start,
                            'end' => $arguments['event']['end'] ?? $record->end
                        ]);
                    }
                ),
            DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()->mountUsing(
            function (Lesson $record, Form $form, array $arguments) {
                $form->fill([
                    'label' => $record->label,
                    'duration' => $record->duration,
                    'start' => $arguments['event']['start'] ?? $record->start,
                    'end' => $arguments['event']['end'] ?? $record->end
                ]);
            }
        );
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }

    public static function canView(): bool
    {
        return true;
    }

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }
}
