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
        Schema::create('launchpad_leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('whatsapp_number');
            $table->string('startup_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('status')->default('new')->index(); // new, contacted, qualified, converted, archived
            $table->text('notes')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launchpad_leads');
    }
};

