<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <style>
            @font-face {
                font-family: 'customfont';
                src: url('{{ storage_path('fonts/arial.ttf') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            @page {
                size: A4;
            }
            .kertas-a4 {
                margin-top: 1.40cm;; /* 3.0cm sih */
                margin-bottom: 1.27cm;
                margin-left: 2.24cm;
                margin-right: 1.40cm;
            }
            body {
                font-family: 'customfont', sans-serif;
                font-size: 14px;
            }
            div {
                margin: 0;
                padding: 0;
                box-sizing: border-box; /* Ensures padding and border are included in element's total width and height */
            }
            table {
                width: 100%;
                border-collapse: collapse; /* Ensures that borders between cells are collapsed */
                border-spacing: 0; /* Removes any spacing between cells */
            }
            tbody, tr {
                margin: 0; /* Ensure no margin */
                padding: 0; /* Ensure no padding */
            }
            td, th {
                padding: 0; /* Adjust as needed */
                margin: 0; /* Ensure no margin */
            }
            .general td {
                padding-left: 5px;
                padding-right: 5px;
                border-bottom: thin solid #000;
            }
            .float-right {
                float: right;
                line-height: 1;
                width: auto;
                height: auto;
                margin: 0; /* Optional: Adds space between floated elements */
            }
            .float-left {
                float: left;
                width: auto;
                height: auto;
                line-height: 1;
                font-size: 14px;
                margin: 0; /* Optional: Adds space between floated elements */
            }
            .clear-div {
                clear: both;
                line-height: 1;
                margin: 0;
                padding: 0;
            }
            .blank-spacer {
                padding-left: 20px;
                padding-right: 20px;
            }
            .nomor {
                text-align: left;
                vertical-align: top;
                width: 10px;
            }
            .colon-mark {
                text-align: center;
                vertical-align: top;
                width: 10px;
            }
            .sub-column {
                text-align: left;
                vertical-align: top;
                width: 70px;
            }
            .record-field {
                text-align: left;
                vertical-align:middle;
                width: 155px;
            }
            .column-field {
                text-align: left;
                vertical-align: top;
                width: auto;
            }
        </style>
        
    </head>
    <body>
        
        <div class="kertas-a4">
            <div class="float-left" style="text-align:center; border-bottom:thin solid #000;">{{ $surat->kotama ?? 'KOMANDO DAERAH MILITER III/SILIWANGI' }}<br>{{ $surat->satminkal ?? 'PERALATAN' }}</div>
            <div class="clear-div">&nbsp;</div>
            {{-- <div class="float-right" style="text-align:left;">PEMBERIAN KENAIKAN GAJI BERKALA<br> MENURUT PERATURAN PEMERINTAH<br> NOMOR 6 TAHUN 2024 TANGGAL 01-01-2024</div> --}}
            <div class="float-right" style="text-align:left;">{!! $surat->judul_kgb_mil ?? 'PEMBERIAN KENAIKAN GAJI BERKALA<br> MENURUT PERATURAN PEMERINTAH<br> NOMOR 6 TAHUN 2024 TANGGAL 01-01-2024' !!}</div>
            <div class="float-right" style="width:12px; text-align:center;">:</div>
            <div class="float-right" style="text-align:right;">DAFTAR</div>
            <div class="clear-div">&nbsp;</div>
            <!-- START DivTable -->
            <table>
                <tbody class="general">
                <tr>
                    <td colspan="6">
                        {!! isset($record->nomor_skep_baru) ? 'NOMOR : '.$record->nomor_skep_baru : 'NOMOR : KG. 06 / <span class="blank-spacer">&nbsp;</span> / <span class="blank-spacer">&nbsp;</span> / 2024' !!}
                    </td>
                </tr>
                <tr>
                    <td class="nomor">1.</td>
                    <td class="column-field">Nama</td>
                    <td class="sub-column">(12-37)</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->nama ?? null }}</td>
                </tr>
                    <td class="nomor">2.</td>
                    <td class="column-field">NRP</td>
                    <td class="sub-column">(41-54)</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->nrp ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">3.</td>
                    <td class="column-field">Jabatan / Kesatuan</td>
                    <td class="sub-column">(41-54)</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->jabatan ?? null .' '. $record->kesatuan ?? null }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center; font-weight: bold;">KEADAAN LAMA</td>
                </tr>
                <tr>
                    <td class="nomor">4.</td>
                    <td class="column-field">Pangkat</td>
                    <td class="sub-column">(55-60)</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->pangkatLama->nama ?? null }} {{ $record->jenis_kelamin_id==2 ? '(K)':null }} {{ $record->korp->nama ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">5.</td>
                    <td class="column-field">Masa Kerja Sesungguhnya&nbsp;</td>
                    <td class="sub-column">Thn</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->tahun_mks_lama ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">&nbsp;</td>
                    <td class="column-field">&nbsp;</td>
                    <td class="sub-column">Bln</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->bulan_mks_lama ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">6.</td>
                    <td class="column-field">Masa Kerja Gaji</td>
                    <td class="sub-column">Thn</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->tahun_mkg_lama ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">&nbsp;</td>
                    <td class="column-field">&nbsp;</td>
                    <td class="sub-column">Bln</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->bulan_mkg_lama ?? null }} </td>
                </tr>
                <tr>
                    <td class="nomor">7.</td>
                    <td class="column-field" colspan="2">Gaji Pokok&nbsp;</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">Rp. {{ number_format($record->gaji_pokok_lama ?? null , 0, ',', '.'). ',-' }}</td>
                </tr>
                <tr>
                    <td class="nomor">8.</td>
                    <td class="column-field" colspan="2">Berlaku Terhitung Mulai Tanggal</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ isset($record->tmt_kgb_lama) && !empty($record->tmt_kgb_lama) ? \Carbon\Carbon::parse($record->tmt_kgb_lama)->format('d-m-Y') : ' ' }}</td>
                </tr>
                <tr>
                    <td class="nomor">9.</td>
                    <td class="column-field" colspan="2">Kenaikan Gaji Y.A.D Tanggal</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ isset($record->tmt_kgb_yad_lama) && !empty($record->tmt_kgb_yad_lama) ? \Carbon\Carbon::parse($record->tmt_kgb_yad_lama)->format('d-m-Y') : ' ' }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center; font-weight: bold;">KEADAAN BARU</td>
                </tr>
                <tr>
                    <td class="nomor">10.</td>
                    <td class="column-field">Pangkat / Korp</td>
                    <td class="sub-column">(40)</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->pangkatBaru->nama ?? null }} {{ $record->jenis_kelamin_id==2 ? '(K)':null }} {{ $record->korp->nama ?? null }}</td>
                </tr>
                <tr>
                    <td class="nomor">11.</td>
                    <td class="column-field">Masa Kerja Sesungguhnya&nbsp;</td>
                    <td class="sub-column">Thn</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ $record->tahun_mks_baru ?? null}}</td>
                    <td class="sub-column">(98)</td>
                </tr>
                <tr>
                    <td class="nomor">&nbsp;</td>
                    <td class="column-field">&nbsp;</td>
                    <td class="sub-column">Bln</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ $record->bulan_mks_baru ?? null }}</td>
                    <td class="sub-column">(99)</td>
                </tr>
                <tr>
                    <td class="nomor">12.</td>
                    <td class="column-field">Masa Kerja Gaji&nbsp;</td>
                    <td class="sub-column">Thn</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ $record->tahun_mkg_baru ?? null }}</td>
                    <td class="sub-column">(100)</td>
                </tr>
                <tr>
                    <td class="nomor">&nbsp;</td>
                    <td class="column-field">&nbsp;</td>
                    <td class="sub-column">Bln</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ $record->bulan_mks_baru ?? null }}</td>
                    <td class="sub-column">(101)</td>
                </tr>
                <tr>
                    <td class="nomor">13.</td>
                    <td class="column-field" colspan="2">Gaji Pokok</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">Rp. {{ number_format($record->gaji_pokok_baru, 0, ',', '.'). ',-' }}</td>
                </tr>
                <tr>
                    <td class="nomor">14.</td>
                    <td class="column-field" colspan="2">Berlaku Terhitung Mulai Tanggal&nbsp;</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ isset($record->tmt_kgb_baru) && !empty($record->tmt_kgb_baru) ? \Carbon\Carbon::parse($record->tmt_kgb_baru)->format('d-m-Y') : ' ' }}</td>
                    <td class="sub-column">(101-107)</td>
                </tr>
                <tr>
                    <td class="nomor">15.</td>
                    <td class="column-field" colspan="2">Kenaikan Gaji Y.A.D Tanggal</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field">{{ isset($record->tmt_kgb_yad_baru) && !empty($record->tmt_kgb_yad_baru) ? \Carbon\Carbon::parse($record->tmt_kgb_yad_baru)->format('d-m-Y') : ' ' }}</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="nomor">16.</td>
                    <td class="column-field" colspan="2">Keterangan</td>
                    <td class="colon-mark">:</td>
                    <td class="record-field" colspan="2">{{ $record->keterangan ?? null }}</td>
                </tr>
                </tbody>
                </table>
                <!-- END DivTable -->
                <div class="clear-div">&nbsp;</div>
                <div class="float-left">
                    <div style="text-align:left; border-bottom:thin solid #000;">
                        <br><br>
                        <p>Kepada Yth.</p><br><br>
                        <p>Yang Berkepentingan</p><br><br><br>
                        <p>Tembusan:</p>
                        <?php
                            $tembusan = $surat->tembusan ?? null;
                            $collection = explode(',', $tembusan);
                        ?>
                        @foreach ($collection as $key => $item)
                            {!! $key+1 .'. '. $item !!} <br>
                        @endforeach 
                    </div>
                </div>
                <div class="float-right">
                    <div style="text-align:center;">
                        <p>Bandung, {!! $surat->hari_ini == true ? 'today' : '<span class="blank-spacer">&nbsp;</span>-<span class="blank-spacer">&nbsp;</span>- 2024 </p>' !!} 
                        <p>&nbsp;</p>
                        <p> {!! $surat->jabatan ?? 'Kepala Paldam III/Siliwangi,' !!} </p>
                        <br><br><br>
                        <p>{!! $surat->nama ?? '&nbsp;' !!}</p>
                        <p>{!! $surat->pangkat ?? '&nbsp;' !!}</p>
                    </div>
                </div>
                
                
        </div>
    </body>
</html>