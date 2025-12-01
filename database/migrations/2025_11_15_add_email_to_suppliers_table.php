<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add the column if it does not already exist (prevents duplicate column errors)
        if (!Schema::hasColumn('suppliers', 'email')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->string('email')->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('suppliers', 'email')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
