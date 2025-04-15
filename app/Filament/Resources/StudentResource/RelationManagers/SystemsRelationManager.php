<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\System;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SystemsRelationManager extends RelationManager
{
    protected static string $relationship = 'systems';


    protected static ?string $modelLabel = 'نظام';

    protected static ?string $title = 'الانظمة المفعلة';

    protected static ?string $pluralLabel = 'نظام';


    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make(name: 'image')
                    ->label('الصورة')
                    ->width('50px')
                    ->height('50px')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم النظام')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->preloadRecordSelect()
                ->recordSelectSearchColumns(['name'])
                ->form(fn (Form $form) => 
                    $form->schema([
                        Forms\Components\Select::make('recordId')
                            ->label('اختر النظام')
                            ->options(fn () => 
                                System::where('classroom_id', operator: $this->ownerRecord->classroom_id)
                                    ->whereNotIn('id', $this->ownerRecord->lessons->pluck('id')) 
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('activated_with')
                            ->default('admin')
                    ])
                )
                ->label('إضافة نظام')
                ->modalHeading('إضافة نظام')
                ->modalSubheading('اختر النظام الذي تريد تفعيلة')
                ->modalButton('إضافة نظام'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('إزالة')
                    ->requiresConfirmation()
                    ->modalHeading('إزالة نظام')
                    ->modalSubheading('هل أنت متأكد أنك تريد إزالة هذا النظام')
                    ->modalButton('إزالة'),
            ]);
    }
}
