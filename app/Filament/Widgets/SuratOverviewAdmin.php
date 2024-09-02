<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SuratOverviewAdmin extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Surat masuk', SuratMasuk::query()->count())
                ->icon('heroicon-o-envelope'),
            Stat::make('Surat keluar', SuratKeluar::query()->count())
                ->icon('heroicon-o-envelope-open'),
            Stat::make('User', User::query()->count())
                ->icon('heroicon-o-user'),
            Stat::make('Disposisi', Disposisi::query()->count())
                ->icon('heroicon-o-paper-airplane'),
        ];
    }
}
