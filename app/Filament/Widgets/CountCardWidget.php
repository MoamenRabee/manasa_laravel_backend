<?php

namespace App\Filament\Widgets;

use App\Models\Center;
use App\Models\Exam;
use App\Models\File;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Video;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\CarbonPeriod;

class CountCardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [


            Stat::make('السناتر', Center::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-building-office')
                ->color('purple')
                ->chart(
                    collect(CarbonPeriod::create(now()->subWeek(), now()))
                        ->map(fn($date) => ['date' => $date->format('Y-m-d'), 'count' => 0])
                        ->keyBy('date')
                        ->merge(
                            Center::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                                ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                ),


            Stat::make('الطلاب', Student::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart(
                    collect(
                        CarbonPeriod::create(now()->subWeek(), now())
                    )->map(function ($date) {
                        return [
                            'date' => $date->format('Y-m-d'),
                            'count' => 0,
                        ];
                    })->keyBy('date')
                        ->merge(
                            Student::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($student) => $student->created_at->format('Y-m-d'))
                                ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                ),
            Stat::make('الدروس', Lesson::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-book-open') // تقدر تغير الأيقونة حسب ذوقك
                ->color('info')
                ->chart(
                    collect(
                        CarbonPeriod::create(now()->subWeek(), now())
                    )->map(function ($date) {
                        return [
                            'date' => $date->format('Y-m-d'),
                            'count' => 0,
                        ];
                    })->keyBy('date')
                        ->merge(
                            Lesson::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($lesson) => $lesson->created_at->format('Y-m-d'))
                                ->map(fn($group) => [
                                    'date' => $group->first()->created_at->format('Y-m-d'),
                                    'count' => $group->count()
                                ])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                ),
            Stat::make('الاختبارات', Exam::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-clipboard-document')
                ->color('warning')
                ->chart(
                    collect(CarbonPeriod::create(now()->subWeek(), now()))
                        ->map(fn($date) => ['date' => $date->format('Y-m-d'), 'count' => 0])
                        ->keyBy('date')
                        ->merge(
                            Exam::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                                ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                ),

            Stat::make('الملفات', File::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-document')
                ->color('secondary')
                ->chart(
                    collect(CarbonPeriod::create(now()->subWeek(), now()))
                        ->map(fn($date) => ['date' => $date->format('Y-m-d'), 'count' => 0])
                        ->keyBy('date')
                        ->merge(
                            File::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                                ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                )
            ,


            Stat::make('الفيديوهات', Video::whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->icon('heroicon-o-video-camera')
                ->color('danger')
                ->chart(
                    collect(CarbonPeriod::create(now()->subWeek(), now()))
                        ->map(fn($date) => ['date' => $date->format('Y-m-d'), 'count' => 0])
                        ->keyBy('date')
                        ->merge(
                            Video::whereBetween('created_at', [now()->subWeek(), now()])
                                ->get()
                                ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                                ->map(fn($group) => ['date' => $group->first()->created_at->format('Y-m-d'), 'count' => $group->count()])
                        )
                        ->sortKeys()
                        ->pluck('count')
                        ->values()
                        ->toArray()
                )


        ];
    }
}
