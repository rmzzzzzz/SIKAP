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
    Schema::create('kehadiran', function (Blueprint $table) {
        $table->id('id_kehadiran');
        $table->unsignedBigInteger('pegawai_id');
        $table->unsignedBigInteger('kegiatan_id');
        $table->enum('tipe_peserta', ['narasumber', 'peserta']);
        $table->enum('status_pegawai', ['internal', 'eksternal']);
        $table->dateTime('waktu_hadir')->nullable();
        $table->decimal('latitude_hadir', 10, 7)->nullable();
        $table->decimal('longitude_hadir', 10, 7)->nullable();
        $table->longText('tanda_tangan')->nullable();
        $table->timestamps();

        $table->foreign('pegawai_id')
            ->references('id_pegawai')
            ->on('pegawai')
            ->cascadeOnDelete();

        $table->foreign('kegiatan_id')
            ->references('id_kegiatan')
            ->on('kegiatan')
            ->cascadeOnDelete();

        
        $table->unique(['pegawai_id', 'kegiatan_id']);
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
