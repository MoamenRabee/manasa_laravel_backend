<?php

namespace App\Filament\Widgets;

use App\Models\View;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class ViewsWidget extends ChartWidget
{
    protected static ?string $heading = 'المشاهدات';

    protected int | string | array $columnSpan = 2;

    protected static ?string $maxHeight = '300px';


    

    
    protected function getData(): array
    {
        $views = View::whereBetween('created_at', [now()->subWeek(), now()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // نحضّر الأيام باللغة العربية
        $labels = [];
        $data = [];

        $period = CarbonPeriod::create(now()->subWeek(), now());

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->locale('ar')->translatedFormat('l'); // السبت، الأحد...
            $data[] = isset($views[$formattedDate]) ? $views[$formattedDate]->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'المشاهدات',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => false,
                        'min' => 1,
                    ],

                ],
            ],
            
        ];
    }


    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'display' => true,
                    'grid' => [
                        'display' => true,
                    ],
                    'ticks' => [
                        'display' => false,
                        'color' => '#000',
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'x' => [
                    'display' => true,
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }






}
