<?php

declare(strict_types=1);

use App\Enums\UpdateStatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proje zaman çizelgesi kayıtları.
 *
 * Index: idx_project_created (project_id, id DESC)
 *   Tipik sorgu: WHERE project_id = ? ORDER BY id DESC LIMIT 20
 *   Composite index hem filtreyi hem sıralamayı karşılar; filesort olmaz.
 *   Cursor pagination `WHERE id < cursor` ile aynı indexi kullanır.
 *
 * MySQL'de DESC index, SQLite testlerinde düz (project_id, id) index kullanılır.
 * SQLite B-Tree geriye doğru taranabildiği için ORDER BY id DESC yine index'e oturur.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status_type', 32)->default(UpdateStatusType::Completed->value);
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::getConnection()->statement(
                'CREATE INDEX idx_project_created ON project_updates (project_id, id DESC)'
            );
        } else {
            Schema::table('project_updates', function (Blueprint $table) {
                $table->index(['project_id', 'id'], 'idx_project_created');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_updates');
    }
};
