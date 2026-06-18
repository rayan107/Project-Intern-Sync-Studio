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
        // جرب كل الجداول الممكنة
        $tables = ['event_user', 'event_registrations', 'registrations'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // أضف عمود checked_in_at إذا ما كاينش
                    if (!Schema::hasColumn($tableName, 'checked_in_at')) {
                        $table->timestamp('checked_in_at')->nullable();
                    }
                    
                    // أضف عمود status إذا ما كاينش
                    if (!Schema::hasColumn($tableName, 'status')) {
                        $table->string('status')->default('registered');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['event_user', 'event_registrations', 'registrations'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'checked_in_at')) {
                        $table->dropColumn('checked_in_at');
                    }
                    if (Schema::hasColumn($tableName, 'status')) {
                        $table->dropColumn('status');
                    }
                });
            }
        }
    }
};