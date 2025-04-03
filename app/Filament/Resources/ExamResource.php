<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Filament\Resources\ExamResource\RelationManagers\QuestionsRelationManager;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;


    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $modelLabel = 'اختبار';

    protected static ?string $pluralLabel = 'الاختبارات';

    protected static ?int $navigationSort = 8;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('minutes')
                    ->label('الوقت باالدقائق')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->required()
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'published' => 'منشور',
                        'published_show_results' => 'منشور مع عرض النتائج',
                        'show_results' => 'عرض النتائج',
                    ]),
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
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('minutes')
                ->label('الوقت باالدقائق')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'قيد الانتظار',
                            'published' => 'منشور',
                            'published_show_results' => 'منشور مع عرض النتائج',
                            'show_results' => 'عرض النتائج',
                            default => $state,
                        };
                    })
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'published',
                        'warning' => 'published_show_results',
                        'primary' => 'show_results',
                    ]),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('الفصل الدراسي')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lesson.name')
                    ->label('الدرس')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('questions_count')
                    ->label('عدد الأسئلة')
                    ->getStateUsing(function ($record) {
                        return $record->questions()->count(); // إرجاع العدد فقط
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\QuestionsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
