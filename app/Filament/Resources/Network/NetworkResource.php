<?php

namespace App\Filament\Resources\Network;

use Filament\Forms;
use Filament\Tables;
use App\Models\Network;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\Network\NetworkResource\Pages;
use App\Filament\Resources\Network\NetworkResource\Pages\EditNetwork;
use App\Filament\Resources\Network\NetworkResource\Pages\ListNetworks;
use App\Filament\Resources\Network\NetworkResource\Pages\CreateNetwork;

class NetworkResource extends Resource
{
    protected static ?string $model = Network::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $modelLabel = 'Médias';

    public static ?string $slug = 'medias';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                    ->label(new HtmlString('<span class="text-gray-400">Ajouter un réseau</span>'))
                    ->schema([
                        TextInput::make('label')
                            ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                            ->maxLength(255),

                        TextInput::make('url')
                            ->label(new HtmlString('<span class="text-gray-400">URL</span>'))
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('label')
                    ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
                    ->searchable()
                    ->sortable()
                    ->size(TextColumnSize::Small)
                    ->color(function ($record) {
                        return match ($record->label) {
                            'Facebook' => 'info',
                            'Instagram' => Color::hex('#ea5104'),
                            // 'rejected' => 'red',
                            default => 'gray',
                        };
                    }),

                // TextColumn::make('url')
                //     ->label(new HtmlString('<span class="text-gray-400">URL</span>'))
                //     ->searchable()
                //     ->sortable()
                //     ->size(TextColumnSize::Small),

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
                    ->color(function (Network $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                // DeleteAction::make(),
                Action::make('Aller sur le site')
                    ->url(fn (Network $record): string => config('url.' . strtolower($record->label)))
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-document-plus')
                    ->color(Color::hex('#ada8a8')),
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
            'index' => ListNetworks::route('/'),
            'create' => CreateNetwork::route('/créer'),
            'edit' => EditNetwork::route('/{record}/modifier'),
        ];
    }
}
