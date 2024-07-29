<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GolonganResource\Pages;
use App\Filament\Resources\GolonganResource\RelationManagers;
use App\Models\Golongan;
use App\Models\Grup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GolonganResource extends Resource
{
    protected static ?string $model = Golongan::class;

    protected static ?string $slug = 'golongan';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Golongan';
    protected static ?int $navigationSort = 2;
    protected static ?string $label = 'Data Golongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('uraian')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('grup_id')
                    ->label('Grup')
                    ->options(Grup::all()->pluck('nama', 'id'))
                    //->native(false)
                    ->required()
                    ->default(2)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('uraian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grup.nama')
                    ->label('Grup')
                    ->searchable(),
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel(),
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    // ->before(
                    //     function ($records, Tables\Actions\DeleteBulkAction $action) {
                    //         $ids = $records->pluck('id')->toArray();
                    //         $exists = GapokMiliter::whereIn('pangkat_id', $ids)->exists();
                    //         if ($exists) {
                    //             Notification::make()
                    //                 ->title('Error!')
                    //                 ->body("Anda tidak dapat menghapus pangkat ini karena masih terikat data gaji pokok militer.")
                    //                 ->status('danger')
                    //                 ->send();
                    //             $action->cancel();
                    //         }
                    //     }
                    // ),
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
            'index' => Pages\ListGolongan::route('/'),
            //'create' => Pages\CreateGolongan::route('/create'),
            //'view' => Pages\ViewGolongan::route('/{record}'),
            //'edit' => Pages\EditGolongan::route('/{record}/edit'),
        ];
    }
       
}
