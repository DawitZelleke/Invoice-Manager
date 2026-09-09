<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('client');
            $table->string('email');
            $table->integer('amount');
            $table->integer('status_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
