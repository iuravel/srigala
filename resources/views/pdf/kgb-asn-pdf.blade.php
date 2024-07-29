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
            .br-b {
                border-bottom: #000 thin solid;
            }
            .br-l {
                border-left: #000 thin solid;
            }
            .br-r {
                border-right: #000 thin solid;
            }
            .br-t {
                border-top: #000 thin solid;
            }
            .br-lr {
                border-left: #000 thin solid;
                border-right: #000 thin solid;
            }
            .br-tb {
                border-top: #000 thin solid;
                border-bottom: #000 thin solid;
            }
            .va-top {
                vertical-align: top;
            }
            .va-mid {
                vertical-align: middle;
            }
            .tx-left {
                text-align: left;
            }
            .tx-center {
                text-align: center;
            }

        </style>
        
    </head>
    <body>
        
        <div class="kertas-a4">
            <div class="float-left" style="text-align:center; border-bottom:thin solid #000;">{{ $surat->kotama ?? 'KOMANDO DAERAH MILITER III/SILIWANGI' }}<br>{{ $surat->satminkal ?? 'PERALATAN' }}</div>
            <div class="clear-div">&nbsp;</div>
            <center> <h1 style="color: red;">UNDER CONTRUCTION</h1> </center>
            <!-- START DivTable -->
            <table>
                <tbody>
                <tr>
                <td colspan="8" class="br-b br-lr br-t">{!! $surat->judul_kgb_asn !!}</td>
                </tr>
                <tr>
                <td class="br-l">1</td>
                <td>Nama</td>
                <td class="br-l">2.</td>
                <td>Karpeg</td>
                <td class="br-l">3.</td>
                <td>Pangkat</td>
                <td class="br-l">4.</td>
                <td class="br-r">Gol/Ruang</td>
                </tr>
                <tr>
                <td rowspan="3" class="br-l">&nbsp;</td>
                <td>{{ $record->nama }}</td>
                <td rowspan="3" class="br-l">&nbsp;</td>
                <td>{{ $record->karped }}</td>
                <td rowspan="3" class="br-l">&nbsp;</td>
                <td rowspan="3">{{ $record->golonganLama->uraian }}</td>
                <td rowspan="3" class="br-l">&nbsp;</td>
                <td rowspan="3" class="br-r">{{ $record->golonganLama->nama }}</td>
                </tr>
                <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>NIP</td>
                </tr>
                <tr>
                <td>{{ $record->tempat_lahir. ', '.$record->tanggal_lahir }}</td>
                <td>{{ $record->nip }}</td>
                </tr>
                <tr>
                <td class="br-l">5.</td>
                <td>Gaji Pokok Lama</td>
                <td class="br-l">6.</td>
                <td>Kesatuan</td>
                <td class="br-l">7.</td>
                <td colspan="3" class="br-r">Surat Keputusan&nbsp;</td>
                </tr>
                <tr>
                <td class="br-l">&nbsp;</td>
                <td>{{ $record->gaji_pokok_lama }}</td>
                <td class="br-l">&nbsp;</td>
                <td>{{ $record->kesatuan }}</td>
                <td class="br-l">&nbsp;</td>
                <td colspan="3" class="br-r">a. Nomor: {{ $record->skep_lama }} <br /> b. Tanggal {{ $record->tmt_kgb_lama }}</td>
                </tr>
                <tr>
                <td class="br-l">8.</td>
                <td>Gaji Pokok Baru&nbsp;</td>
                <td class="br-l">9.</td>
                <td>Masa Kerja</td>
                <td class="br-l">10.</td>
                <td colspan="3" class="br-r">Terhitung mulai tanggal</td>
                </tr>
                <tr>
                <td rowspan="2" class="br-l br-b">&nbsp;</td>
                <td>{{ $record->gaji_pokok_baru }}</td>
                <td rowspan="2" class="br-l br-b">&nbsp;</td>
                <td>{{ $record->tahun_mkg_baru .' Thn '.$record->bulan_mkg_baru. ' Bln' }}</td>
                <td rowspan="2" class="br-l br-b">&nbsp;</td>
                <td colspan="3" class="br-r">{{ $record->tmt_kgb_baru }}</td>
                </tr>
                <tr>
                <td class="br-b">&nbsp;(number to words)</td>
                <td class="br-b">&nbsp;</td>
                <td colspan="3" class="br-r br-b">&nbsp;</td>
                </tr>
                <tr>
                <td class="br-l">11.</td>
                <td colspan="3">Saat Kenaikan Gaji yang akan datang</td>
                <td class="br-l">13.</td>
                <td colspan="3" class="br-r">Bandung, - - 2024</td>
                </tr>
                <tr>
                <td class="br-l">&nbsp;</td>
                <td colspan="3">{{ $record->tmt_kgb_yad_baru }}</td>
                <td rowspan="2" class="br-l">&nbsp;</td>
                <td colspan="3" rowspan="2" class="br-r">{{ $surat->jabatan }} <br /> <br /> <br /> {{ $surat->nama }} <br /> {{ $surat->pangkat }}</td>
                </tr>
                <tr>
                <td class="br-l">12.</td>
                <td colspan="3">Cap Dinas</td>
                </tr>
                <tr>
                <td class="br-l br-tb">14.</td>
                <td colspan="7" class="br-r br-tb">{{ $surat->ket_kgb_asn }}</td>
                </tr>
                </tbody>
                </table>
                <!-- DivTable.com -->
                <div class="clear-div">&nbsp;</div>
                <div class="float-left">
                    <div style="text-align:left; border-bottom:thin solid #000;">
                        <p>Kepada Yth.</p><br>
                        <p>Yang Berkepentingan</p><br>
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
        </div>
    </body>
</html>