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
        $table->unsignedBigInteger('peserta_id');
        $table->unsignedBigInteger('kegiatan_id');
        $table->enum('tipe_peserta', ['narasumber', 'peserta']);
        $table->enum('status_peserta', ['internal', 'eksternal']);
        $table->dateTime('waktu_hadir');
        $table->decimal('latitude_hadir', 10, 7);
        $table->decimal('longitude_hadir', 10, 7);
        $table->string('tanda_tangan', 255)->nullable();
        $table->timestamps();

        $table->foreign('peserta_id')
            ->references('id_peserta')
            ->on('peserta')
            ->cascadeOnDelete();

        $table->foreign('kegiatan_id')
            ->references('id_kegiatan')
            ->on('kegiatan')
            ->cascadeOnDelete();

        
        $table->unique(['peserta_id', 'kegiatan_id']);
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
