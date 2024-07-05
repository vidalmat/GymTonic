<?php

namespace App\Filament\Resources\Mail;

use App\Models\Member;
use App\Mail\MailMember;
use App\Models\MailUser;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\Mail\MailResource\Pages\EditMail;
use App\Filament\Resources\Mail\MailResource\Pages\ListMails;
use App\Filament\Resources\Mail\MailResource\Pages\CreateMail;

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
            Radio::make('send_to')
                ->label(new HtmlString('<span class="text-gray-400">Envoyer à</span>'))
                ->options([
                    'specific' => 'Membres spécifiques',
                    'all' => 'Tous les membres',
                ])
                ->default('specific')
                ->reactive()
                ->required(),
            Select::make('members')
                ->label(new HtmlString('<span class="text-gray-400">Membres</span>'))
                ->searchable()
                ->multiple()
                ->relationship('members')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->lastname}")
                ->preload()
                ->required()
                ->hidden(fn (callable $get) => $get('send_to') === 'all'),
            TextInput::make('subject')
                ->label(new HtmlString('<span class="text-gray-400">Sujet</span>'))
                ->required(),
            RichEditor::make('message')
                ->label(new HtmlString('<span class="text-gray-400">Message</span>'))
                ->toolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'italic',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ])
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
                ->label(new HtmlString('<span class="text-gray-400">Message</span>'))
                ->limit(20)
                ->html()
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return strip_tags(html_entity_decode($state));
                }),

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
                    ->color(function (MailUser $record) {
                        if ($record->created_at->ne($record->updated_at)) {
                            return 'info';
                        }
                    }),
                // IconColumn::make('sent')
                //     ->label(new HtmlString('<span class="text-gray-400">Email envoyé</span>'))
                //     ->sortable()
                //     ->extraAttributes(['class' => 'flex justify-center'])
                //     ->boolean()
                //     ->trueColor('success')
                //     ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading(function ($record) {
                        return 'Suppression de ' . $record->subject;
                    })
                    ->modalDescription("Êtes-vous sur de vouloir supprimer cet email ?")
                    ->successNotificationTitle(function ($record) {
                        return 'L\'email ' . $record->subject . ' a été supprimé';
                    }),
                Action::make('sendEmail')
                    ->requiresConfirmation()
                    ->icon('heroicon-c-arrow-long-right')
                    ->label(function (MailUser $record) {
                        if ($record->sent === 1) {
                            return new HtmlString('<span class="text-success">Réexpédier email</span>');
                        } else {
                            return new HtmlString('<span class="text-blue-600">Envoyer cet email</span>');
                        }
                    })
                    ->color(function (MailUser $record) {
                        if ($record->sent === 1) {
                            return 'success';
                        }
                    })
                    ->action(function (MailUser $record) {
                        if (empty($record['subject']) || empty($record['message'])) {
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

                        if ($record->send_to === 'all') {
                            $emails = Member::pluck('email')->toArray();
                        } else {
                            $emails = $record->members->pluck('email')->toArray();
                        }

                        if (empty($emails)) {
                            Notification::make()
                                ->title('Aucune adresse e-mail valide trouvée.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            Mail::to($emails)->send(new MailMember($details));

                            if ($record->sent === 0) {
                                $record->sent = 1;
                                $record->save();
                            }

                            Notification::make()
                                ->title('L\'email a été envoyé avec succès.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            report($e);
                            Notification::make()
                                ->title('L\'envoi de l\'email a échoué.')
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
