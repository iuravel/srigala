<?php

use App\Models\Grup;
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
        // Schema::create('golongan', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor(Grup::class)->references("id")->on("grup");
        //     $table->string('nama');
        //     $table->string('uraian')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('golongan');
    }
};
