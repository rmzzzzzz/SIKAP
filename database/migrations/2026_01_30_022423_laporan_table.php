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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');

            $table->foreignId('kegiatan_id')
                ->constrained('kegiatan', 'id_kegiatan');

            $table->foreignId('opd_id')
                ->constrained('opd', 'id_opd');

        
            $table->integer('total_hadir');
          
            $table->enum('status_persetujuan', ['menunggu', 'disetujui', 'ditolak'])
                ->default('menunggu');

            $table->string('ttd_pimpinan')->nullable();
            $table->timestamp('waktu_persetujuan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
