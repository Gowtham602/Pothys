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
       Schema::create('image_clicks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('image_id');
    $table->string('ip_address')->nullable();
    $table->string('browser')->nullable();
    $table->string('device_type')->nullable();
    $table->string('country')->nullable();
    $table->timestamps();

    $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_clicks');
    }
};
