<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Enums\UpdateStatusType;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Yönetici',
            'email' => 'admin@example.com',
        ]);

        $client = Client::query()->create([
            'name' => 'Ayşe Demir',
            'email' => 'ayse@ornekfirma.com',
            'company_name' => 'Örnek Firma A.Ş.',
        ]);

        $project = Project::query()->create([
            'client_id' => $client->id,
            'title' => 'Kurumsal Web Sitesi',
            'access_token' => Str::random(64),
            'status' => ProjectStatus::InProgress,
            'start_date' => now()->subDays(21)->toDateString(),
            'target_completion_date' => now()->addDays(14)->toDateString(),
            'agreed_budget' => 45000,
        ]);

        Payment::query()->create([
            'project_id' => $project->id,
            'amount' => 15000,
            'paid_on' => now()->subMonth()->toDateString(),
            'note' => 'Kapora',
        ]);

        Payment::query()->create([
            'project_id' => $project->id,
            'amount' => 10000,
            'paid_on' => now()->toDateString(),
            'note' => 'Ara ödeme',
        ]);

        $steps = [
            ['Projeye başlandı', 'Kick-off toplantısı yapıldı, kapsam netleştirildi.', UpdateStatusType::Completed, true],
            ['Veritabanı tasarlandı', 'Müşteri, proje ve güncelleme tabloları oluşturuldu.', UpdateStatusType::Completed, true],
            ['Tasarım revizyonu', 'İç not: logo dosyaları hâlâ eksik.', UpdateStatusType::Blocked, false],
            ['API geliştiriliyor', 'Durum endpoint’leri üzerinde çalışılıyor.', UpdateStatusType::InProgress, true],
            ['Bilgilendirme', 'Önümüzdeki hafta ara teslim planlanıyor.', UpdateStatusType::Info, true],
        ];

        foreach ($steps as [$title, $description, $type, $isPublic]) {
            ProjectUpdate::query()->create([
                'project_id' => $project->id,
                'title' => $title,
                'description' => $description,
                'status_type' => $type,
                'is_public' => $isPublic,
            ]);
        }
    }
}
