<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsiaStatusKlaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsia_status_klaim', function (Blueprint $table) {
            $table->string('no_sep', 50)->primary();
            $table->string('no_rawat', 50);
            $table->enum('status', ['verifikasi resume', 'lengkap', 'pengajuan', 'perbaiki', 'disetujui', 'klaim ambulans', 'batal', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rsia_status_klaim');
    }
}
