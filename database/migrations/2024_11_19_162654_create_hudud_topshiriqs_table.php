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
        Schema::create('hudud_topshiriqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hudud_id')->constrained('hududs')->onDelete('cascade');
            $table->foreignId('topshiriq_id')->constrained('topshiriqs')->onDelete('cascade');
            $table->string('status')->default('send');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hudud_topshiriqs');
    }
};  
