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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id'); // foreign key to admins
        $table->string('title');
        $table->decimal('price', 10, 2)->default(0);
        $table->text('description');
        $table->date('event_date');
        $table->time('start_time');
        $table->time('end_time');
        $table->string('location');
        $table->json('image')->nullable(); // Store image filename/path
        $table->timestamps();
$table->dropColumn('image');
        // Foreign key
        $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
