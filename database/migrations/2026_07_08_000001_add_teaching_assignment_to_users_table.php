<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('teaching_subject_id')->nullable()->after('school_id')->constrained('subjects')->nullOnDelete();
            $table->foreignId('teaching_class_id')->nullable()->after('teaching_subject_id')->constrained('school_classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teaching_subject_id']);
            $table->dropForeign(['teaching_class_id']);
            $table->dropColumn(['teaching_subject_id', 'teaching_class_id']);
        });
    }
};
