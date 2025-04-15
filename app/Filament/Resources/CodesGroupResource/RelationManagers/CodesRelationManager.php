<?php

namespace App\Filament\Resources\CodesGroupResource\RelationManagers;
use App\Exports\CodesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Code;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CodesRelationManager extends RelationManager
{
    protected static string $relationship = 'codes';


    protected static ?string $modelLabel = 'كود';

    protected static ?string $title = 'الاكواد في المجموعة';

    protected static ?string $pluralLabel = 'كود';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('الكود')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('is_used')
                    ->label('مستخدم')
                    ->colors([
                        'success' => fn ($state): bool => $state,
                        'danger' => fn ($state): bool => !$state,
                    ])
                    ->formatStateUsing(fn ($state): string => $state ? 'نعم' : 'لا'),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('اسم الطالب')
                    ->default('لا يوجد'),
            ])
            ->filters([
              
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportCodes')
                    ->label('📤 تصدير الأكواد Excel')
                    ->action(function () {
                        $group = $this->ownerRecord;

                        return Excel::download(
                            new CodesExport($group->id),
                            'codes_group_' . $group->id . '.xlsx'
                        );
                    }),

                Tables\Actions\Action::make('createCodes')
                    ->label('إضافة أكواد')
                    ->form([
                        Forms\Components\TextInput::make('count')
                            ->label('عدد الأكواد')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label('السعر')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $count = $data['count'];
                        $price = $data['price'];
                        $group = $this->ownerRecord;

                        for ($i = 0; $i < $count; $i++) {
                            Code::create([
                                'code' => str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                                'price' => $price,
                                'codes_group_id' => $group->id,
                            ]);
                        }

                        Notification::make()
                            ->title("✅ تم إنشاء $count كود جديد")
                            ->success()
                            ->send();
                    }),
            
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


}
