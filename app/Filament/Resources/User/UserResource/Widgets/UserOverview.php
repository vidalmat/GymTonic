<?php

namespace App\Filament\Resources\User\UserResource\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class UserOverview extends ChartWidget
{
    protected static ?string $heading = 'Membres';

    protected function getData(): array
    {
        Carbon::setLocale('fr');

        $data = Trend::model(User::class)
        ->between(
            start: now()->subYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Membres ajoutés',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
