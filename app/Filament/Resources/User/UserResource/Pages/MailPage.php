<?php

namespace App\Filament\Resources\User\UserResource\Pages;

use App\Mail\MailMember;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Mail;
use Filament\Pages\SubNavigationPosition;
use Filament\Notifications\Notification;
use App\Filament\Resources\User\UserResource;

class MailPage extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user.user-resource.pages.mail';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $title = 'Email';

    protected static ?string $modelLabel = 'Emails';

    public static ?string $slug = 'emails';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    // public function getActions(): array
    // {
    //     return [
    //         Action::make('sendEmail')
    //         ->label('Envoyer un email')
    //         ->action(function () {

    //             $details = [
    //                 'title' => 'Test mail de Gym Tonic',
    //                 'body' => 'Ceci est un test d\'email de Gym Tonic'
    //             ];

    //             if (empty($details['title']) || empty($details['body'])) {
    //                 $this->notify('danger', 'Les détails de l\'e-mail ne sont pas définis correctement');
    //                 return;
    //             }

    //             try {
    //                 Mail::to('vidalmat06@gmail.com')->send(new MailMember($details));

    //                 Notification::make()
    //                     ->title('L\'email a été envoyé avec succès')
    //                     ->success()
    //                     ->send();
    //             } catch (\Exception $e) {
    //                 report($e);
    //             }
    //         }),
    //     ];
    // }
}
