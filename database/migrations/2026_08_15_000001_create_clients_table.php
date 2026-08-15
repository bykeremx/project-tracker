<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Müşteri ana kaydı. Projeler bu tabloya bağlanır.
 *
 * Index analizi: Listeleme `id` PK üzerinden yapılır (B-Tree clustered).
 * İsim/e-posta araması şimdilik tam tarama yapabilir; hacim küçükken
 * kabul edilebilir. İleride arama gerekirse name/email için ayrı index eklenir.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
