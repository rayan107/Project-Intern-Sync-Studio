<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            // إضافة الأعمدة المفقودة
            if (!Schema::hasColumn('event_user', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('registered_at');
            }
            
            if (!Schema::hasColumn('event_user', 'status')) {
                $table->string('status')->default('registered')->after('checked_in_at');
            }
            
            if (!Schema::hasColumn('event_user', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropColumn(['checked_in_at', 'status', 'cancelled_at']);
        });
    }
};