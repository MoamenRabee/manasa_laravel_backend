<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'مستخدم';

    protected static ?string $pluralLabel = 'المستخدمين';


    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('الاسم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('البريد الالكتروني')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('نوع المستخدم')
                    ->options([
                        'admin' => 'مشرف',
                        'assistant' => 'مستخدم',
                    ])
                    ->placeholder('اختر نوع المستخدم')
                    ->dehydrated(fn($state) => filled($state))
                    ->hidden(function ($record) {
                        if (!$record) return false; 
                        return $record->type === 'admin' && $record->id === auth()->id();
                    }),
                
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('كلمة المرور')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? \Illuminate\Support\Facades\Hash::make($state) : null)
                    ->afterStateHydrated(fn($record, callable $set) => $set('password', ''))
                    ->required(fn (Page $livewire) => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(fn($state) => filled($state)),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->label('الاسم'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->label('البريد الالكتروني'),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('نوع المستخدم')
                    ->colors([
                        'primary' => 'admin',
                        'success' => 'assistant',
                    ])
                    ->formatStateUsing(fn (string $state): string => [
                        'admin' => 'مشرف',
                        'assistant' => 'مستخدم',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i A')
                    ->sortable()
                    ->label('تاريخ الانشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i A')
                    ->label('تاريخ التعديل')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }



    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->type === 'admin';
    }


}
