<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\User\UserResource;
use App\Filament\Pages\Auth\CustomEditProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
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
                        ]),

                    NavigationGroup::make('Administration')
                        ->items([
                            ...(UserResource::canViewAny() ? UserResource::getNavigationItems() : []),
                            ...(RoleResource::canViewAny() ? RoleResource::getNavigationItems() : []),
                        ]),

                    // NavigationGroup::make(UserResource::canViewAny() || RoleResource::canViewAny() || PermissionResource::canViewAny() ? 'Administration' : '')
                    //     ->items([
                    //         ...(PermissionResource::canViewAny() ? PermissionResource::getNavigationItems() : []),
                    //         ...(RoleResource::canViewAny() ? RoleResource::getNavigationItems() : []),
                    //         ...(UserResource::canViewAny() ? UserResource::getNavigationItems() : []),
                    //         ...(KonectUserResource::canViewAny() ? KonectUserResource::getNavigationItems() : []),
                    //     ]),
                ]);
            })
            ->profile(CustomEditProfile::class)
            ->revealablePasswords()
            ->plugins([
                FilamentShieldPlugin::make()
            ]);
    }
}
