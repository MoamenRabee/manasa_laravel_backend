<?php

namespace App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Center;
use App\Models\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViewsExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViewRelationManager extends RelationManager
{
    protected static string $relationship = 'view';


    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'مشاهدة';

    protected static ?string $title = 'المشاهدات';

    protected static ?string $pluralLabel = 'المشاهدات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('duration')
                    ->label('مدة المشاهدة')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('count')
                    ->label('عدد المشاهدات')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student.name')
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.parent_phone')
                    ->label('ولي الامر')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('مدة المشاهدة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->label('عدد المشاهدة')
                    ->sortable(),
            
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('center_id')
                    ->label('السنتر')
                    ->relationship('student.center', 'name') // student.center يجب أن تكون العلاقة متاحة
                    ->searchable() // اختياري
                    ->preload(), // اختياري
                    
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportViews')
                ->label('📤 تصدير Excel')
                ->action(function (array $data, $livewire) {
                    // نستخدم query المطبّق عليه الفلاتر
                    $query = $livewire->getFilteredTableQuery();

                    // نضمن أننا نفلتر فقط المشاهدات المرتبطة بالفيديو الحالي
                    $query->where('video_id', $this->ownerRecord->id);

                    return Excel::download(
                        new ViewsExport($query),
                        'views_export_' . now()->format('Y-m-d') . '.xlsx'
                    );
                }),


                Tables\Actions\CreateAction::make(),
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


    public function canCreate(): bool
    {
        return false;
    }


}
