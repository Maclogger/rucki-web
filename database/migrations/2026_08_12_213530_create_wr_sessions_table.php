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
        Schema::create('wr_sessions', function (Blueprint $table) {
            $table->uuid("id_session")->primary();
            $table->uuid("id_visitor");
            $table->timestamps();
            $table->foreign("id_visitor")->references("id_visitor")->on("wr_visitors");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wr_sessions');
    }
};
