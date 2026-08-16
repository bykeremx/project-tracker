# Proje Takip / Project Tracker

[Türkçe](#türkçe) · [English](#english)

Müşteri projelerinin durumunu yönetmek ve müşteriye özel canlı bağlantı ile paylaşmak için Laravel uygulaması.

---

## Türkçe

### Teknoloji

| Katman | Sürüm |
|--------|--------|
| PHP | 8.3+ |
| Laravel | 13 (`^13.17`) |
| Veritabanı | MySQL 8 |
| Ön yüz | Blade, Tailwind CSS 4, Vite 8 |
| Test | Pest 5 |

İş mantığı controller’da durmaz: Form Request + Action / Service. Sorgular eager load ve `cursorPaginate` kullanır.

### Ne işe yarar

İki yüzey vardır:

- **Admin paneli** (`/admin`, giriş zorunlu): müşteriler, projeler, zaman çizelgesi, tahsilat, yöneticiler.
- **Müşteri sayfası** (`/status/{access_token}`): salt okunur. Yalnızca `is_public = true` güncellemeler görünür. Bütçe ve tahsilat çıkmaz.

Proje oluşturulunca 64 karakterlik rastgele `access_token` üretilir; formdan token yazılamaz.

### Proje yapısı (özet)

```
app/
  Actions/          İş kuralları (oluştur, güncelle, sil, token üret)
  Enums/            ProjectStatus, UpdateStatusType
  Http/
    Controllers/    İnce HTTP katmanı (Admin, Auth, Status)
    Requests/       Doğrulama
  Models/           User, Client, Project, ProjectUpdate, Payment
  Policies/         Yetki
  Services/         Timeline (cursor), kazanç özetleri
  Support/          Para formatı
database/
  migrations/       Şema + index’ler
  seeders/          Örnek yönetici ve proje
resources/
  views/            Admin, giriş, müşteri ekranı
routes/web.php      Tüm HTTP rotaları
tests/Feature/      Pest senaryoları
```

**Veri modeli (kısa):** `users` → yöneticiler. `clients` → müşteriler. `projects` → iş + `agreed_budget` + `access_token`. `project_updates` → zaman çizelgesi. `payments` → tahsilat; aylık kazanç bu satırlardan hesaplanır.

### Yerelde çalıştırma

Gereksinimler: PHP 8.3+, Composer, Node.js 20+, MySQL 8.

```bash
git clone <repo-url>
cd project-tracker

composer install
cp .env.example .env
php artisan key:generate
```

`.env` içinde MySQL ayarlarını doldurun:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_tracker_db
DB_USERNAME=root
DB_PASSWORD=
```

Veritabanını oluşturun, sonra:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Geliştirme (sunucu + Vite birlikte):

```bash
composer run dev
```

Uygulama: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Demo hesap

| Alan | Değer |
|------|--------|
| E-posta | `admin@example.com` |
| Şifre | `password` |

Seeder örnek müşteri, proje, güncelleme ve tahsilat da ekler.

### Test

```bash
php artisan test
```

Testler bellek-içi SQLite kullanır; MySQL’e dokunmaz.

### Önemli güvenlik notları

- Müşteri URL’si tahmin edilemez token ile açılır; yazma yoktur.
- Gizli notlar (`is_public = false`) public sorguda hiç çekilmez.
- Admin yazma işlemleri `throttle:admin-writes` (20/dk) ile sınırlıdır. Giriş `throttle:5,1`.

---

## English

### Stack

| Layer | Version |
|--------|--------|
| PHP | 8.3+ |
| Laravel | 13 (`^13.17`) |
| Database | MySQL 8 |
| Frontend | Blade, Tailwind CSS 4, Vite 8 |
| Tests | Pest 5 |

Business rules live in Actions / Services, not controllers. Validation uses Form Requests. Queries use eager loading and `cursorPaginate`.

### What it does

Two surfaces:

- **Admin panel** (`/admin`, auth required): clients, projects, timeline, collections, admin users.
- **Client status page** (`/status/{access_token}`): read-only. Only `is_public = true` updates are shown. Budget and payments never appear.

On create, each project gets a 64-character random `access_token`. Tokens cannot be set from the form.

### Project structure (summary)

```
app/
  Actions/          Use cases (create, update, delete, token generation)
  Enums/            ProjectStatus, UpdateStatusType
  Http/
    Controllers/    Thin HTTP layer (Admin, Auth, Status)
    Requests/       Validation
  Models/           User, Client, Project, ProjectUpdate, Payment
  Policies/         Authorization
  Services/         Timeline (cursor) and earnings totals
  Support/          Money formatting
database/
  migrations/       Schema + indexes
  seeders/          Sample admin and project
resources/
  views/            Admin, login, public status
routes/web.php      HTTP routes
tests/Feature/      Pest feature tests
```

**Data model (short):** `users` are admins. `clients` hold customers. `projects` hold work plus `agreed_budget` and `access_token`. `project_updates` is the timeline. `payments` is the ledger; monthly earnings are summed from these rows.

### Run locally

Requirements: PHP 8.3+, Composer, Node.js 20+, MySQL 8.

```bash
git clone <repo-url>
cd project-tracker

composer install
cp .env.example .env
php artisan key:generate
```

Set MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_tracker_db
DB_USERNAME=root
DB_PASSWORD=
```

Create the database, then:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Dev (server + Vite together):

```bash
composer run dev
```

App: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Demo account

| Field | Value |
|------|--------|
| Email | `admin@example.com` |
| Password | `password` |

The seeder also adds a sample client, project, timeline steps, and payments.

### Tests

```bash
php artisan test
```

Tests run on in-memory SQLite and do not touch MySQL.

### Security notes

- The client URL is a random token and is read-only.
- Private notes (`is_public = false`) are excluded from public queries.
- Admin writes are limited by `throttle:admin-writes` (20/min). Login is `throttle:5,1`.
