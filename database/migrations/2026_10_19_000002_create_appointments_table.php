<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 12)->unique();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('service', 40);
            $table->date('preferred_date');
            $table->string('preferred_time', 5);            // HH:MM, 24h
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending'); // pending | confirmed | completed | cancelled
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['preferred_date', 'preferred_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
