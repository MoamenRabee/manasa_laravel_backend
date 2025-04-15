<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodesGroupResource\Pages;
use App\Filament\Resources\CodesGroupResource\RelationManagers;
use App\Filament\Resources\CodesGroupResource\RelationManagers\CodesRelationManager;
use App\Models\CodesGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CodesGroupResource extends Resource
{
    protected static ?string $model = CodesGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';


    protected static ?string $modelLabel = 'ملف اكواد';

    protected static ?string $pluralLabel = 'ملفات الاكواد';


    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم المجموعة')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->default(null)
                    ->columnSpan(2),
                Forms\Components\Toggle::make('isPrinted')
                    ->label('هل تمت الطباعة؟')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المجموعة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->searchable(),
                Tables\Columns\IconColumn::make('isPrinted')
                    ->label('هل تمت الطباعة؟')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث') 
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_printed')
                    ->label('مطبوعة')
                    ->trueLabel('نعم')
                    ->falseLabel('لا')
                    ->placeholder('الكل')
                    ->column('isPrinted'),
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
            CodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCodesGroups::route('/'),
            'create' => Pages\CreateCodesGroup::route('/create'),
            'edit' => Pages\EditCodesGroup::route('/{record}/edit'),
        ];
    }
}
