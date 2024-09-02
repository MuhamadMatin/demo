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
use App\Http\Middleware\AdminMiddleware;
use App\Filament\Pages\ManagePreferences;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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

class AdminPanelProvider extends PanelProvider
{
    use HandlesSettings;

    public function panel(Panel $panel): Panel
    {
        $this->updateHeader();
        
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName($this->getBrand())
            ->brandLogo($this->getLogo())
            ->favicon($this->getIcon())
            ->login()
            ->defaultThemeMode(ThemeMode::Light)
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
                AdminMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Surat',
                'Aset',
                'Inbox',
                'Agenda',
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-s-cog'),
                MenuItem::make()
                    ->label('Back')
                    ->url('/')
                    ->icon('heroicon-s-arrow-uturn-left'),
                MenuItem::make()
                    ->label('Dashboard')
                    ->url('/dashboard')
                    ->icon('heroicon-s-identification')
                    ->visible(fn (): bool => auth()->user()->id),
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
                    FilamentShieldPlugin::make()
                        ->gridColumns([
                            'default' => 1,
                            'sm' => 2,
                        ])
                        ->sectionColumnSpan(1)
                        ->checkboxListColumns([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 2,
                        ])
                        ->resourceCheckboxListColumns([
                            'default' => 1,
                            'sm' => 2,
                        ]),
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
