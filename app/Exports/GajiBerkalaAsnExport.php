<?php

namespace App\Exports;

use App\Models\GajiBerkalaAsn;
use App\Models\JenisKelamin;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GajiBerkalaAsnExport implements  FromQuery, Responsable, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;
    private $fileName = 'gaji-berkala-asn.xlsx';
    private $writerType = \Maatwebsite\Excel\Excel::XLSX;
    
    public function query()
    {
        return GajiBerkalaAsn::query();
    }
    public function headings(): array
    {
        return [
            'nama','nip','karpeg','jk','tempat_lahir','tgl_lahir','tmt_cpns','jabatan','kesatuan',
            
            'golongan_terakhir','mks_terakhir_thn','mks_terakhir_bln','mkg_terakhir_thn','mkg_terakhir_bln',
            'gaji_pokok_terakhir','skep_lama','tmt_kgb_terakhir', 'tmt_yad_terakhir',
            
            'golongan_baru','mks_baru_thn','mks_baru_bln','mkg_baru_thn','mkg_baru_bln',
            'gaji_pokok_baru','skep_baru','tmt_kgb_baru', 'tmt_yad_baru',
            'keterangan'
        ];
    }
    public function map($record): array
    {
        return [
            $record->nama, //A
            strval($record->nip), //B
            strval($record->karpeg), //C
            $this->getJenisKelamin($record->jenis_kelamin_id), //D
            $record->tempat_lahir, //E
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tanggal_lahir), //F
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tmt_cpns), //G
            $record->jabatan, //H
            $record->kesatuan, //I
            $this->getGolonganLama($record->golongan_lama_id), //J
            $record->tahun_mks_lama, //K
            $record->bulan_mks_lama, //L
            $record->tahun_mkg_lama, //M
            $record->bulan_mkg_lama, //N
            $record->gaji_pokok_lama, //O
            $record->skep_lama, //P
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tmt_kgb_lama), //Q
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tmt_kgb_yad_lama), //R
            $this->getGolonganBaru($record->golongan_baru_id), //S
            $record->tahun_mks_baru, //T
            $record->bulan_mks_baru, //U
            $record->tahun_mkg_baru, //V
            $record->bulan_mkg_baru, //W
            $record->gaji_pokok_baru, //X
            $record->skep_baru, //Y
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tmt_kgb_baru), //Z
            \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($record->tmt_kgb_yad_baru), //AA
            $record->keterangan, //BB
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, //NAMA
            'B' => "@", //NIP
            'C' => NumberFormat::FORMAT_TEXT, //KARPEG
            'D' => NumberFormat::FORMAT_TEXT, //JK
            'E' => NumberFormat::FORMAT_TEXT, //TMPAT LAHIR
            'F' => 'dd/mm/yyyy', //TGL LAHIR
            'G' => 'dd/mm/yyyy', //TMT CPNS
            'H' => NumberFormat::FORMAT_TEXT, //JABATAN
            'I' => NumberFormat::FORMAT_TEXT, //KESATUAN
            'J' => NumberFormat::FORMAT_TEXT, //GOL LAMA
            'K' => NumberFormat::FORMAT_GENERAL, //THN MKS
            'L' => NumberFormat::FORMAT_GENERAL, //BLN MKS
            'M' => NumberFormat::FORMAT_GENERAL, //THN MKG
            'N' => NumberFormat::FORMAT_GENERAL, //BLN MKG
            'O' => '#,##0', //GAJI LAMA 
            'P' => NumberFormat::FORMAT_TEXT, //SKEP LAMA
            'Q' => 'dd/mm/yyyy', //TMT KGB
            'R' => 'dd/mm/yyyy', //TMT KGB YAD
            'S' => NumberFormat::FORMAT_TEXT, //GOL BARU
            'T' => NumberFormat::FORMAT_GENERAL, //THN MKS
            'U' => NumberFormat::FORMAT_GENERAL, //BLN MKS
            'V' => NumberFormat::FORMAT_GENERAL, //THN MKG
            'W' => NumberFormat::FORMAT_GENERAL, //BLN MKG
            'X' => '#,##0', //GAJI BARU
            'Y' => NumberFormat::FORMAT_TEXT, //SKEP
            'Z' => 'dd/mm/yyyy', //TMT KGB
            'AA' => 'dd/mm/yyyy', //TMT KGB YAD
            'BB' => NumberFormat::FORMAT_TEXT, //KET
        ];
    }

    public function dateFormat($dateString): string
    {
        if (isset($dateString)) 
        {
            return Carbon::createFromFormat('Y-m-d', $dateString)->format('d-m-Y');
        } 
        return '';       
    }
    public function getJenisKelamin($jenis_kelamin_id)
    {
        return isset($jenis_kelamin_id) ? JenisKelamin::find($jenis_kelamin_id)->singkatan : '';
        
    }
    public function getGolonganLama($record)
    {
        $query = GajiBerkalaAsn::where('golongan_lama_id', $record)->first();
        return $query->golonganLama->nama;
    }
    public function getGolonganBaru($record)
    {
        $query = GajiBerkalaAsn::where('golongan_baru_id', $record)->first();
        return $query->golonganLama->nama;
    }
}
