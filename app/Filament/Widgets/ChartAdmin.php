<?php

namespace App\Filament\Widgets;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Flowframe\Trend\Trend;
use App\Models\SuratKeluar;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ChartAdmin extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?int $sort = 3;
    
    protected function getData(): array
    {
        // Fetch data for SuratMasuk
        $suratMasukData = Trend::model(SuratMasuk::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        // Fetch data for SuratKeluar
        $suratKeluarData = Trend::model(SuratKeluar::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $datasets = [
            [
                'label' => 'Surat Masuk',
                'data' => $suratMasukData->map(fn (TrendValue $value) => $value->aggregate),
            ],
            [
                'label' => 'Surat Keluar',
                'data' => $suratKeluarData->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ];

        if (!auth()->user()->hasRole('USER')) {
            $disposisiData = Trend::model(Disposisi::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count();

            $datasets[] = [
                'label' => 'Disposisi',
                'data' => $disposisiData->map(fn (TrendValue $value) => $value->aggregate),
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $suratMasukData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
