<?php

namespace App\Filament\Resources;
use App\Models\Exam;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Filters\SelectFilter;

use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Models\Classroom;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;


class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'درس';

    protected static ?string $pluralLabel = 'الدروس';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'محتوي التطبيق';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('بيانات الدرس')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('الاسم')
                            ->maxLength(255)->columnSpan(span: 3),
                        TextInput::make('sort_number')
                            ->required()
                            ->label('الترتيب')
                            ->numeric(),
                        Select::make('classroom_id')
                            ->required()
                            ->label('الصف الدراسي')
                            ->columnSpan(1)
                            ->options(Classroom::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('require_exam_id', null)),
                        Select::make('require_exam_id')
                            ->label('اختبار اجباري')
                            ->columnSpan(1)
                            ->options(function (callable $get) {
                                $classroomId = $get('classroom_id');
                                if ($classroomId) {
                                    return Exam::where('classroom_id', $classroomId)->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->searchable(),
                        
                        Select::make('is_free')
                            ->label('مجاني')
                            ->required()
                            ->options([
                                1 => 'نعم',
                                0 => 'لا',
                            ])
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state == 1) {
                                    $set('price', null);
                                }
                            }),
                        
                        TextInput::make('price')
                            ->required()
                            ->label('السعر')
                            ->numeric()
                            ->hidden(fn (callable $get) => $get('is_free') == 1),
                        
                        Checkbox::make('is_active')
                                ->label('نشط')
                                ->default(true)
                                ->columnSpan(3),
                    ]),
                    Section::make('الوصف و الصوره')
                    ->columns(3)
                    ->schema(components: [
                        RichEditor::make('description')
                        ->label('الوصف')
                        ->toolbarButtons([
                            'bold',
                            'bulletList',
                            'heading',
                            'italic',
                            'orderedList',
                        ])
                        ->columnSpan(2),
                    FileUpload::make('image')
                        ->image()
                        ->directory('images')
                        ->required()
                        ->label('الصورة')
                        ->maxSize(512),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make(name: 'image')
                    ->label('الصورة')
                    ->width('50px')
                    ->height('50px')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('الاسم'),
                    
                Tables\Columns\BadgeColumn::make('is_free')
                    ->label('مجاني')
                    ->getStateUsing(function (Model $record) {
                        return $record->is_free ? 'مجاني' : 'السعر: ' . $record->price . ' ج.م';
                    })
                    ->colors([
                        'success' => fn (Model $record) => $record->is_free,
                        'warning' => fn (Model $record) => !$record->is_free,
                    ]),

                Tables\Columns\TextColumn::make('sort_number')
                    ->numeric()
                    ->sortable()
                    ->label('الترتيب')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->numeric()
                    ->sortable()
                    ->label('الصف الدراسي'),
                Tables\Columns\TextColumn::make('required_exam.name')
                    ->numeric()
                    ->sortable()
                    ->default('لا يوجد')
                    ->label('اختبار اجباري'),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('نشط')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->label('تاريخ الانشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label('الصف الدراسي')
                    ->options(fn () => Classroom::all()->pluck('name', 'id')->toArray())
                    ->default(null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_number', 'asc')
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
