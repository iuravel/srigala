<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiBerkalaAsnResource\Pages;
use App\Filament\Resources\GajiBerkalaAsnResource\RelationManagers;
use App\Models\BentukSurat;
use App\Models\GajiBerkalaAsn;
use App\Models\GapokAsn;
use App\Models\Golongan;
use App\Models\JenisKelamin;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GajiBerkalaAsnResource extends Resource
{
    protected static ?string $model = GajiBerkalaAsn::class;

    protected static ?string $slug = 'kgb-asn';
    protected static ?string $navigationGroup = 'Gaji Berkala';
    protected static ?string $modelLabel = 'KGB ASN';  
    protected static ?string $navigationLabel = 'KGB ASN';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//start schema
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                Grid::make(['default' => 1,'sm' => 2,'md' => 3,'lg' => 4,'xl' => 6,'2xl' => 8,]), //set default
                    Section::make('Data Personel')
                    ->columnSpanFull()
                    ->id('dataPersonel')
                    ->iconColor('info')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Grid::make(6)->schema([
                            TextInput::make('nama')->label('Nama')->prefix('NAMA')->hiddenLabel()
                            ->maxLength(255)->required()
                            ->columnSpan(2),
                            TextInput::make('nip')->label('nip')->prefix('NIP')->hiddenLabel()
                            ->integer()->mask('9999999999999999999999999')->required()
                            ->columnSpan(2),
                            TextInput::make('karpeg')->label('Karpeg')->prefix('KARPEG')->hiddenLabel()
                            ->maxLength(255)->required()
                            ->columnSpan(2)
                        ]),
                        Grid::make(6)->schema([
                            Radio::make('jenis_kelamin_id')->inline()->inlineLabel(true)->hiddenLabel()
                            ->required()
                            ->options(['1'=>'Laki-laki', '2'=>'Perempuan'])
                            ->columnSpan(2),
                            Cluster::make([
                                TextInput::make('tempat_lahir')->label('Tempat Lahir')->prefix('TEMPAT LAHIR')->maxLength(255)->required()
                                ,
                                DatePicker::make('tanggal_lahir')->prefixIcon('heroicon-m-calendar')->prefix('TGL LAHIR')
                                ->native(false)->displayFormat('d-m-Y')->inlineLabel()->hiddenLabel()
                                
                            ])->columnSpan(4)->hiddenLabel()
                            ,
                        ]),
                        Grid::make(6)->schema([
                            TextInput::make('jabatan')->hiddenLabel()->prefix('JABATAN')
                            ->maxLength(255)
                            ->columnSpan(3),
                            TextInput::make('kesatuan')->hiddenLabel()->prefix('KESATUAN')->default('Paldam III/Siliwangi')
                            ->maxLength(255)
                            ->columnSpan(3)
                        ]),
                        Grid::make(6)->schema([
                            DatePicker::make('tmt_cpns')->hiddenLabel()->prefix('TMT CPNS')->prefixIcon('heroicon-m-calendar')->displayFormat('d-m-Y')->native(false)
                            ->required()
                            ->columnSpan(2),
                            // DatePicker::make('tmt_golongan')->required()->label('TMT Golongan')->prefixIcon('heroicon-m-calendar')->displayFormat('d-m-Y')->native(false)->required()
                            // ->columnSpan(2),
                        ]),
                    ]),

                    Section::make('Keadaan Lama')
                    ->columnSpanFull()
                    ->id('keadaanLama')
                    ->icon('heroicon-m-battery-50')
                    ->iconColor('warning')
                    ->collapsible()
                    //->collapsed()
                    ->schema (
                        [
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('tahun_mks_lama')->label('M K S')->mask('99')->suffix('Thn ')->numeric()->maxLength(100)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        if ( empty($get('tahun_mkg_lama')) ) {
                                            $set('tahun_mkg_lama', $state) ?? null; }
                                    }),
                                    TextInput::make('bulan_mks_lama')->label(',')->mask('99')->suffix('Bln')->numeric()->maxLength(100)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        if ( empty($get('bulan_mkg_lama')) ) {
                                            $set('bulan_mkg_lama', $state) ?? null; }
                                    }),
                                ])->columnSpan(2)->label('MKS'),
                                Cluster::make([
                                    TextInput::make('tahun_mkg_lama')->mask('99')->suffix('Thn')->numeric()->maxLength(100)->columnSpan(1)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        self::updateByMkg($state, $get, $set);
                                    })
                                    ,
                                    TextInput::make('bulan_mkg_lama')->mask('99')->suffix('Bln')->numeric()->maxLength(100)->columnSpan(1),
                                ])->columnSpan(2)->label('MKG'),

                                ]),
                            Grid::make(6)->schema([
                                    Select::make('golongan_lama_id')->hiddenLabel()->prefix('GOLONGAN')
                                    ->relationship(name: 'golonganLama', titleAttribute: 'nama', modifyQueryUsing:fn (Builder $query) => $query->orderBy('id'))->native(false)->columnSpan(2)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) 
                                    {
                                        self::getKeadaanLama($state, $get, $set);
                                    })
                                    ->suffixAction(
                                        Action::make('charge')->label('Charge')
                                        ->icon('heroicon-o-arrows-up-down')->color('success')
                                        ->action(function ($state, get $get, set $set) {
                                            if ( (isset($state) && !empty($get('golongan_lama_id'))) )
                                            {
                                                self::updateFieldKeadaanLama($state, $get, $set) ?? null;    
                                            }
                                        })
                                    ),
                                    TextInput::make('gaji_pokok_lama')->hiddenLabel()->prefix('GAJI POKOK')->mask('99999999999999999999')->numeric()->columnSpan(2)
                                    ->live()->afterStateUpdated(function ($state, get $get, set $set) { } ),
                                ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('skep_lama')->prefix('NO. SKEP')->hiddenLabel()->maxLength(255),
                                    DatePicker::make('tmt_kgb_lama')->prefixIcon('heroicon-m-calendar')->prefix('TMT')->hiddenLabel()->native(false)->displayFormat('d-m-Y'),
                                ])->columnSpan(4)->hiddenLabel(),
                                    DatePicker::make('tmt_kgb_yad_lama')->prefixIcon('heroicon-o-bell')->prefix('YAD')->hiddenLabel()->native(false)->displayFormat('d-m-Y')->columnSpan(2),
                                ])
                            ]
                        ),

                    Section::make('Keadaan Baru')
                    ->columnSpanFull()
                        ->id('keadaanBaru')
                        ->iconColor('success')
                        ->icon('heroicon-m-battery-100')
                        ->collapsible()
                        //->collapsed()
                        ->schema([
                            Grid::make(6)->schema([
                                Select::make('golongan_baru_id')->hiddenLabel()->prefix('GOLONGAN')
                                ->relationship(name: 'golonganBaru', titleAttribute: 'nama', modifyQueryUsing:fn (Builder $query) => $query->orderBy('id'))->native(false)->columnSpan(2)
                                ->disableOptionWhen(fn (string $value, get $get): bool => $value < $get('golongan_lama_id'))
                                ->suffixAction(
                                    Action::make('charge')->label('Charge')
                                    ->icon('heroicon-o-arrows-up-down')->color('warning')
                                    ->action(function ($state, get $get, set $set) {
                                        if ( isset($state) && !empty($get('golongan_lama_id')) && !empty($get('tahun_mks_lama')) ) 
                                        {
                                            self::getKeadaanBaru($state, $get, $set);
                                        }
                                    })
                                ),
                            ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('tahun_mks_baru')->mask('99')->suffix('Thn')->numeric()->maxLength(100),
                                    TextInput::make('bulan_mks_baru')->mask('99')->suffix('Bln')->numeric()->maxLength(100),
                                ])->label('MKS')->columnSpan(2),
                                Cluster::make([
                                    TextInput::make('tahun_mkg_baru')->mask('99')->suffix('Thn')->numeric()->maxLength(100),
                                    TextInput::make('bulan_mkg_baru')->mask('99')->suffix('Bln')->numeric()->maxLength(100),
                                ])->label('MKG')->columnSpan(2),
                                TextInput::make('gaji_pokok_baru')->label('GAJI POKOK')->mask('9999999999999999999999999')->numeric()->columnSpan(2)
                            ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('skep_baru')->prefix('NO. SKEP')->hiddenLabel()->rules(['max:255']),
                                    DatePicker::make('tmt_kgb_baru')->prefixIcon('heroicon-m-calendar')->prefix('TMT')->hiddenLabel()->native(false)->displayFormat('d-m-Y'),
                                ])->columnSpan(4)->hiddenLabel(),
                                DatePicker::make('tmt_kgb_yad_baru')->prefixIcon('heroicon-o-bell-alert')->prefix('YAD')->hiddenLabel()->native(false)->displayFormat('d-m-Y')->columnSpan(2),
                            ]),
                            Grid::make(6)->schema([
                                Textarea::make('keterangan')->label('KETERANGAN')->rules(['max:255'])->columnSpan(4)
                            ])
                    ]),
//END SCHEMA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->actions([], position: ActionsPosition::BeforeColumns)
            ->columns([

                ColumnGroup::make('PERSONEL', [
                    //TextColumn::make('index')->rowIndex(),
                    TextColumn::make('nama')->label('NAMA')->searchable()->sortable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('nip')->label('NIP')->searchable()->copyable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('karpeg')->label('KARPEG')->searchable()->copyable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('jenisKelamin.singkatan')->label('JK')->sortable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('jabatan')->label('JABATAN')->searchable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('kesatuan')->label('KESATUAN')->searchable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('full_ttl')->label('TEMPAT, TGL LAHIR')
                        ->formatStateUsing(fn ($record) => "{$record->tempat_lahir}".', '."{$record->tanggal_lahir}".''),
                    // TextColumn::make('tempat_lahir')->label('TEMPAT LAHIR')->searchable()->sortable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    // TextColumn::make('tanggal_lahir')->date('d-m-Y')->label('TGL LAHIR')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('tmt_cpns')->date('d-m-Y')->label('TMT CPNS')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                ])->alignment(Alignment::Center),
                ColumnGroup::make('KEADAAN LAMA', [
                    TextColumn::make('full_golongan_lama')->label('Golongan')
                    ->formatStateUsing(fn ($record) => "{$record->golonganLama->uraian}".' - '."{$record->golonganLama->nama}".'')
                    ->sortable()->searchable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('full_mks_lama')->label('MKS')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mks_lama}".' Thn, '."{$record->tahun_mks_lama}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('full_mkg_lama')->label('MKG')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mkg_lama}".' Thn, '."{$record->tahun_mkg_lama}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('gaji_pokok_lama')->label('GAJI POKOK')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                    TextColumn::make('tmt_kgb_lama')->label('TMT KGB')->size(TextColumnSize::ExtraSmall)->color('success')
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('tmt_kgb_yad_lama')->label('TMT YAD')->size(TextColumnSize::ExtraSmall)->color('info')
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('skep_lama')->label('NO. SKEP')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                ])->alignment(Alignment::Center),
                
                ColumnGroup::make('KEADAAN BARU', [
                    TextColumn::make('full_golongan_baru')->label('Golongan')
                    ->formatStateUsing(fn ($record) => "{$record->golonganBaru->uraian}".' - '."{$record->golonganBaru->nama}".'')
                    ->sortable()->searchable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('full_mks_baru')->label('MKS')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mks_baru}".' Thn, '."{$record->bulan_mks_baru}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('full_mkg_baru')->label('MKG')->size(TextColumnSize::ExtraSmall)
                            ->formatStateUsing(fn ($record) => "{$record->tahun_mkg_baru}".' Thn, '."{$record->bulan_mkg_baru}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('gaji_pokok_baru')->label('GAJI POKOK')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                    TextColumn::make('tmt_kgb_baru')->label('TMT KGB')->size(TextColumnSize::ExtraSmall)
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)->color('danger')
                        ->sortable(),
                    TextColumn::make('tmt_kgb_yad_baru')->label('TMT YAD')->size(TextColumnSize::ExtraSmall)
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('skep_baru')->label('NO. SKEP')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                    TextColumn::make('keterangan')->label('KET')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                ])
                ->alignment(Alignment::Center),

              
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Mulai Tanggal')->native(false)->displayFormat('d-m-Y'),
                        DatePicker::make('created_until')->label('Sampai Tanggal')->native(false)->displayFormat('d-m-Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tmt_kgb_lama', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tmt_kgb_lama', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Mulai dari: ' . Carbon::parse($data['created_from'])->isoFormat('D MMMM Y'))
                                ->removeField('from');
                        }
                 
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Sampai: ' . Carbon::parse($data['created_until'])->isoFormat('D MMMM Y'))
                                ->removeField('until');
                        }
                 
                        return $indicators;
                    }),
                    
                    Tables\Filters\TrashedFilter::make()->native(false)
            ])
            ->actions([
                
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->size(ActionSize::Medium)->slideOver()
                        ->icon('heroicon-o-viewfinder-circle')->color('info'),
                    Tables\Actions\Action::make('Unduh')
                        ->action(function ($record) {
                            $currentRecord = $record;
                            $surat = BentukSurat::find(1);
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.kgb-asn-pdf', ['record' => $currentRecord, 'surat' => $surat]);
                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'kgb-'.strtolower(strval($record->nama)).'-'.strval($record->nip).'.pdf');
                        })
                        ->icon('heroicon-m-arrow-down-tray')->size(ActionSize::Medium)->color('primary'),
                    Tables\Actions\EditAction::make()->size(ActionSize::Medium)->slideOver()
                        ->icon('heroicon-o-pencil-square')->color('warning'),
                    Tables\Actions\DeleteAction::make()->size(ActionSize::Medium)
                        ->icon('heroicon-o-archive-box-x-mark')->color('destroy'),
                ])->color('gray'),
                
                // TEST FORMAT
                Tables\Actions\Action::make('Cetak')
                    ->url(fn (GajiBerkalaAsn $record) => route('kgb.asn.preview', $record))
                    ->iconButton()->icon('heroicon-o-printer')->size(ActionSize::ExtraSmall)->color('primary')
                    ->openUrlInNewTab(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListGajiBerkalaAsn::route('/'),
            'create' => Pages\CreateGajiBerkalaAsn::route('/create'),
            'view' => Pages\ViewGajiBerkalaAsn::route('/{record}'),
            'edit' => Pages\EditGajiBerkalaAsn::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function getKeadaanLama($state, get $get, set $set) : void
    {
        $field = [ 'tmt_cpns' => $get('tmt_cpns') ];
        $old_field = [
            'golongan' => $get('golongan_lama_id') ?? null,
            'thn_mks' => $get('tahun_mks_lama') ?? null,
            'bln_mks' => $get('bln_mks_lama') ?? null,
            'thn_mkg' => $get('thn_mkg_lama') ?? null,
            'bln_mkg' => $get('bln_mkg_lama') ?? null,
            'gapok' => $get('gaji_pokok_lama') ?? null,
            'no_skep' => $get('skep_lama') ?? null,
            'tmt_kgb' => $get('tmt_kgb_lama') ?? null,
            'tmt_kgb_yad' => $get('tmt_kgb_yad_lama') ?? null
        ];

        $gapok = GapokAsn::get();
        
        if (!empty($field['tmt_cpns'] && !empty($old_field['thn_mks']))) {

            $query = $gapok->where('golongan_id', $state)
                ->where('masa_kerja', $old_field['thn_mks'])
                ->first();
            $set('gaji_pokok_lama', $query->gaji ?? null);

            $set('tmt_kgb_lama', Carbon::parse($field['tmt_cpns'])->addYears((int)$old_field['thn_mks'])) ?? null;
            $set('tmt_kgb_yad_lama', Carbon::parse($field['tmt_cpns'])->addYears((int)$old_field['thn_mks'] + (int)2 )) ?? null;
            
            if (empty($get('golongan_baru_id'))){
                $set('golongan_baru_id', $old_field['golongan']);
            }
        }
        
    }
    public static function updateFieldKeadaanLama($state, get $get, set $set) : void
    {
        $set('tahun_mks_lama', $get('tahun_mks_baru') ?? null);
        $set('bulan_mks_lama', $get('bulan_mks_baru') ?? null);
        $set('tahun_mkg_lama', $get('tahun_mkg_baru') ?? null);
        $set('bulan_mkg_lama', $get('bulan_mkg_baru') ?? null);
        $set($state, $get('golongan_baru_id') ?? null);
        $set('gaji_pokok_lama', $get('gaji_pokok_baru') ?? null);
        $set('skep_lama', $get('skep_baru') ?? null);
        $set('tmt_kgb_lama', $get('tmt_kgb_baru') ?? null);
        $set('tmt_kgb_yad_lama', $get('tmt_kgb_yad_baru') ?? null);
    }
    public static function updateByMkg($state, get $get, set $set) : void
    {
        if (!empty($state)) 
        {
            $gapok = GapokAsn::get();
            $query = $gapok->where('golongan_id', $get('golongan_lama_id'))
                ->where('masa_kerja', $state)
                ->first();

            $set('gaji_pokok_lama', $query->gaji ?? null);
        }
    }
    public static function getKeadaanBaru($state, get $get, set $set) : void
    {


        if ( empty($get('tahun_mks_lama') && empty($get('tahun_mkg_lama'))) && empty($get('golongan_lama_id')) && empty($get('tmt_kgb_lama')) && empty($get('tmt_kgb_yad_lama')) )
        {
            Notification::make()
            ->title('Keadaan Lama Tidak Valid.')
            ->warning()
            ->color('destroy')
            ->seconds(3)
            ->send();

            return;
        }

        $golongan_lama = $get('golongan_lama_id');
        $gapok = GapokAsn::get();
        if ($state <= (int)6 || $golongan_lama >= (int)7 && $state <= (int)12 || $golongan_lama >= (int)13) 
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //DEFAULT +2 TAHUN
            $mks_baru = $get('tahun_mks_lama') + (int)2;
            $mkg_baru = $get('tahun_mkg_lama') + (int)2;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('golongan_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        elseif ($golongan_lama <= (int)6 && $state == (int)7)
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //TAMTAMA KE BINTARA SERDA MKG -4 Tahun
            $mks_baru = $get('tahun_mks_lama') - (int)4;
            $mkg_baru = $get('tahun_mkg_lama') - (int)4;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('golongan_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        elseif ($golongan_lama >= (int)7 && $state == (int)13)
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //BINTARA KE PERWIRA - LETDA MKG -5 Tahun
            $mks_baru = $get('tahun_mks_lama') - (int)5;
            $mkg_baru = $get('tahun_mkg_lama') - (int)5;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('golongan_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        
        

    } 
}
