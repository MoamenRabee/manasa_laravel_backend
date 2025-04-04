<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class ClassroomsCountWidget extends ChartWidget
{
    protected static ?string $heading = 'الصفوف الدراسية وعدد الطلاب';

    protected static ?string $maxHeight = '300px';



    protected function getData(): array
    {
        // نجيب الصفوف الدراسية مع عدد الطلاب في كل صف باستخدام withCount
        $classrooms = Classroom::withCount('students')->get();

        // تجهيز البيانات لعرضها في الشارت
        $classroomCounts = $classrooms->mapWithKeys(function ($classroom) {
            return [
                $classroom->name => $classroom->students_count, // هنا بنستخدم students_count المتاحة من withCount
            ];
        });

        return [
            'datasets' => [
                [
                    'data' => $classroomCounts->values()->toArray(), // عدد الطلاب في كل صف
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // ألوان عشوائية للشرائح
                ],
            ],
            'labels' => $classroomCounts->keys()->toArray(), // أسماء الصفوف
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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
                    'display' => false,
                ],
                'x' => [
                    'display' => false,
                ],
            ],

        ];
    }





}
