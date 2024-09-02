<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\HandlesSettings;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

class DashboardPanelProvider extends PanelProvider
{
    use HandlesSettings;

    public function panel(Panel $panel): Panel
    {
        $this->updateHeader();
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->brandName($this->getBrand())
            ->brandLogo($this->getLogo())
            ->favicon($this->getIcon())
            ->login()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\\Filament\\Dashboard\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-s-cog'),
                MenuItem::make()
                    ->label('Admin')
                    ->url('/admin')
                    ->icon('heroicon-s-shield-exclamation')
                    ->visible(fn (): bool => auth()->user()->hasRole(['super_admin', 'ADMIN'])),
                MenuItem::make()
                    ->label('Back')
                    ->url('/')
                    ->icon('heroicon-s-arrow-uturn-left')
                    ->visible(fn (): bool => auth()->user()->id),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Surat',
                'Aset',
                'Inbox',
                'Agenda',
            ])
            ->plugins(
                [
                    FilamentEditProfilePlugin::make()
                        ->slug('my-profile')
                        ->setTitle('Edit profile')
                        ->setNavigationLabel('My-Profile')
                        ->setIcon('heroicon-o-user')
                        ->setSort(10)
                        ->canAccess(fn () => auth()->user()->id)
                        ->shouldRegisterNavigation(false)
                        ->shouldShowDeleteAccountForm()
                        ->shouldShowBrowserSessionsForm()
                        ->shouldShowAvatarForm(
                            value: true,
                            directory: 'avatars',
                            rules: 'mimes:jpeg,png,jpg|max:1024'
                        ),
                    FilamentGeneralSettingsPlugin::make()
                        ->canAccess(fn () => auth()->user()->hasRole(['super_admin', 'ADMIN']))
                        ->setSort(3)
                        ->setIcon('heroicon-o-cog-6-tooth')
                        ->setNavigationGroup('Settings')
                        ->setTitle('General Settings')
                        ->setNavigationLabel('General Settings'),
                ]
            );
    }
}
