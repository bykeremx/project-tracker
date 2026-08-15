<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proje tahsilat defteri. Aylık kazanç burada satır olarak tutulmaz;
 * paid_on üzerinden SUM ile hesaplanır.
 *
 * Index: idx_project_paid_on (project_id, paid_on)
 *   Proje sayfasında "bu işe ait ödemeler, tarihe göre" sorgusu.
 *
 * Index: idx_paid_on (paid_on, id)
 *   Dashboard ve ay detayı: WHERE paid_on BETWEEN ? AND ? ORDER BY paid_on DESC, id DESC.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('paid_on');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'paid_on'], 'idx_project_paid_on');
            $table->index(['paid_on', 'id'], 'idx_paid_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
