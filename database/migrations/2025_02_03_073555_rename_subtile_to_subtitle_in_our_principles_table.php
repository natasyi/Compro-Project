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
        Schema::table('our_principles', function (Blueprint $table) {
            $table->renameColumn('subtile', 'subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('our_principles', function (Blueprint $table) {
            $table->renameColumn('subtitle', 'subtile');
        });
    }
};
