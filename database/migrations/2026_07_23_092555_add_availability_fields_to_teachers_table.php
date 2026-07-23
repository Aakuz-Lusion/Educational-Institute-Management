<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->boolean('is_available')->default(true);
            $table->date('unavailable_date')->nullable();
            $table->string('unavailable_reason')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'unavailable_date', 'unavailable_reason', 'is_active']);
        });
    }
};