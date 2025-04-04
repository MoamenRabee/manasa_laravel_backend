<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemResource\Pages;
use App\Filament\Resources\SystemResource\RelationManagers;
use App\Filament\Resources\SystemResource\RelationManagers\LessonsRelationManager;
use App\Models\Classroom;
use App\Models\System;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;


class SystemResource extends Resource
{
    protected static ?string $model = System::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';


    protected static ?string $modelLabel = 'نظام';

    protected static ?string $pluralLabel = 'الانظمة';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->required()
                    ->label('الصف الدراسي')
                    ->columnSpan(1)
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('النوع')
                    ->required()
                    ->options([
                        'month' => 'شهر',
                        'package' => 'باكدچ',
                    ]),
                Forms\Components\RichEditor::make('description')
                    ->label('الوصف')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'heading',
                        'italic',
                        'orderedList',
                    ])
                    ->maxLength(255)
                    ->default(null)
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->required()
                    ->directory(directory: 'systems')
                    ->image(),


                Forms\Components\TextInput::make('sort_number')
                    ->required()
                    ->label('الترتيب')
                    ->numeric(),
                
                Forms\Components\Select::make('is_free')
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
                    
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('السعر')
                    ->numeric()
                    ->hidden(fn (callable $get) => $get('is_free') == 1),

                
                Forms\Components\Checkbox::make('is_active')
                    ->label('نشط')
                    ->default(true)
                    ->columnSpan(3),
                
            ])->columns(3);
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
            

                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->sortable()
                    ->searchable()
                    
                    ->getStateUsing(function (Model $record) {
                        return $record->type === 'month' ? 'شهر' : 'باكدچ';
                    })
                    ->colors([
                        'primary' => fn (Model $record) => $record->type === 'month',
                        'secondary' => fn (Model $record) => $record->type === 'package',
                    ]),


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
                Tables\Filters\Filter::make('is_active')
                    ->label('نشط')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),

                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'month' => 'شهر',
                        'package' => 'باكدچ',
                    ]),

                Tables\Filters\Filter::make('is_free')
                    ->label('مجاني')
                    ->query(fn (Builder $query): Builder => $query->where('is_free', true)),
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
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystems::route('/'),
            'create' => Pages\CreateSystem::route('/create'),
            'edit' => Pages\EditSystem::route('/{record}/edit'),
        ];
    }
}
