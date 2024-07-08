<?php

namespace App\Filament\Resources\ErrorLog;

use App\Models\ErrorLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Actions\DeleteAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\ErrorLog\ErrorLogResource\Pages\ViewErrorLog;
use App\Filament\Resources\ErrorLog\ErrorLogResource\Pages\ListErrorLogs;

class ErrorLogResource extends Resource
{
    protected static ?string $model = ErrorLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $modelLabel = 'Erreurs';

    public static ?string $slug = 'erreurs';

    protected static string $relationship = 'users';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.lastname')
                    ->label(new HtmlString('<span class="text-gray-400">Utilisateur</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('title')
                    ->label(new HtmlString('<span class="text-gray-400">Message</span>'))
                    ->searchable()
                    ->size(TextColumnSize::Small)->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return strip_tags(html_entity_decode($state));
                    }),
                TextColumn::make('created_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de création</span>'))
                    ->sortable()
                    ->size(TextColumnSize::Small),
                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de modification</span>'))
                    ->sortable()
                    ->size(TextColumnSize::Small)
                    ->color(function (ErrorLog $record) {
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
                DeleteAction::make()
                    ->modalHeading(function ($record) {
                        return 'Suppression de ' . $record->title;
                    })
                    ->modalDescription("Êtes-vous sur de vouloir supprimer ce libellé ?")
                    ->successNotificationTitle(function ($record) {
                        return 'Le message d\'erreur ' . $record->title . ' a été supprimé';
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('users.lastname')
                    ->columnSpanFull(),
                TextEntry::make('title')
                    ->columnSpanFull(),
                TextEntry::make('code')
                    ->columnSpanFull(),
                TextEntry::make('stack_trace')
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListErrorLogs::route('/'),
            'view' => ViewErrorLog::route('/{record}'),
        ];
    }
}
