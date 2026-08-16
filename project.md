# Proje İsterleri ve Mimari Özellik Belgesi
## Müşteri Proje Durum Takip Paneli

---

# 1. Amaç ve Sistem Özeti

Bu sistem, yöneticinin (Admin) müşterileri ve projelerin gelişim süreçlerini yönettiği bir **Admin Paneli** ile müşterinin kendisine özel oluşturulan benzersiz ve güvenli bağlantı (`/status/{access_token}`) üzerinden projesini canlı olarak takip ettiği **Müşteri Canlı Durum Ekranı**ndan oluşmaktadır.

Sistemin temel amacı, müşteriye proje sürecini şeffaf bir şekilde göstermek, yöneticiye ise proje yönetimini kolaylaştırmaktır.

---

# 2. Teknoloji Yığını ve Standartlar

| Katman | Teknoloji |
|---------|-----------|
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Blade + Tailwind CSS (Vite) |
| Veritabanı | MySQL |
| Kod Standartları | PSR-12, Strict Types, Form Request Validation, Thin Controller, Action/Service Pattern |

### Kod Standartları

- `declare(strict_types=1);`
- PSR-12 kod standardı
- Form Request Validation
- Thin Controller yaklaşımı
- Business Logic yalnızca Action / Service katmanında bulunmalıdır.
- Eloquent ilişkileri Lazy Loading yerine gerektiğinde Eager Loading ile kullanılmalıdır.

---

# 3. Veritabanı Tasarımı

## 3.1 Clients Tablosu

Müşteri bilgilerini tutar.

```sql
CREATE TABLE clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    company_name VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Alanlar

| Alan | Açıklama |
|------|----------|
| id | Birincil anahtar |
| name | Müşteri adı |
| email | E-posta |
| company_name | Firma adı |
| timestamps | Laravel zaman alanları |

---

## 3.2 Projects Tablosu

Müşterilere ait projeleri tutar.

```sql
CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    access_token VARCHAR(64) NOT NULL,
    status ENUM(
        'draft',
        'in_progress',
        'completed',
        'on_hold'
    ) DEFAULT 'in_progress',
    start_date DATE NOT NULL,
    target_completion_date DATE NOT NULL,
    actual_completion_date DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON DELETE CASCADE,

    UNIQUE INDEX idx_access_token (access_token)
);
```

### Alanlar

| Alan | Açıklama |
|------|----------|
| client_id | Müşteri ilişkisi |
| title | Proje adı |
| access_token | Müşteri erişim anahtarı |
| status | Proje durumu |
| start_date | Başlangıç tarihi |
| target_completion_date | Tahmini bitiş |
| actual_completion_date | Gerçek bitiş |

---

### Neden `access_token` Index Kullanılıyor?

Müşteri sayfası şu URL üzerinden açılır:

```
/status/{access_token}
```

Yani sorgular şu şekilde olacaktır:

```sql
SELECT *
FROM projects
WHERE access_token = ?
LIMIT 1;
```

Index sayesinde:

- Full Table Scan yapılmaz.
- B-Tree Index kullanılır.
- Tablo milyonlarca kayıt olsa bile sorgu çok hızlı çalışır.
- Token benzersiz olduğu için ayrıca `UNIQUE` olarak tanımlanmıştır.

---

## 3.3 Project Updates Tablosu

Projenin zaman çizelgesini tutar.

```sql
CREATE TABLE project_updates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status_type ENUM(
        'completed',
        'in_progress',
        'blocked',
        'info'
    ) DEFAULT 'completed',
    is_public BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE CASCADE,

    INDEX idx_project_created (project_id, id DESC)
);
```

---

### Alanlar

| Alan | Açıklama |
|------|----------|
| project_id | Proje ilişkisi |
| title | Güncelleme başlığı |
| description | Açıklama |
| status_type | Güncelleme tipi |
| is_public | Müşteriye açık mı? |

---

### Neden `(project_id, id DESC)` Index'i Kullanılıyor?

Timeline ekranında sürekli şu sorgu çalışacaktır:

```sql
SELECT *
FROM project_updates
WHERE project_id = ?
ORDER BY id DESC
LIMIT 20;
```

Bu index sayesinde:

- Dosya sıralaması yapılmaz.
- ORDER BY maliyeti azalır.
- Cursor Pagination maksimum performansla çalışır.

---

# 4. Sistem Bileşenleri

## 4.1 Admin Paneli

Yalnızca giriş yapmış yöneticilerin erişebileceği yönetim panelidir.

### Müşteri Yönetimi

- Yeni müşteri oluşturma
- Müşteri düzenleme
- Müşteri silme
- Müşteri listeleme

Girilecek bilgiler:

- Ad
- E-posta
- Firma

---

### Proje Yönetimi

Admin;

- Proje oluşturabilir.
- Proje düzenleyebilir.
- Projeyi tamamlandı olarak işaretleyebilir.
- Beklemeye alabilir.

Girilecek bilgiler:

- Proje başlığı
- Başlangıç tarihi
- Tahmini bitiş tarihi

---

### Access Token Oluşturulması

Yeni proje oluşturulduğunda sistem otomatik olarak:

```php
Str::random(64)
```

ile tahmin edilmesi mümkün olmayan güvenli bir erişim anahtarı üretmelidir.

Bu anahtar:

- Benzersiz olmalıdır.
- URL içerisinde kullanılmalıdır.
- Tahmin edilememelidir.

---

### Proje Güncellemeleri

Admin;

Projeye sınırsız sayıda adım ekleyebilir.

Örnekler:

- Projeye başlandı
- Veritabanı tasarlandı
- API geliştirildi
- Testler tamamlandı
- Yayına alındı

Her adım için:

- Başlık
- Açıklama
- Durum tipi

seçilebilir.

---

### Status Type Seçenekleri

| Değer | Açıklama |
|--------|----------|
| completed | Tamamlandı |
| in_progress | Devam Ediyor |
| blocked | Engellendi |
| info | Bilgilendirme |

---

### İç Notlar

Her güncelleme için:

```text
is_public
```

alanı bulunacaktır.

#### TRUE

Müşteri görebilir.

#### FALSE

Sadece Admin görebilir.

---

### Canlı Link Kopyalama

Admin panelinde:

```
https://domain.com/status/{access_token}
```

adresini tek tıklamayla panoya kopyalayan bir buton bulunmalıdır.

---

# 4.2 Müşteri Canlı Durum Ekranı

Bu ekran giriş gerektirmez.

Erişim:

```
/status/{access_token}
```

şeklinde yapılır.

---

## Güvenlik

Bu ekran kesinlikle:

- Read Only olacaktır.
- Veri değiştirme işlemi yapmayacaktır.
- Gizli notları göstermeyecektir.

Yalnızca:

```text
is_public = true
```

olan kayıtlar gösterilecektir.

---

## Sayfa Tasarımı

Sayfanın üst kısmında:

- Proje adı
- Müşteri adı
- Başlangıç tarihi
- Tahmini bitiş tarihi
- Genel durum rozeti

bulunmalıdır.

---

## Timeline

Alt bölümde dikey zaman çizelgesi yer alacaktır.

Her güncelleme:

- Kronolojik sırada
- Kart görünümünde
- Durum ikonları ile birlikte gösterilecektir.

---

### Durum Renkleri

| Durum | Renk |
|--------|------|
| completed | Yeşil |
| in_progress | Sarı |
| blocked | Kırmızı |
| info | Mavi |

---

# 5. Performans

Timeline verileri:

```php
cursorPaginate()
```

ile çekilecektir.

Bunun avantajları:

- Daha düşük RAM kullanımı
- Büyük veri kümelerinde yüksek performans
- Sonsuz kaydırma (Infinite Scroll) desteği
- OFFSET maliyetinin ortadan kalkması

---

# 6. Senior Pair Programmer Talimatı (Cursor AI)

Bu projeyi geliştirirken aşağıdaki ilkelere uyulmalıdır.

## Eğitici Yaklaşım

Yazılan her:

- Migration
- Model
- Controller
- Action
- Service
- Request
- Policy

dosyasından sonra şu soruya cevap verilmelidir:

> "Bu yapıyı neden bu şekilde tasarladık?"

---

## Veritabanı Analizi

Yazılan her sorgu için açıklanmalıdır:

- Hangi index kullanılıyor?
- EXPLAIN çıktısında ne olur?
- Full Scan olur mu?
- Cursor Pagination neden tercih edildi?
- N+1 problemi oluşur mu?

---

## Geliştirme Sırası

Kodlar tek seferde yazılmamalıdır.

Şu sıra izlenmelidir:

1. Migration
2. Model
3. Relationship
4. Form Request
5. Action / Service
6. Controller
7. Route
8. Blade View
9. JavaScript
10. Test

---

## Açıklama Dili

Tüm mimari açıklamalar, kod yorumları ve eğitim içerikleri tamamen Türkçe olmalıdır.