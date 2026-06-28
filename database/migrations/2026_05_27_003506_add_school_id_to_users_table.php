<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // 1. Add the role column
        $table->string('role')->default('teacher')->after('email');
        
        // 2. Add the status column (defaults to pending for safety)
        $table->string('role_status')->default('pending')->after('role');

        // 3. Add the ward column for WEO administrators
        $table->string('ward')->nullable()->after('role_status');
        
        // 4. Link institutional staff accounts to their schools
        $table->foreignId('school_id')->nullable()->after('ward')->constrained('schools')->onDelete('cascade');
    });
}
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['school_id']);
        $table->dropColumn(['role', 'role_status', 'ward', 'school_id']);
    });
}
};