<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('bentuk_surat', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('kotama')->nullable();
        //     $table->string('satminkal')->nullable();
        //     $table->longText('judul_kgb_mil')->nullable();
        //     $table->longText('judul_kgb_asn')->nullable();
        //     $table->longText('ket_kgb_asn')->nullable();
        //     $table->boolean('hari_ini')->default(0)->nullable();
        //     $table->longText('jabatan')->nullable();
        //     $table->string('nama')->nullable();
        //     $table->string('pangkat')->nullable();
        //     $table->json('tembusan')->nullable();

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('bentuk_surat');
    }
};
