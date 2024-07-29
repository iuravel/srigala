<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BentukSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bentuk_surat')->insert([
            'kotama' => 'KOMANDO DAERAH MILITER III/SILIWANGI',
            'satminkal' => 'PERALATAN',
            'judul_kgb_mil' => '<p>PEMBERIAN KENAIKAN GAJI BERKALA</p><p>MENURUT PERATURAN PEMERINTAH</p><p>NOMOR 6 TAHUN 2024 TANGGAL 01-01-2024</p>',
            'judul_kgb_asn' => 'SURAT PERINTAH - KENAIKAN GAJI BERKALA',
            'ket_kgb_asn' => 'Berdassarkan Surat Peraturan Pemerintah Republik Indonesia  Nomor 5 Tahun 2024 tanggal 01 Januari 2005, tentang Peraturan Kenaikan Gaji Pegawai Negeri Sipil di Lingkungan TNI Angkatan Darat.',
            'hari_ini' => 0,
            'jabatan' => 'Kepala Paldam III/Siliwangi',
            'nama' => 'Teguh Sulistyono, S.I.P.',
            'pangkat' => 'Letnan Kolonel Cpl NRP 11000056341078',
            'tembusan' => '"Kaajendam III/Slw,Kainfolahtadam III/Slw,Paku Paldam III/Slw NA.2.05.09"',
        ]);
    }
}
