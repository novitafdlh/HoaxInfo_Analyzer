<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('matched_official_content_id')
                ->nullable()
                ->after('guest_session_id')
                ->constrained('official_contents')
                ->nullOnDelete();
            $table->string('analysis_method')->nullable()->after('similarity_label');
            $table->string('confidence_level')->nullable()->after('analysis_method');
            $table->string('confidence_label')->nullable()->after('confidence_level');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('matched_official_content_id');
            $table->dropColumn([
                'analysis_method',
                'confidence_level',
                'confidence_label',
            ]);
        });
    }
};
