<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\Mail\MailResource;
use App\Filament\Resources\User\UserResource;
use App\Filament\Pages\Auth\CustomEditProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\Lesson\LessonResource;
use App\Filament\Resources\Member\MemberResource;
use App\Filament\Resources\Network\NetworkResource;
use App\Filament\Resources\Document\DocumentResource;
use App\Filament\Resources\ErrorLog\ErrorLogResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\User\UserResource\Widgets\UserOverview;
use App\Filament\Resources\Lesson\LessonResource\Widgets\CalendarWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ->brandLogo(asset('GymTonic2.png'))
            ->brandName('Gym Tonic')
            ->default('Gym Tonic')
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            ...Dashboard::getNavigationItems(),
                            ...(NetworkResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? NetworkResource::getNavigationItems() : []),
                        ]),

                    NavigationGroup::make(
                        (
                            UserResource::canViewAny() ||
                            RoleResource::canViewAny() ||
                            DocumentResource::canViewAny() ||
                            MemberResource::canViewAny() ||
                            LessonResource::canViewAny()) ? 'Administration' : '')
                        ->items([
                            ...(UserResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? UserResource::getNavigationItems() : []),
                            ...(RoleResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? RoleResource::getNavigationItems() : []),
                            ...(DocumentResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? DocumentResource::getNavigationItems() : []),
                            ...(MemberResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? MemberResource::getNavigationItems() : []),
                            ...(LessonResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? LessonResource::getNavigationItems() : []),
                            // ...(Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? MailPage::getNavigationItems() : []),
                            ...(MailResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? MailResource::getNavigationItems() : []),
                        ]),

                        NavigationGroup::make(
                            (
                                ErrorLogResource::canViewAny() ? 'Système' : ''))
                            ->items([
                                ...(ErrorLogResource::canViewAny() && Auth::user()->isSuperAdmin() || Auth::user()->isManager() ? ErrorLogResource::getNavigationItems() : []),
                            ]),
                ]);
            })
            ->profile(CustomEditProfile::class)
            ->revealablePasswords()
            ->plugins([
                FilamentShieldPlugin::make()
            ])
            ->widgets([
                CalendarWidget::class,
                UserOverview::class,
            ])
            ->plugins([FilamentFullCalendarPlugin::make()
                ->selectable(true)
                ->editable(true)
                ->editable(true)
                ->config([
                    'initialView' => 'timeGridWeek', // show week by week
                    'firstDay' => 1, // start the week on a Monday
                    'eventDisplay' => 'block', // render a solid rectangle
                ])
            ]);
    }
}
