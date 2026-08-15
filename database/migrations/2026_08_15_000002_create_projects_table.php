<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Müşteriye ait projeler.
 *
 * Index: idx_access_token UNIQUE
 *   /status/{token} sorgusu WHERE access_token = ? LIMIT 1 kullanır.
 *   UNIQUE B-Tree index ile lookup O(log n); full table scan olmaz.
 *
 * Index: client_id (FK)
 *   Admin panelinde müşteriye göre proje listesi ve CASCADE silme için.
 *
 * Index: idx_projects_client_id (client_id, id)
 *   ?client_id= filtre + latest('id') aynı index seek.
 *
 * Index: idx_projects_status
 *   Dashboard COUNT / GROUP BY status. PK latest('id') listeler.
 *
 * Durum kolonu string tutulur (PHP enum ile eşleşir). Native MySQL ENUM
 * yerine string tercih edilir: SQLite testleri çalışır, yeni durum eklemek
 * ALTER TABLE gerektirmez.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('access_token', 64)->unique('idx_access_token');
            $table->string('status', 32)->default(ProjectStatus::InProgress->value);
            $table->date('start_date');
            $table->date('target_completion_date');
            $table->date('actual_completion_date')->nullable();
            $table->decimal('agreed_budget', 12, 2)->nullable();
            $table->timestamps();

            $table->index(['client_id', 'id'], 'idx_projects_client_id');
            $table->index('status', 'idx_projects_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
