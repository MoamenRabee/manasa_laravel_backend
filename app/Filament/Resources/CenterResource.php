<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CenterResource\Pages;
use App\Filament\Resources\CenterResource\RelationManagers;
use App\Models\Center;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CenterResource extends Resource
{
    protected static ?string $model = Center::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'اعدادات التطبيق';


    protected static ?string $modelLabel = 'مركز تعليمي';

    protected static ?string $pluralLabel = 'المراكز التعليمية';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('الاسم'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->label('العنوان'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                    
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->label('العنوان'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->label('تاريخ الإنشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCenters::route('/'),
            'create' => Pages\CreateCenter::route('/create'),
            'edit' => Pages\EditCenter::route('/{record}/edit'),
        ];
    }
}
