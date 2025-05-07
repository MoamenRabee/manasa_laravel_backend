<?php

namespace App\Filament\Resources;
use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Filament\Resources\VideoResource\RelationManagers\ViewRelationManager;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOption\Option;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $modelLabel = 'فيديو';

    protected static ?string $pluralLabel = 'فيديوهات';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'محتوي التطبيق';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('الاسم'),
                Forms\Components\TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(3)
                    ->label('عدد المشاهدات'),
                // Forms\Components\Select::make('is_free')
                //     ->label('مجاني')
                //     ->required()
                //     ->options([
                //         1 => 'نعم',
                //         0 => 'لا',
                //     ])
                //     ->default(1)
                //     ->reactive()
                //     ->afterStateUpdated(function (callable $set, $state) {
                //         if ($state == 1) {
                //             $set('price', null);
                //         }
                //     }),
                // Forms\Components\TextInput::make('price')
                //     ->required()
                //     ->label('السعر')
                //     ->numeric()
                //     ->hidden(fn (callable $get) => $get('is_free') == 1),
                Forms\Components\Select::make('link_type')
                    ->options([
                        'youtube' => 'يوتيوب',
                        'vimeo' => 'فيميو',
                    ])
                    ->label('نوع الرابط')
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->url()
                    ->label('الرابط'),
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
                Forms\Components\TextInput::make('sort_number')
                    ->required()
                    ->numeric()
                    ->label('ترتيب العرض'),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->label('المدة ب الدقائق'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label('نشط')->columnSpan(2),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->label('الوصف'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name')
                    ->sortable()
                    ->label('اسم الفصل الدراسي'),
                Tables\Columns\TextColumn::make('lesson.name')
                    ->sortable()
                    ->label('الدرس'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('اسم الفيديو'),
                // Tables\Columns\BadgeColumn::make('is_free')
                //     ->label('مجاني')
                //     ->getStateUsing(function (Model $record) {
                //         return $record->is_free ? 'مجاني' : 'السعر: ' . $record->price . ' ج.م';
                //     })
                //     ->colors([
                //         'success' => fn (Model $record) => $record->is_free,
                //         'warning' => fn (Model $record) => !$record->is_free,
                //     ]),
                Tables\Columns\TextColumn::make('sort_number')
                    ->numeric()
                    ->sortable()
                    ->label('ترتيب العرض')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable()
                    ->label('المدة بالدقائق')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('نشط'),
                Tables\Columns\BadgeColumn::make('link_type')
                    ->label('نوع الرابط')
                    ->colors([
                        'youtube' => 'green',
                        'vimeo' => 'blue',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'youtube' => 'يوتيوب',
                            'vimeo' => 'فيميو',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ الإنشاء'),
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
            ViewRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
