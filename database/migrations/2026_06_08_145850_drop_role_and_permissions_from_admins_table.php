<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('admins', 'permissions')) {
                $table->dropColumn('permissions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->default('viewer')->after('email');
            $table->json('permissions')->nullable()->after('role');
        });
    }
};