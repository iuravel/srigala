<?php

namespace App\Providers;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //$this->app->register(SrigalaPanelProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Gaji Berkala')
                    ->icon('heroicon-o-identification'),
                NavigationGroup::make()
                    ->label('Daftar Gaji')
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Otentikasi')
                    ->icon('heroicon-o-user-group')
                    ->collapsed(),
            ]);
        });
        
    }
}
