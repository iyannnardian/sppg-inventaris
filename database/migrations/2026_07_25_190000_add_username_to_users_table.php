<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 50)->unique()->nullable()->after('nama');
            });

            // Populate default username for existing users
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $username = strtolower(explode('@', $user->email)[0] ?? 'user_' . $user->id_user);
                DB::table('users')->where('id_user', $user->id_user)->update(['username' => $username]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }
};
