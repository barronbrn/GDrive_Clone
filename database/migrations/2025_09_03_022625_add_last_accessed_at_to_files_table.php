<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Runs the migrations (adds last_accessed_at column to files table)
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->timestamp('last_accessed_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    // Membalikkan migrasi (menghapus kolom last_accessed_at dari tabel files)
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            //
        });
    }
};
