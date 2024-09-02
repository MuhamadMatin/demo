<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SuratOverview extends BaseWidget
{
    protected static ?string $heading = 'Surat Posts';

    protected function getStats(): array
    {
        $stats = [
            Stat::make('Surat masuk', SuratMasuk::query()->count())
                ->icon('heroicon-o-envelope'),
            Stat::make('Surat keluar', SuratKeluar::query()->count())
                ->icon('heroicon-o-envelope-open'),
        ];

        // Check user role or any condition
        if (!auth()->user()->hasRole('USER')) {
            $stats[] = Stat::make('User', User::query()->count())
                ->icon('heroicon-o-user');
            $stats[] = Stat::make('Disposisi', Disposisi::query()->count())
                ->icon('heroicon-o-paper-airplane');
        }

        return $stats;
    }
}
