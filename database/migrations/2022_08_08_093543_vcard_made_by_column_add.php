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
        Schema::table('vcards', function (Blueprint $table) {
            $table->string('made_by')->after('location_url')->nullable();
            $table->text('made_by_url')->after('made_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vcards', function (Blueprint $table) {
            $table->dropColumn('made_by');
            $table->dropColumn('made_by_url');
        });
    }
};
