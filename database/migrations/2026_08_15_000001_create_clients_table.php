<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Müşteri ana kaydı. Projeler bu tabloya bağlanır.
 *
 * Index: idx_clients_name
 *   Proje formunda ORDER BY name.
 * Listeleme latest('id') PK kullanır; ayrı created_at index gerekmez.
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

            $table->index('name', 'idx_clients_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
