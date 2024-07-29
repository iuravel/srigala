<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BentukSuratResource\Pages;
use App\Filament\Resources\BentukSuratResource\RelationManagers;
use App\Models\BentukSurat;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BentukSuratResource extends Resource
{
    protected static ?string $model = BentukSurat::class;
    
    protected static ?string $slug = 'bentuk-surat';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Bentuk Surat';
    protected static ?int $navigationSort = 4;
    protected static ?string $label = 'Bentuk Surat';

    public static function form(Form $form): Form
    {
        
        $tembusan = ['test 1', 'test 2', 'test 3'];
        return $form
            ->schema([
                Section::make([
                    Grid::make(['default' => 1,'sm' => 2,'md' => 3,'lg' => 4,'xl' => 6,'2xl' => 8,]),
                    Grid::make(4)->schema([
                        Cluster::make([
                            Forms\Components\TextInput::make('kotama')
                                ->prefix('KOTAMA')
                                ->required()->maxLength(255),
                            Forms\Components\TextInput::make('satminkal')
                                ->prefix('SATMINKAL')
                                ->required()->maxLength(255),
                        ])->columnSpan(4)->label('KOPSTUK SURAT'), //end cluster
    
                        Forms\Components\RichEditor::make('judul_kgb_mil')
                            ->label('JUDUL SURAT (MIL)')
                            ->toolbarButtons(['bold','italic','redo','underline','undo'])
                            ->columnSpan(2),
                        Forms\Components\RichEditor::make('judul_kgb_asn')
                        ->label('JUDUL SURAT (ASN)')
                            ->toolbarButtons(['bold','italic','redo','underline','undo'])
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('ket_kgb_asn')
                            ->label('DASAR SURAT (ASN)')
                            ->maxLength(255)
                            ->columnSpan(4),
    
                        Forms\Components\RichEditor::make('jabatan')
                            ->label('JABATAN PENANDATANGAN')
                            ->toolbarButtons(['bold','italic','redo','underline','undo'])
                            ->columnSpan(2),
                        Forms\Components\ToggleButtons::make('hari_ini')
                            ->label('TANGGAL SURAT (AUTO)')
                            ->boolean()
                            ->default(0)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('nama')
                            ->label('NAMA PEJABAT')
                            ->maxLength(255)
                            ->columnStart(1)->columnSpan(2),
                        Forms\Components\TextInput::make('pangkat')
                            ->label('PANGKAT, KORP & NRP')
                            ->maxLength(255)
                            ->columnSpan(2),
    
                        Forms\Components\TagsInput::make('tembusan')
                            ->label('TEMBUSAN')->placeholder('Buat baru')
                            ->separator(',')->splitKeys(['Tab', ','])->reorderable()
                            ->nestedRecursiveRules([ 'max:255' ])
                            ->columnSpan(4),
                    ])
                ]),
                
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->paginated(false)
            ->columns([
                //Tables\Columns\TextColumn::make('kotama')->label('KOTAMA'),
                Tables\Columns\TextColumn::make('satminkal')->label('SATMINKAL'),
                Tables\Columns\TextColumn::make('nama')->label('PENANDATANGAN'),
                Tables\Columns\TextColumn::make('pangkat')->label('PANGKAT/ KORP/ NRP'),
                Tables\Columns\TextColumn::make('tembusan')->label('TEMBUSAN'),
                Tables\Columns\IconColumn::make('hari_ini')->label('TANGGAL')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
           
    }
    
    public static function getPages(): array
    {
        $record = BentukSurat::find(1);
        return [
            'index' => Pages\ListBentukSurat::route('/'),
            //'create' => Pages\CreateBentukSurat::route('/create'),
            //'view' => Pages\ViewBentukSurat::route('/{record}'),
            'edit' => Pages\EditBentukSurat::route('/{record}/edit'),
        ];
    }


}
