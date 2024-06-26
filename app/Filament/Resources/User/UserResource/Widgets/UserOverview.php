<?php

namespace App\Filament\Resources\User\UserResource\Widgets;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class UserOverview extends ChartWidget
{
    protected static ?string $heading = 'Membres';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        Carbon::setLocale('fr');

        $data = Trend::model(Member::class)
        ->between(
            start: now()->subYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

        $dataAdmin = Trend::model(User::class)
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
                [
                    'label' => 'Administrateurs ajoutés',
                    'data' => $dataAdmin->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#F70206',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F Y')),
            'labels' => $dataAdmin->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
