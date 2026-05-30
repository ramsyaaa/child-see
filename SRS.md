# SOFTWARE REQUIREMENT SPECIFICATION (SRS)

# Sistem Identifikasi Anak Berkebutuhan Khusus (ABK)

Versi: 2.0
Platform: Web Based
Framework: Laravel 11
Database: MySQL

Berdasarkan dokumen screening ABK yang telah diberikan.

---

# 1. LATAR BELAKANG

Anak Berkebutuhan Khusus (ABK) membutuhkan identifikasi dini agar mendapatkan intervensi dan dukungan yang tepat sesuai karakteristik hambatannya.

Saat ini proses identifikasi awal masih:

- manual
- sulit direkap
- tidak terstruktur
- sulit dianalisis
- tidak memiliki histori perkembangan

Sistem ini dibuat untuk:

- mempermudah proses screening
- mengotomatisasi scoring
- mengotomatisasi klasifikasi
- menyimpan histori assessment
- memberikan rekomendasi tindak lanjut

---

# 2. TUJUAN SISTEM

## Tujuan Utama

Membangun sistem screening dan identifikasi awal ABK berbasis kuesioner dinamis.

---

# 3. RUANG LINGKUP SISTEM

---

# Jenis Screening yang Didukung

## A. Tunagrahita

### Sub Kategori

- Down Syndrome
- Fragile X Syndrome
- Prader Willi Syndrome
- Williams Syndrome

### Domain

- Domain Konseptual
- Domain Sosial
- Domain Praktis

---

## B. Tunanetra

### Sub Kategori

- Totally Blind
- Low Vision
- MDVI

---

## C. Tunalaras

---

## D. Disgrafia

---

## E. Disleksia

---

## F. Slow Learner

---

# 4. AKTOR SISTEM

---

# ROLE

## 1. Super Admin

Memiliki akses penuh ke seluruh sistem.

---

## 2. User

User umum:

- orang tua
- guru
- terapis
- observer
- keluarga

---

# 5. HAK AKSES

---

# SUPER ADMIN

## Master Data

✅ Kelola kategori
✅ Kelola domain
✅ Kelola pertanyaan
✅ Kelola jawaban
✅ Kelola bobot
✅ Kelola rule scoring
✅ Kelola rekomendasi

---

## User Management

✅ Kelola user
✅ Suspend user
✅ Reset password user

---

## Assessment

✅ Lihat semua assessment
✅ Review assessment
✅ Export assessment

---

## Reporting

✅ Dashboard statistik
✅ Grafik distribusi
✅ Export PDF
✅ Export Excel

---

# USER

## Authentication

✅ Register
✅ Login
✅ Forgot password

---

## Child Management

✅ Tambah anak
✅ Edit anak
✅ Hapus anak

---

## Assessment

✅ Mulai assessment
✅ Isi kuesioner
✅ Submit assessment
✅ Lihat hasil
✅ Download PDF hasil
✅ Lihat histori assessment

---

# 6. FITUR UTAMA SISTEM

---

# MODULE 1 — AUTHENTICATION

## Fitur

- Register
- Login
- Logout
- Reset password
- Email verification

---

# MODULE 2 — USER PROFILE

## Fitur

- Edit profile
- Upload foto
- Ganti password

---

# MODULE 3 — CHILD MANAGEMENT

## Fitur

- Tambah data anak
- Edit data anak
- Upload foto anak
- Riwayat assessment anak

---

# MODULE 4 — CATEGORY MANAGEMENT

## Fungsi

Mengelola kategori ABK.

## Fitur

- Tambah kategori
- Edit kategori
- Aktif/nonaktif
- Upload icon

---

# MODULE 5 — DOMAIN MANAGEMENT

## Fungsi

Mengelola domain assessment.

## Contoh

### Tunagrahita

- Domain Sosial
- Domain Praktis
- Domain Konseptual

---

# MODULE 6 — QUESTIONNAIRE ENGINE

## Fungsi

Engine utama kuesioner dinamis.

---

# FITUR

## Dynamic Questionnaire

Admin dapat:

- tambah pertanyaan
- edit pertanyaan
- ubah urutan
- ubah bobot
- ubah tipe jawaban

Tanpa coding.

---

# QUESTION TYPES

| Type            | Deskripsi     |
| --------------- | ------------- |
| yes_no          | Ya/Tidak      |
| single_choice   | Single option |
| multiple_choice | Multi option  |
| scale           | Skala         |
| text            | Text          |
| number          | Numeric       |

---

# MODULE 7 — ANSWER OPTION MANAGEMENT

## Fungsi

Mengatur pilihan jawaban dan skor.

---

# Contoh

| Jawaban      | Score |
| ------------ | ----- |
| Tidak Pernah | 0     |
| Kadang       | 1     |
| Sering       | 2     |

---

# MODULE 8 — SCORING ENGINE

## Fungsi

Menghitung hasil assessment.

---

# FITUR

✅ Weighted score
✅ Dynamic formula
✅ Rule based classification
✅ Domain scoring
✅ Final scoring

---

# FLOW

```text id="ukbwdg"
Jawaban
↓
Score per pertanyaan
↓
Score per domain
↓
Final score
↓
Rule matching
↓
Classification
```

---

# MODULE 9 — RULE ENGINE

## Fungsi

Menentukan klasifikasi.

---

# Contoh Rule

| Min | Max | Result            |
| --- | --- | ----------------- |
| 0   | 3   | Belum Terindikasi |
| 4   | 7   | Terindikasi       |
| 8   | 11  | Terindikasi Kuat  |

---

# MODULE 10 — RESULT ENGINE

## Output

- hasil identifikasi
- tingkat indikasi
- detail klasifikasi
- rekomendasi

---

# MODULE 11 — PDF REPORT

## Isi PDF

- data anak
- hasil screening
- domain score
- rekomendasi
- grafik

---

# MODULE 12 — DASHBOARD

---

# Dashboard Super Admin

## Statistik

- total assessment
- kategori terbanyak
- distribusi indikasi
- tren assessment

---

# Dashboard User

## Informasi

- daftar anak
- histori screening
- hasil terbaru

---

# 7. SYSTEM FLOW

---

# FLOW USER

```text id="av7rqf"
Register/Login
↓
Tambah Data Anak
↓
Pilih Screening
↓
Isi Kuesioner
↓
Submit
↓
Scoring
↓
Classification
↓
Result
↓
Download PDF
```

---

# FLOW ADMIN

```text id="2d1h7k"
Login
↓
Kelola Master
↓
Kelola Rule
↓
Kelola Assessment
↓
Monitoring Dashboard
↓
Export Data
```

---

# 8. SYSTEM ARCHITECTURE

---

# FRONTEND

## Stack

- Blade
- Tailwind CSS
- AlpineJS

---

# BACKEND

## Stack

- Laravel 11
- PHP 8.2
- Sanctum

---

# DATABASE

## Database

- MySQL 8 / PostgreSQL

---

# STORAGE

## File Storage

- local
- s3 compatible

---

# ADMIN PANEL

## Recommendation

### FilamentPHP

---

# 9. DATABASE DESIGN

---

# TABLE: users

```sql id="10dfkz"
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    name VARCHAR(255),

    email VARCHAR(255) UNIQUE,

    password VARCHAR(255),

    phone VARCHAR(30),

    role ENUM(
        'super_admin',
        'user'
    ) DEFAULT 'user',

    user_type ENUM(
        'parent',
        'teacher',
        'therapist',
        'general'
    ) DEFAULT 'general',

    avatar VARCHAR(255) NULL,

    is_active BOOLEAN DEFAULT TRUE,

    email_verified_at TIMESTAMP NULL,

    remember_token VARCHAR(100) NULL,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: children

```sql id="3m7d1n"
CREATE TABLE children (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    user_id BIGINT,

    full_name VARCHAR(255),

    nick_name VARCHAR(255),

    gender ENUM('male','female'),

    birth_place VARCHAR(100),

    birth_date DATE,

    parent_name VARCHAR(255),

    parent_phone VARCHAR(30),

    school_name VARCHAR(255) NULL,

    class_name VARCHAR(100) NULL,

    address TEXT,

    photo VARCHAR(255) NULL,

    notes TEXT NULL,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: categories

```sql id="xplgtj"
CREATE TABLE categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    name VARCHAR(255),

    slug VARCHAR(255),

    type ENUM(
        'intelektual',
        'sensorik',
        'emosional',
        'akademik',
        'kombinasi'
    ),

    description LONGTEXT,

    icon VARCHAR(255) NULL,

    sort_order INT DEFAULT 0,

    is_active BOOLEAN DEFAULT TRUE,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: domains

```sql id="7u44tb"
CREATE TABLE domains (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    category_id BIGINT,

    name VARCHAR(255),

    description LONGTEXT NULL,

    opening_text LONGTEXT NULL,

    closing_text LONGTEXT NULL,

    sort_order INT DEFAULT 0,

    is_active BOOLEAN DEFAULT TRUE,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: questionnaires

```sql id="g13fd8"
CREATE TABLE questionnaires (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    category_id BIGINT,

    domain_id BIGINT,

    question_code VARCHAR(100),

    question LONGTEXT,

    question_type ENUM(
        'yes_no',
        'single_choice',
        'multiple_choice',
        'scale',
        'text',
        'number'
    ),

    helper_text TEXT NULL,

    weight DECIMAL(8,2) DEFAULT 1,

    is_required BOOLEAN DEFAULT TRUE,

    sort_order INT DEFAULT 0,

    is_active BOOLEAN DEFAULT TRUE,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: answer_options

```sql id="12b0kn"
CREATE TABLE answer_options (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    questionnaire_id BIGINT,

    label VARCHAR(255),

    value VARCHAR(100),

    score DECIMAL(8,2),

    sort_order INT DEFAULT 0,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: assessment_rules

```sql id="tm4cw7"
CREATE TABLE assessment_rules (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    category_id BIGINT,

    domain_id BIGINT NULL,

    label VARCHAR(255),

    severity_level ENUM(
        'normal',
        'light',
        'medium',
        'heavy'
    ),

    min_score DECIMAL(8,2),

    max_score DECIMAL(8,2),

    description LONGTEXT,

    recommendation LONGTEXT,

    color VARCHAR(20) NULL,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: assessments

```sql id="s6tsrp"
CREATE TABLE assessments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    assessment_code VARCHAR(100),

    user_id BIGINT,

    child_id BIGINT,

    category_id BIGINT,

    assessment_date DATETIME,

    total_score DECIMAL(8,2),

    result_label VARCHAR(255),

    result_description LONGTEXT,

    recommendation LONGTEXT,

    status ENUM(
        'draft',
        'submitted',
        'completed'
    ) DEFAULT 'draft',

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: assessment_answers

```sql id="x2hy4o"
CREATE TABLE assessment_answers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    assessment_id BIGINT,

    questionnaire_id BIGINT,

    answer_option_id BIGINT NULL,

    answer_text TEXT NULL,

    score DECIMAL(8,2),

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: assessment_domain_scores

```sql id="pzntit"
CREATE TABLE assessment_domain_scores (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    assessment_id BIGINT,

    domain_id BIGINT,

    total_score DECIMAL(8,2),

    result_label VARCHAR(255),

    result_description LONGTEXT,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: recommendations

```sql id="fy4xmk"
CREATE TABLE recommendations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    title VARCHAR(255),

    description LONGTEXT,

    contact_name VARCHAR(255) NULL,

    contact_type ENUM(
        'whatsapp',
        'phone',
        'website',
        'email'
    ) NULL,

    contact_value VARCHAR(255) NULL,

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# TABLE: attachments

```sql id="r1d55c"
CREATE TABLE attachments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    assessment_id BIGINT,

    file_name VARCHAR(255),

    file_path VARCHAR(255),

    file_type VARCHAR(100),

    created_at TIMESTAMP NULL,

    updated_at TIMESTAMP NULL
);
```

---

# 10. RELATIONSHIP DATABASE

---

# USERS

HAS MANY:

- children
- assessments

---

# CHILDREN

BELONGS TO:

- users

HAS MANY:

- assessments

---

# CATEGORIES

HAS MANY:

- domains
- questionnaires
- assessment_rules
- assessments

---

# DOMAINS

BELONGS TO:

- categories

HAS MANY:

- questionnaires
- assessment_rules

---

# QUESTIONNAIRES

BELONGS TO:

- categories
- domains

HAS MANY:

- answer_options

---

# ASSESSMENTS

BELONGS TO:

- users
- children
- categories

HAS MANY:

- assessment_answers
- assessment_domain_scores

---

# 11. SCORING DESIGN

---

# FORMULA

```text id="g2l5po"
Final Score =
SUM(answer_score × question_weight)
```

---

# FLOW

```text id="l8krl6"
Load Answers
↓
Calculate Question Score
↓
Calculate Domain Score
↓
Calculate Final Score
↓
Find Matching Rule
↓
Generate Result
```

---

# CONTOH

---

# QUESTION

| Question           | Weight |
| ------------------ | ------ |
| Anak sulit membaca | 2      |

---

# ANSWER

| Answer | Score |
| ------ | ----- |
| Ya     | 1     |

---

# RESULT

```text id="pb5rbq"
1 × 2 = 2
```

---

# 12. SERVICE LAYER DESIGN

```text id="t7qnp3"
app/
├── Services
│   ├── AssessmentService.php
│   ├── ScoringService.php
│   ├── RuleEngineService.php
│   ├── ClassificationService.php
│   ├── RecommendationService.php
│   ├── PDFReportService.php
│   └── DashboardService.php
```

---

# AssessmentService

## Tugas

- create assessment
- save answers
- finalize assessment

---

# ScoringService

## Tugas

- calculate score
- calculate domain score

---

# RuleEngineService

## Tugas

- mapping result
- determine classification

---

# PDFReportService

## Tugas

- generate PDF result

---

# 13. API DESIGN

---

# AUTH

```http id="89tpk4"
POST /api/register
POST /api/login
POST /api/logout
```

---

# CHILDREN

```http id="b2hiv1"
GET /api/children
POST /api/children
PUT /api/children/{id}
DELETE /api/children/{id}
```

---

# CATEGORY

```http id="fh3h2k"
GET /api/categories
GET /api/categories/{id}/domains
```

---

# QUESTIONNAIRE

```http id="0t3e5p"
GET /api/questionnaires/{category}
```

---

# ASSESSMENT

```http id="2wyyvb"
POST /api/assessments/start
POST /api/assessments/submit
GET /api/assessments/{id}
```

---

# REPORT

```http id="8k74q4"
GET /api/assessments/{id}/pdf
```

---

# 14. NON FUNCTIONAL REQUIREMENTS

| Requirement  | Detail             |
| ------------ | ------------------ |
| Security     | Sanctum Auth       |
| Performance  | <3 detik           |
| Availability | 99%                |
| Scalability  | Multi tenant ready |
| Audit        | Activity log       |
| Backup       | Daily              |

---

# 15. RECOMMENDED PACKAGES

| Package            | Fungsi      |
| ------------------ | ----------- |
| FilamentPHP        | Admin panel |
| Laravel Sanctum    | API auth    |
| Spatie Activitylog | Audit log   |
| Laravel Excel      | Export      |
| DomPDF             | PDF report  |

---

# 16. DEPLOYMENT ARCHITECTURE

---

# SERVER

## Minimum Spec

| Resource | Spec       |
| -------- | ---------- |
| CPU      | 4 Core     |
| RAM      | 8 GB       |
| Storage  | 100 GB SSD |

---

# SOFTWARE

| Software | Version |
| -------- | ------- |
| Ubuntu   | 22.04   |
| PHP      | 8.2     |
| MySQL    | 8       |
| Nginx    | latest  |

---

# 17. FUTURE DEVELOPMENT

---

# PHASE 2

## Planned Features

- AI recommendation
- AI summary
- WhatsApp integration
- Child progress tracking
- Multi assessment comparison
- Growth analytics
- Consultation booking

---

# 18. FINAL SYSTEM CONCEPT

Sistem ini dibangun sebagai:

# Dynamic Assessment & Screening Engine

Dengan prinsip:

✅ Dynamic questionnaire
✅ Dynamic scoring
✅ Dynamic rule engine
✅ Configurable via CMS
✅ Multi category support
✅ Multi domain support
✅ Reusable architecture

---

# 19. KESIMPULAN

Sistem mampu:

- melakukan screening ABK
- mengotomatisasi scoring
- mengotomatisasi klasifikasi
- menyimpan histori anak
- menghasilkan laporan assessment
- memberikan rekomendasi tindak lanjut

Dan dapat dikembangkan untuk:

- Autism
- ADHD
- Speech Delay
- Dyscalculia
- Gangguan perkembangan lainnya

tanpa perlu mengubah core system.
