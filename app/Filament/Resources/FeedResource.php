<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedResource\Pages;
use App\Filament\Resources\FeedResource\RelationManagers;
use App\Models\Feed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedResource extends Resource
{
    protected static ?string $model = Feed::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $modelLabel = 'خبر';

    protected static ?string $pluralLabel = 'الاخبار';

    protected static ?int $navigationSort = 14;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('link')
                    ->label('الرابط')
                    ->maxLength(255)
                    ->default(null)
                    ->url()
                    ->columnSpan(2),
                Forms\Components\RichEditor::make('description')
                    ->label('الوصف')
                    ->maxLength(65535)
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'heading',
                        'italic',
                        'orderedList',
                    ])
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->directory('feeds')
                    ->image(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->width('50px')
                    ->height('50px')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->label('الرابط')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                   ->label('تاريخ الانشاء')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime('Y-m-d H:i A')
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
            'index' => Pages\ListFeeds::route('/'),
            'create' => Pages\CreateFeed::route('/create'),
            'edit' => Pages\EditFeed::route('/{record}/edit'),
        ];
    }
}
