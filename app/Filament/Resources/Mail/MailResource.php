<?php

namespace App\Filament\Resources\Mail;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use App\Mail\MailMember;
use App\Models\MailUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\MailRecipient;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Mail\MailResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\Mail\MailResource\Pages\EditMail;
use App\Filament\Resources\Mail\MailResource\Pages\ListMails;
use App\Filament\Resources\Mail\MailResource\Pages\CreateMail;
use App\Filament\Resources\Mail\MailResource\RelationManagers;

class MailResource extends Resource
{
    protected static ?string $model = MailUser::class;

    protected static string $view = 'filament.resources.user.user-resource.pages.mail';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $title = 'Email';

    protected static ?string $modelLabel = 'Emails';

    public static ?string $slug = 'emails';

    protected static string $relationship = 'members';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('members')
                    ->label(new HtmlString('<span class="text-gray-400">Membres</span>'))
                    ->searchable()
                    ->relationship('members')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->lastname}")
                    ->preload()
                    ->required(),
                TextInput::make('subject')
                ->label(new HtmlString('<span class="text-gray-400">Sujet</span>'))
                    ->required(),
                Textarea::make('message')
                ->label(new HtmlString('<span class="text-gray-400">Message</span>'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('subject')
                ->label(new HtmlString('<span class="text-gray-400">Sujet</span>')),

                TextColumn::make('message')
                ->label(new HtmlString('<span class="text-gray-400">Message</span>')),

                TextColumn::make('created_at')
                    ->label(new HtmlString('<span class="text-gray-400">Date de création</span>'))
                    // ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small),

                TextColumn::make('updated_at')
                    ->label(new HtmlString('<span class="text-blue-400">Date de modification</span>'))
                    // ->date('d-m-Y')
                    ->sortable()
                    ->size(TextColumnSize::Small)
                    ->color(function (MailUser $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('sendEmail')
                    ->requiresConfirmation()
                    ->label(new HtmlString('<span class="text-blue-600">Envoyer un email</span>'))
                    ->action(function (MailUser $record) {

                        if (empty($record['subject']) || empty($record['message']) || $record->members->isEmpty()) {
                            Notification::make()
                                ->title('Les données de l\'email ne sont pas complètes')
                                ->danger()
                                ->send();
                            return;
                        }

                        $details = [
                            'title' => $record['subject'],
                            'body' => $record['message'],
                        ];

                        $email = Arr::pluck($record->members, 'email');

                        if (empty($email)) {
                            Notification::make()
                                ->title('Aucune adresse e-mail valide trouvée.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            Mail::to($email)->send(new MailMember($details));

                            Notification::make()
                                ->title('L\'e-mail a été envoyé avec succès à : ' . implode(', ', $email))
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            report($e);
                            Notification::make()
                                ->title('L\'envoi de l\'e-mail a échoué.')
                                ->danger()
                                ->send();
                        }
                    }),
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
            'index' => ListMails::route('/'),
            'create' => CreateMail::route('/créer'),
            'edit' => EditMail::route('/{record}/modifier'),
        ];
    }
}
