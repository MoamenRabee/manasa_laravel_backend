<?php

namespace App\Filament\Resources\SystemResource\RelationManagers;

use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons'; 

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'درس';

    protected static ?string $title = 'الدروس';

    protected static ?string $pluralLabel = 'درس';

    public function form(Form $form): Form
    {
        return $form->schema([]);
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
                    ->label('اسم الدرس')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('اسم الفصل')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('نشط')
                    ->sortable(),
            ])
            ->filters([
                
            ])
            ->headerActions([
               
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name'])
                    ->form(fn (Form $form) => 
                        $form->schema([
                            Forms\Components\Select::make('recordId')
                                ->label('اختر الدرس')
                                ->options(fn () => 
                                    Lesson::where('classroom_id', operator: $this->ownerRecord->classroom_id)
                                        ->whereNotIn('id', $this->ownerRecord->lessons->pluck('id')) // استبعاد الدروس المضافة بالفعل
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                    )
                    ->label('إضافة درس')
                    ->modalHeading('إضافة درس')
                    ->modalSubheading('اختر الدرس الذي تريد إضافته إلى هذا النظام')
                    ->modalButton('إضافة درس'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('إزالة')
                    ->requiresConfirmation()
                    ->modalHeading('إزالة درس')
                    ->modalSubheading('هل أنت متأكد أنك تريد إزالة هذا الدرس؟')
                    ->modalButton('إزالة'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function modifyQuery(Builder $query): Builder
    {
        return $query->where('classroom_id', $this->ownerRecord->classroom_id);
    }
}
