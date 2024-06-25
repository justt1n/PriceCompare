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
        Schema::table('cronjob_schedules', function (Blueprint $table) {
            $table->dropColumn('site_ids');
            $table->dropColumn('action');
            $table->tinyInteger('new');
            $table->tinyInteger('update');
            $table->unsignedBigInteger('site_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cronjob_schedules', function (Blueprint $table) {
            //
        });
    }
};
