<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KorpResource\Pages;
use App\Filament\Resources\KorpResource\RelationManagers;
use App\Models\Korp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KorpResource extends Resource
{
    protected static ?string $model = Korp::class;

    protected static ?string $slug = 'korp';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Korp';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Data Korp';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('uraian')
                    ->maxLength(255),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('uraian')
                    ->searchable(),
            ])
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
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
            'index' => Pages\ListKorp::route('/'),
            //'create' => Pages\CreateKorp::route('/create'),
            //'view' => Pages\ViewKorp::route('/{record}'),
            //'edit' => Pages\EditKorp::route('/{record}/edit'),
        ];
    }

}
