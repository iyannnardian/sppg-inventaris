<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pengeluarans')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                if (!Schema::hasColumn('pengeluarans', 'id_user')) {
                    $table->foreignId('id_user')->nullable()->after('id_pengeluaran')->constrained('users', 'id')->onDelete('cascade');
                }
                if (Schema::hasColumn('pengeluarans', 'nama_petugas')) {
                    $table->dropColumn('nama_petugas');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('pengeluarans')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                if (!Schema::hasColumn('pengeluarans', 'nama_petugas')) {
                    $table->string('nama_petugas', 40)->nullable();
                }
            });
        }
    }
};
