<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CountCardWidget;
use Filament\Facades\Filament;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'لوحة التحكم';
    protected static ?string $slug = 'dashboard';


    public function getColumns(): int | string | array
    {
        return 3;
    }





}
