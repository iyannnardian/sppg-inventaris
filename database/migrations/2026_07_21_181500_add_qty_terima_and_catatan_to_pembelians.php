<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembelian_details', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian_details', 'qty_terima')) {
                $table->decimal('qty_terima', 18, 2)->nullable()->after('qty');
            }
            if (!Schema::hasColumn('pembelian_details', 'catatan')) {
                $table->string('catatan', 255)->nullable()->after('subtotal');
            }
        });

        Schema::table('pembelians', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelians', 'catatan_penerimaan')) {
                $table->text('catatan_penerimaan')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('pembelian_details', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian_details', 'qty_terima')) {
                $table->dropColumn('qty_terima');
            }
            if (Schema::hasColumn('pembelian_details', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });

        Schema::table('pembelians', function (Blueprint $table) {
            if (Schema::hasColumn('pembelians', 'catatan_penerimaan')) {
                $table->dropColumn('catatan_penerimaan');
            }
        });
    }
};
