<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $modelLabel = 'اعدادات التطبيق';

    protected static ?string $pluralLabel = 'الاعدادات';


    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('android_build_number')
                    ->required()
                    ->label('رقم البنية للأندرويد')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ios_build_number')
                    ->required()
                    ->numeric()
                    ->label('رقم البنية للايفون')
                    ->default(0),
                Forms\Components\TextInput::make('android_link')
                    ->maxLength(255)
                    ->label('رابط الأندرويد')
                    ->default(null),
                Forms\Components\TextInput::make('ios_link')
                    ->maxLength(255)
                    ->label('رابط الايفون')
                    ->default(null),
                Forms\Components\TextInput::make('closed_message')
                    ->maxLength(255)
                    ->label('رسالة الاغلاق')
                    ->default(null),
                Forms\Components\TextInput::make('whatsapp')
                    ->maxLength(255)
                    ->label('واتساب')
                    ->default(null),
                Forms\Components\TextInput::make('facebook')
                ->label('فيسبوك')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('telegram')
                    ->label('تليجرام')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('website')
                    ->label('الموقع الالكتروني')
                    ->maxLength(255)
                    ->url()
                    ->default(null),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->label('رقم الهاتف')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_closed')
                    ->label('التطبيق مغلق')
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('android_build_number')
                    ->numeric()
                    ->label('إصدار الاندرويد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ios_build_number')
                    ->numeric()
                    ->label('إصدار الايفون')
                    ->sortable(),
                Tables\Columns\TextColumn::make('android_link')
                    ->label('رابط الاندرويد')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ios_link')
                    ->label('رابط الايفون')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('is_closed')
                    ->label('التطبيق مغلق')
                    ->formatStateUsing(fn($state) => $state ? 'مغلق' : 'مفتوح')
                    ->colors([
                        'danger' => '1',
                        'success' => '0',
                    ]),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('واتساب')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('facebook')
                    ->label('فيسبوك')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telegram')
                    ->label('تليجرام')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('website')
                    ->label('الموقع الالكتروني')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('رقم الهاتف')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ اخر تعديل')
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
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }


    public static function canCreate(): bool
    {
        return Setting::count() === 0;
    }

    public static function canDelete(Model $model): bool
    {
        return Setting::count() === 0;
    }
}
