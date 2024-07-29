<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GajiBerkalaAsn extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gaji_berkala_asn';
    public $timestamps = true;
    protected $fillable = [
        'golongan_lama_id','golongan_baru_id','user_id','jenis_kelamin_id',
        'nama','nip','karpeg','jabatan','kesatuan','tempat_lahir','tanggal_lahir','tmt_cpns',
        'tahun_mks_lama','bulan_mks_lama','tahun_mkg_lama','bulan_mkg_lama','gaji_pokok_lama','skep_lama','tmt_kgb_lama','tmt_kgb_yad_lama',
        'tahun_mks_baru','bulan_mks_baru','tahun_mkg_baru','bulan_mkg_baru','gaji_pokok_baru','skep_baru','tmt_kgb_baru','tmt_kgb_yad_baru',
        'keterangan','tanggal_terbit','deleted_at',
    ];
    public function jenisKelamin(): BelongsTo
    {
        return $this->belongsTo(JenisKelamin::class, foreignKey:'jenis_kelamin_id');
    }
    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class);
    }
    public function golonganLama(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, foreignKey:'golongan_lama_id');
    }
    public function golonganBaru(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, foreignKey:'golongan_baru_id');
    }
    public function gapokAsn(): BelongsToMany
    {
        return $this->belongsToMany(GapokAsn::class);
    }
    public function getFullTtlAttribute()
    {
        $tempat = $this->tahun_mks_lama;
        $tanggal = $this->bulan_mks_lama;
        return $tempat .''. $tanggal;
    }
    public function getFullGolonganLamaAttribute()
    {
        $uraian = $this->nama;
        $nama = $this->uraian;
        return $uraian .''. $nama;
    }
    public function getFullGolonganBaruAttribute()
    {
        $uraian = $this->nama;
        $nama = $this->uraian;
        return $uraian .''. $nama;
    }
    
    public function getFullMksLamaAttribute()
    {
        //MKS LAMA
        $tahun = $this->tahun_mks_lama;
        $bulan = $this->bulan_mks_lama;
        return $tahun .''. $bulan;
    }
    public function getFullMkgLamaAttribute()
    {
        //MKG LAMA
        $tahun = $this->tahun_mkg_lama;
        $bulan = $this->bulan_mkg_lama;
        return $tahun .''. $bulan;
    }
    public function getFullMksBaruAttribute()
    {
        //MKS BARU
        $tahun = $this->tahun_mks_baru;
        $bulan = $this->bulan_mks_baru;
        return $tahun .''. $bulan;
    }
    public function getFullMkgBaruAttribute()
    {
        //MKG BARU
        $tahun = $this->tahun_mkg_baru;
        $bulan = $this->bulan_mkg_baru;
        return $tahun .''. $bulan;
    }
}
