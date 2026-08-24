<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Models\WebsiteSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            ->brandName(fn (): string => rescue(
                fn (): string => trim((WebsiteSetting::current()?->site_name ?? 'SIBATIG').' · Irban Tiga'),
                'SIBATIG · Irban Tiga',
                report: false,
            ))
            ->brandLogo(fn () => view('filament.partials.brand'))
            ->brandLogoHeight('2.65rem')
            ->favicon(asset('images/logo-irban-3.jpg').'?v=20260819')
            ->colors(fn (): array => [
                'primary' => Color::hex(WebsiteSetting::themeColor('primary_color', '#1769d2')),
            ])
            ->assets([
                Css::make('sibatig-admin', asset('css/sibatig-admin.css').'?v=13'),
            ])
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.partials.theme-variables'))
            ->renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, fn () => view('filament.partials.auth-showcase'))
            ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER, fn () => view('filament.partials.sidebar-footer'))
            ->renderHook(PanelsRenderHook::TOPBAR_LOGO_AFTER, fn () => view('filament.partials.topbar-greeting'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn () => view('filament.partials.topbar-actions'))
            ->spa(hasPrefetching: true)
            ->databaseTransactions()
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
            ->sidebarWidth('15.375rem')
            ->collapsibleNavigationGroups(false)
            ->navigationGroups([
                NavigationGroup::make('Menu Utama')->collapsible(false),
                NavigationGroup::make('Lainnya')->collapsible(false),
                NavigationGroup::make('Administrasi')->collapsible(false),
            ])
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
