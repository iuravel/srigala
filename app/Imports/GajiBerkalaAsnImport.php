<?php

namespace App\Imports;

use App\Models\GajiBerkalaAsn;
use App\Models\Golongan;
use App\Models\JenisKelamin;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GajiBerkalaAsnImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GajiBerkalaAsn([
            'nama' => $row['nama'] ?? null,
            'nip' => $row['nip'] ?? null,
            'karpeg'=> $row['karpeg'] ?? null,
            'jenis_kelamin_id'=> self::getKelaminId($row['jk'])->id ?? null,
            'tempat_lahir'=> $row['tempat_lahir'] ?? null,
            'tanggal_lahir'=> self::excelToDate($row['tgl_lahir']) ?? null,
            'tmt_cpns'=> self::excelToDate($row['tmt_cpns']) ?? null,
            'jabatan'=> $row['jabatan'] ?? null,
            'kesatuan'=> $row['kesatuan'] ?? null,

            'golongan_lama_id'=> self::getGolonganId($row['golongan_terakhir'])->id ?? null,
            'tahun_mks_lama'=> $row['mks_terakhir_thn'] ?? null,
            'bulan_mks_lama'=> $row['mks_terakhir_bln'] ?? null,
            'tahun_mkg_lama'=> $row['mkg_terakhir_thn'] ?? null,
            'bulan_mkg_lama'=> $row['mkg_terakhir_bln'] ?? null,
            'gaji_pokok_lama'=> $row['gaji_pokok_terakhir'] ?? null,
            'skep_lama'=> $row['skep_lama'] ?? null,
            'tmt_kgb_lama'=> self::excelToDate($row['tmt_kgb_terakhir']) ?? null,
            'tmt_kgb_yad_lama'=> self::excelToDate($row['tmt_yad_terakhir']) ?? null,

            'golongan_baru_id'=> self::getGolonganId($row['golongan_baru'])->id ?? null,
            'tahun_mks_baru'=> $row['mks_baru_thn'] ?? null,
            'bulan_mks_baru'=> $row['mks_baru_bln'] ?? null,
            'tahun_mkg_baru'=> $row['mkg_baru_thn'] ?? null,
            'bulan_mkg_baru'=> $row['mkg_baru_bln'] ?? null,
            'gaji_pokok_baru'=> $row['gaji_pokok_baru'] ?? null,
            'skep_baru'=> $row['skep_baru'] ?? null,
            'tmt_kgb_baru'=> self::excelToDate($row['tmt_kgb_baru']) ?? null,
            'tmt_kgb_yad_baru'=> self::excelToDate($row['tmt_yad_baru']) ?? null,

            'keterangan'=> $row['keterangan'] ?? null,

        ]);
    }
    public function excelToDate($data)
    {
        return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data));
    }
    public static function getGolonganId(string $golongan){
        return Golongan::where('nama', $golongan)->first();
    }
    public static function getKelaminId($record)
    {
        return JenisKelamin::where('singkatan', $record)->first();
    }
    public function batchSize(): int
    {
        return 50;
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
