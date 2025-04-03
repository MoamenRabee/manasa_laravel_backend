<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'سؤال';

    protected static ?string $title = 'الاسئلة';

    protected static ?string $pluralLabel = 'اسئلة';



    protected function getFormModalHeading(): ?string
    {
        return 'تعديل السؤال';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('نوع السؤال')
                    ->options([
                        'mcq' => 'اختيار من متعدد',
                        'text' => 'نص',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state !== 'mcq') {
                            // مسح الحقول عند تغيير النوع
                            $set('options', []);
                            $set('correct_answer', null);
                        }
                    })
                    ->columnSpan(3),
                Forms\Components\RichEditor::make('question')
                    ->label('السؤال')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'heading',
                        'italic',
                        'orderedList',
                    ])
                    ->columnSpan(2),

                Forms\Components\FileUpload::make('image')
                    ->label('صورة سؤال')
                    ->directory('quesions')
                    ->image()
                    ->columns(columns: 1),

                Forms\Components\TagsInput::make('options')
                    ->label('الخيارات')
                    ->placeholder('اكتب الخيارات هنا')
                    ->required()
                    ->reactive()
                    ->columnSpan(2)
                    ->visible(fn (callable $get) => $get('type') === 'mcq')
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        if (!in_array($get('correct_answer'), $state ?? [])) {
                            $set('correct_answer', null);
                        }
                    })
                    ->afterStateUpdated(function (callable $set, $state) {
                        $set('options', array_values($state));
                    }),

                Forms\Components\Select::make('correct_answer')
                    ->label('الإجابة الصحيحة')
                    ->options(function (callable $get) {
                        $options = $get('options') ?? [];
                        return is_array($options) && count($options) > 0
                            ? array_combine($options, $options)
                            : [];
                    })
                    ->required()
                    ->reactive()
                    ->visible(fn (callable $get) => $get('type') === 'mcq')
                    ->disabled(fn (callable $get) => empty($get('options'))),
            ])->columns(3);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->label('صورة السؤال')
                    ->limit(1),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('نوع السؤال')
                    ->formatStateUsing(function ($state) {
                        return [
                            'mcq' => 'اختياري',
                            'text' => 'مقالي',
                        ][$state] ?? $state;
                    })
                    ->colors([
                        'primary' => 'mcq',
                        'success' => 'text',
                    ])
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('question')
                    ->html()
                    ->limit(100)
                    ->searchable()
                    ->label('السؤال'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع السؤال')
                    ->options([
                        'mcq' => 'اختياري',
                        'text' => 'مقالي',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalHeading('تعديل السؤال'),
                Tables\Actions\DeleteAction::make()->modalHeading('حذف السؤال'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
