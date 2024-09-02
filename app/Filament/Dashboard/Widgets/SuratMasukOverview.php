<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\SuratMasuk;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SuratMasukOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth()->user();
        return [
            Stat::make('Surat masuk', SuratMasuk::query()->where('tujuan_surat', $user->id)->count())
                ->icon('heroicon-o-envelope'),
        ];
    }
}
