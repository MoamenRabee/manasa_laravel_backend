<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViewResource\Pages;
use App\Filament\Resources\ViewResource\RelationManagers;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\View;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViewResource extends Resource
{
    protected static ?string $model = View::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';


    protected static ?string $modelLabel = 'مشاهدة';

    protected static ?string $pluralLabel = 'المشاهدات';

    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('student_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('video_id')
                //     ->required()
                //     ->numeric(),
                Forms\Components\TextInput::make('duration')
                    ->label('مدة المشاهدة')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('count')
                    ->label('عدد المشاهدات')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('اسم الطالب')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.phone')
                    ->label('رقم الهاتف')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.parent_phone')
                    ->label('ولي الامر')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('video.lesson.classroom.name')
                    ->label('الصف الدراسي')
                    ->sortable(),
                Tables\Columns\TextColumn::make('video.lesson.name')
                    ->label('الدرس')
                    ->sortable(),
                Tables\Columns\TextColumn::make('video.name')
                    ->label('الفيديو')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('مدة المشاهدة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->label('عدد المشاهدات')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ المشاهدة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListViews::route('/'),
            'create' => Pages\CreateView::route('/create'),
            'edit' => Pages\EditView::route('/{record}/edit'),
        ];
    }


    public static function canCreate(): bool
    {
        return false;
    }





}
