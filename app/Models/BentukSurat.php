<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BentukSurat extends Model
{
    use HasFactory;
    protected $table = 'bentuk_surat';
    public $timestamps = false;
    protected $fillable = [
        'kotama',
        'satminkal',
        'judul_kgb_mil',
        'judul_kgb_asn',
        'ket_kgb_asn',
        'hari_ini',
        'jabatan',
        'nama',
        'pangkat',
        'tembusan',
    ];

    public function gajiBerkalaMiliter(): HasMany 
    {
        return $this->hasMany(GajiBerkalaMiliter::class);
    }
    
    protected function casts(): array
    {
        return [
            'tembusan' => 'array',
        ];
    }
}
