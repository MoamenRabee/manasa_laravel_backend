<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\Classroom;
use App\Models\File;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';



    protected static ?string $modelLabel = 'ملف';

    protected static ?string $pluralLabel = 'الملفات';


    protected static ?int $navigationSort = 7;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('الاسم')
                    ->maxLength(255),
                
                Forms\Components\Select::make('classroom_id')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->required()
                    ->label('الفصل الدراسي')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('lesson_id', null))
                    ->searchable(),
                Forms\Components\Select::make('lesson_id')
                    ->options(function (callable $get) {
                        $classroomId = $get('classroom_id');
                        if ($classroomId) {
                            return Lesson::where('classroom_id', $classroomId)->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->required()
                    ->label('الدرس')
                    ->searchable(),
                Forms\Components\FileUpload::make('path')
                    ->label('الملف')
                    ->required()
                    ->directory('pdfs')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(1024)
                    ->columnSpan(3),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('الفصل الدراسي')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lesson.name')
                    ->label('الدرس')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الاضافة')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التعديل')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classroom_id')
                    ->label('الصف الدراسي')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('lesson_id')
                    ->label('الدرس')
                    ->options(Lesson::all()->pluck('name', 'id'))
                    ->searchable(),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}
