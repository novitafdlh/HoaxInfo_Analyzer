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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('image_hash');
            $table->longText('extracted_text')->nullable();
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->string('similarity_label')->nullable();
            $table->string('system_status')->nullable();
            $table->string('final_status')->default('menunggu_validasi');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
