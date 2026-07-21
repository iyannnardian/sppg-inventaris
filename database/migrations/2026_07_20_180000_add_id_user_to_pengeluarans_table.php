<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pengeluarans') && !Schema::hasColumn('pengeluarans', 'id_user')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                $table->foreignId('id_user')->nullable()->after('id_pengeluaran')->constrained('users', 'id')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('pengeluarans') && Schema::hasColumn('pengeluarans', 'id_user')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            });
        }
    }
};
