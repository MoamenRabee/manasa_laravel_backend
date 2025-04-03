<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Center;
use App\Models\Classroom;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'طالب';

    protected static ?string $pluralLabel = 'الطلاب';


    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('الاسم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->label('رقم الهاتف')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('parent_phone')
                    ->tel()
                    ->required()
                    ->label('رقم هاتف ولي الأمر')
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('كلمة المرور')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? \Illuminate\Support\Facades\Hash::make($state) : null)
                    ->afterStateHydrated(fn($record, callable $set) => $set('password', ''))
                    ->required(fn (Page $livewire) => $livewire instanceof Pages\CreateStudent)
                    ->dehydrated(fn($state) => filled($state)),
                Forms\Components\Select::make('classroom_id')
                    ->required()
                    ->label('الفصل')
                    ->searchable(true)
                    ->options(Classroom::all()->pluck('name', 'id')),
                Forms\Components\Select::make('center_id')
                    ->required()
                    ->label('المركز')
                    ->searchable(true)
                    ->options(Center::all()->pluck('name', 'id')),
                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->directory(directory: 'students')
                    ->image(),
                Forms\Components\TextInput::make('device_id')
                    ->label('رمز التعريفي للهاتف')
                    ->maxLength(255)
                    ->default(null)
                    ->suffixAction(fn ($state, callable $set) => Action::make('clear')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn () => $set('device_id', ''))
                    ),
                Forms\Components\Checkbox::make('activeted')
                    ->label('تفعيل الحساب')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular()
                    ->width('50px')
                    ->height('50px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent_phone')
                    ->label('رقم هاتف ولي الأمر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('الصف الدراسي')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('center.name')
                    ->label('المركز')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('activeted')
                    ->label('تفعيل الحساب')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'مفعل' : 'غير مفعل';
                    })
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),
                Tables\Columns\TextColumn::make('device_id')
                    ->label('رمز تعريف الهاتف')
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
                Tables\Filters\SelectFilter::make('classroom_id')
                    ->label('الفصل')
                    ->options(Classroom::all()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('center_id')
                    ->label('المركز')
                    ->options(Center::all()->pluck('name', 'id')),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

}
