<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->unique(['event_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'email']);
            $table->dropColumn(['name', 'email', 'registered_at']);
        });
    }
};