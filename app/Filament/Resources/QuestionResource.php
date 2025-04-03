<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $modelLabel = 'سؤال';

    protected static ?string $pluralLabel = 'الاسئلة';

    protected static ?int $navigationSort = 9;


    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
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
                    ->columnSpan(3),
                Forms\Components\MarkdownEditor::make('question')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('exam_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
