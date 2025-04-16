<?php

namespace App\Exports;

use App\Models\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ViewsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'اسم الطالب',
            'السنتر',
            'رقم الهاتف',
            'ولي الأمر',
            'مدة المشاهدة',
            'عدد المشاهدات',
        ];
    }

    public function map($view): array
    {
        return [
            $view->student->name,
            $view->student->center->name,
            $view->student->phone,
            $view->student->parent_phone,
            $view->duration,
            $view->count,
        ];
    }
}