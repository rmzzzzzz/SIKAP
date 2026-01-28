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
    Schema::create('pegawai', function (Blueprint $table) {
        $table->id('id_pegawai');
        $table->unsignedBigInteger('opd_id')->nullable();
        $table->string('nama', 150);
        $table->string('nip', 25)->nullable();
        $table->string('jabatan', 100);
        $table->string('unit_kerja', 100);
        $table->string('email', 100);
        $table->string('telp', 20);
        $table->timestamps();

        $table->foreign('opd_id')
            ->references('id_opd')
            ->on('opd')
            ->nullOnDelete();
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
