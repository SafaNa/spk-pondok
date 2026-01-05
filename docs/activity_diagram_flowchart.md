# Activity Diagram & Flowchart SPK Pondok

## Deskripsi Aplikasi

Aplikasi **SPK Pondok** adalah Sistem Pendukung Keputusan (Decision Support System) yang menggunakan metode **SAW (Simple Additive Weighting)** untuk memberikan rekomendasi santri di pondok pesantren berdasarkan kriteria-kriteria yang telah ditentukan.

---

## 1. Activity Diagram - Sistem Keseluruhan

```mermaid
flowchart TB
    subgraph User["🧑 User/Admin"]
        Start([Start])
        End([End])
    end
    
    subgraph Auth["🔐 Authentication"]
        Login[Login]
        CheckAuth{Authenticated?}
        ShowLoginPage[Show Login Page]
        ValidateCredentials[Validate Credentials]
    end
    
    subgraph Dashboard["📊 Dashboard"]
        ViewDashboard[View Dashboard]
        ShowStats[Display Statistics]
    end
    
    subgraph MasterData["📁 Master Data Management"]
        ManageSantri[Manage Santri]
        ManageKriteria[Manage Kriteria]
        ManageSubkriteria[Manage Subkriteria]
        ManagePeriode[Manage Periode]
    end
    
    subgraph Calculation["🔢 SPK Calculation"]
        InputPenilaian[Input Penilaian]
        RunCalculation[Run SAW Calculation]
        ViewHasil[View Hasil Perhitungan]
        ViewRekomendasi[View Rekomendasi]
        SensitivityAnalysis[Sensitivity Analysis]
    end
    
    Start --> Login
    Login --> ValidateCredentials
    ValidateCredentials --> CheckAuth
    CheckAuth -->|No| ShowLoginPage
    ShowLoginPage --> Login
    CheckAuth -->|Yes| ViewDashboard
    ViewDashboard --> ShowStats
    
    ShowStats --> ManageSantri
    ShowStats --> ManageKriteria
    ShowStats --> ManagePeriode
    ShowStats --> InputPenilaian
    
    ManageKriteria --> ManageSubkriteria
    InputPenilaian --> RunCalculation
    RunCalculation --> ViewHasil
    ViewHasil --> ViewRekomendasi
    ViewRekomendasi --> SensitivityAnalysis
    
    ManageSantri --> End
    ViewRekomendasi --> End
    SensitivityAnalysis --> End
```

---

## 2. Activity Diagram - Proses Login

```mermaid
flowchart TD
    A([Start]) --> B[User mengakses halaman login]
    B --> C[User mengisi username dan password]
    C --> D[User menekan tombol Login]
    D --> E{Validasi kredensial}
    E -->|Valid| F[Redirect ke Dashboard]
    E -->|Invalid| G[Tampilkan pesan error]
    G --> C
    F --> H([End])
```

---

## 3. Activity Diagram - Manajemen Data Santri

```mermaid
flowchart TD
    A([Start]) --> B[Admin membuka menu Santri]
    B --> C[Sistem menampilkan daftar Santri]
    C --> D{Pilihan Aksi}
    
    D -->|Tambah| E[Klik tombol Tambah Santri]
    E --> F[Isi form data: NIS, Nama, Kelas, dll]
    F --> G[Klik tombol Simpan]
    G --> H{Validasi data}
    H -->|Valid| I[Data tersimpan ke database]
    H -->|Invalid| J[Tampilkan error validasi]
    J --> F
    
    D -->|Edit| K[Klik tombol Edit pada santri]
    K --> L[Form terisi data santri]
    L --> M[Edit data yang diperlukan]
    M --> N[Klik tombol Update]
    N --> H
    
    D -->|Hapus| O[Klik tombol Hapus]
    O --> P{Konfirmasi hapus?}
    P -->|Ya| Q[Data dihapus dari database]
    P -->|Tidak| C
    
    D -->|Import| R[Klik tombol Import Excel]
    R --> S[Pilih file Excel]
    S --> T[Upload dan proses file]
    T --> I
    
    D -->|Export| U[Klik tombol Export]
    U --> V[Download file Excel/CSV]
    
    I --> C
    Q --> C
    V --> W([End])
```

---

## 4. Activity Diagram - Manajemen Kriteria & Subkriteria

```mermaid
flowchart TD
    A([Start]) --> B[Admin membuka menu Kriteria]
    B --> C[Sistem menampilkan daftar Kriteria]
    C --> D{Pilihan Aksi}
    
    D -->|Tambah Kriteria| E[Klik tombol Tambah]
    E --> F["Isi form: Nama, Bobot, Jenis (Benefit/Cost)"]
    F --> G[Simpan Kriteria]
    G --> H{Validasi}
    H -->|Valid| I[Kriteria tersimpan]
    H -->|Invalid| F
    
    D -->|Edit Kriteria| J[Klik Edit]
    J --> K[Update data kriteria]
    K --> G
    
    D -->|Kelola Subkriteria| L[Klik tombol Subkriteria]
    L --> M[Tampilkan daftar Subkriteria]
    M --> N{Aksi Subkriteria}
    
    N -->|Tambah| O["Isi: Nama Subkriteria, Nilai"]
    O --> P[Simpan Subkriteria]
    P --> M
    
    N -->|Edit| Q[Update Subkriteria]
    Q --> P
    
    N -->|Hapus| R[Hapus Subkriteria]
    R --> M
    
    I --> C
    M --> S([End])
```

---

## 5. Activity Diagram - Proses Penilaian Santri

```mermaid
flowchart TD
    A([Start]) --> B[Admin membuka menu Perhitungan]
    B --> C{Ada periode aktif?}
    C -->|Tidak| D[Tampilkan peringatan periode tidak aktif]
    D --> E[Admin mengaktifkan periode di menu Periode]
    E --> B
    
    C -->|Ya| F[Tampilkan form penilaian]
    F --> G[Pilih Santri dari dropdown]
    G --> H[Sistem menampilkan semua kriteria]
    H --> I[Admin memilih subkriteria untuk setiap kriteria]
    I --> J{Semua kriteria terisi?}
    J -->|Tidak| I
    J -->|Ya| K[Klik tombol Hitung]
    K --> L[Sistem menyimpan penilaian]
    L --> M[Sistem menjalankan perhitungan SAW]
    M --> N[Redirect ke halaman Hasil]
    N --> O([End])
```

---

## 6. Flowchart - Algoritma SAW (Simple Additive Weighting)

```mermaid
flowchart TD
    A([Mulai Perhitungan SAW]) --> B[Ambil semua data kriteria dan bobot]
    B --> C[Ambil data penilaian santri untuk periode aktif]
    C --> D[Hitung Total Bobot Semua Kriteria]
    D --> E[Loop: Untuk setiap kriteria]
    
    E --> F{Jenis Kriteria?}
    
    F -->|Benefit| G[Ambil nilai MAX dari subkriteria]
    G --> H["Normalisasi = Nilai / MAX"]
    
    F -->|Cost| I[Ambil nilai MIN dari subkriteria]
    I --> J["Normalisasi = MIN / Nilai"]
    
    H --> K["Bobot Ternormalisasi = Bobot / Total Bobot"]
    J --> K
    
    K --> L["Nilai Kriteria = Normalisasi × Bobot Ternormalisasi"]
    L --> M[Tambahkan ke Nilai Akhir]
    M --> N{Masih ada kriteria?}
    N -->|Ya| E
    N -->|Tidak| O[Simpan Nilai Akhir ke database]
    O --> P{Nilai Akhir >= 0.7?}
    
    P -->|Ya| Q["Status: DIREKOMENDASIKAN ✅"]
    P -->|Tidak| R{Nilai Akhir >= 0.4?}
    R -->|Ya| S["Status: DIPERTIMBANGKAN ⚠️"]
    R -->|Tidak| T["Status: TIDAK DIREKOMENDASIKAN ❌"]
    
    Q --> U[Generate Alasan Rekomendasi]
    S --> U
    T --> U
    U --> V[Simpan ke Riwayat Hitung]
    V --> W([Selesai])
```

---

## 7. Activity Diagram - Lihat Hasil & Rekomendasi

```mermaid
flowchart TD
    A([Start]) --> B[Admin membuka menu Hasil Rekomendasi]
    B --> C[Sistem mengambil data periode aktif]
    C --> D[Tampilkan daftar santri dengan ranking]
    D --> E{Pilihan Aksi}
    
    E -->|Lihat Detail| F[Klik nama santri]
    F --> G[Tampilkan detail perhitungan]
    G --> H["Tampilkan: Kriteria, Nilai, Normalisasi, Bobot"]
    H --> I[Tampilkan Nilai Akhir dan Status Rekomendasi]
    I --> J[Tampilkan Alasan Rekomendasi]
    
    E -->|Cetak| K[Klik tombol Cetak PDF]
    K --> L[Generate laporan PDF]
    L --> M[Download/Print laporan]
    
    E -->|Recalculate| N[Klik tombol Hitung Ulang]
    N --> O[Sistem menghitung ulang semua santri]
    O --> D
    
    E -->|Hapus| P[Klik tombol Hapus]
    P --> Q{Konfirmasi hapus?}
    Q -->|Ya| R[Hapus penilaian dan riwayat]
    Q -->|Tidak| D
    R --> D
    
    J --> S([End])
    M --> S
```

---

## 8. Activity Diagram - Analisis Sensitivitas

```mermaid
flowchart TD
    A([Start]) --> B[Admin membuka menu Analisis Sensitivitas]
    B --> C[Sistem menampilkan semua kriteria dengan bobot]
    C --> D[Admin mengatur slider bobot kriteria]
    D --> E[Sistem menghitung bobot ternormalisasi secara realtime]
    E --> F[Admin melihat perubahan ranking santri]
    F --> G{Ingin mengubah bobot lagi?}
    G -->|Ya| D
    G -->|Tidak| H[Admin menganalisis hasil sensitivitas]
    H --> I([End])
```

---

## 9. Flowchart - Use Case Diagram

```mermaid
flowchart LR
    subgraph System["SPK Pondok Pesantren"]
        UC1((Login))
        UC2((Manage Santri))
        UC3((Manage Kriteria))
        UC4((Manage Subkriteria))
        UC5((Manage Periode))
        UC6((Input Penilaian))
        UC7((Hitung SAW))
        UC8((Lihat Rekomendasi))
        UC9((Cetak Laporan))
        UC10((Analisis Sensitivitas))
        UC11((Lihat History))
        UC12((Export Data))
    end
    
    Admin["🧑‍💼 Admin"] --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    
    UC3 -.->|include| UC4
    UC6 -.->|include| UC7
    UC7 -.->|include| UC8
```

---

## 10. Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USER {
        uuid id PK
        string name
        string email UK
        string password
        timestamp created_at
    }
    
    SANTRI {
        uuid id PK
        string nis UK
        string nama
        string kelas
        string jenis_kelamin
        date tanggal_lahir
        string status
        float nilai_akhir
        timestamp created_at
    }
    
    KRITERIA {
        uuid id PK
        string nama_kriteria
        float bobot
        string jenis
        timestamp created_at
    }
    
    SUBKRITERIA {
        uuid id PK
        uuid kriteria_id FK
        string nama_subkriteria
        float nilai
        timestamp created_at
    }
    
    PERIODE {
        uuid id PK
        string nama
        string deskripsi
        boolean is_active
        timestamp created_at
    }
    
    PENILAIAN {
        uuid id PK
        uuid santri_id FK
        uuid kriteria_id FK
        uuid subkriteria_id FK
        uuid periode_id FK
        float nilai
        timestamp created_at
    }
    
    RIWAYAT_HITUNG {
        uuid id PK
        uuid santri_id FK
        uuid periode_id FK
        float nilai_akhir
        text alasan
        timestamp created_at
    }
    
    KRITERIA ||--o{ SUBKRITERIA : "has"
    SANTRI ||--o{ PENILAIAN : "assessed in"
    KRITERIA ||--o{ PENILAIAN : "evaluated by"
    SUBKRITERIA ||--o{ PENILAIAN : "scored with"
    PERIODE ||--o{ PENILAIAN : "belongs to"
    SANTRI ||--o{ RIWAYAT_HITUNG : "has calculation"
    PERIODE ||--o{ RIWAYAT_HITUNG : "belongs to"
```

---

## 11. Sequence Diagram - Proses Perhitungan SAW

```mermaid
sequenceDiagram
    participant U as Admin
    participant V as View
    participant C as PerhitunganController
    participant M as Models
    participant DB as Database
    
    U->>V: Buka halaman Perhitungan
    V->>C: index()
    C->>M: Get Kriteria & Santri
    M->>DB: Query data
    DB-->>M: Return data
    M-->>C: Return collections
    C-->>V: Render form
    
    U->>V: Pilih Santri & Isi Penilaian
    U->>V: Klik "Hitung"
    V->>C: hitung(request)
    C->>C: Validate request
    C->>M: Get active Periode
    M->>DB: Query periode
    DB-->>M: Return periode
    
    loop For each kriteria
        C->>M: Save/Update Penilaian
        M->>DB: updateOrCreate()
    end
    
    C->>C: hitungNilaiAkhir()
    Note over C: Normalization (Benefit/Cost)
    Note over C: Calculate weighted score
    Note over C: Generate recommendation
    
    C->>M: Update Santri nilai_akhir
    C->>M: Save RiwayatHitung
    M->>DB: Save to database
    
    C-->>V: Redirect to hasil
    V-->>U: Display result
```

---

## Ringkasan Fitur Utama

| No | Modul | Deskripsi |
|:--:|-------|-----------|
| 1 | **Authentication** | Login, Logout, Ganti Password |
| 2 | **Dashboard** | Statistik santri, kriteria, hasil penilaian |
| 3 | **Master Santri** | CRUD data santri, Import/Export Excel |
| 4 | **Master Kriteria** | CRUD kriteria dengan jenis Benefit/Cost |
| 5 | **Master Subkriteria** | CRUD subkriteria dengan nilai numerik |
| 6 | **Master Periode** | CRUD periode penilaian, aktivasi periode |
| 7 | **Penilaian** | Input nilai santri per kriteria |
| 8 | **Perhitungan SAW** | Normalisasi, pembobotan, scoring |
| 9 | **Rekomendasi** | Ranking santri, status rekomendasi |
| 10 | **Analisis Sensitivitas** | Simulasi perubahan bobot kriteria |
| 11 | **History** | Riwayat perhitungan per periode |
| 12 | **Cetak Laporan** | Export hasil ke PDF |

---

## Logika Rekomendasi

| Nilai Akhir | Status | Deskripsi |
|:-----------:|:------:|-----------|
| ≥ 0.70 | ✅ Direkomendasikan | Santri memenuhi kriteria dengan sangat baik |
| 0.40 - 0.69 | ⚠️ Dipertimbangkan | Santri memiliki potensi, perlu evaluasi lebih lanjut |
| < 0.40 | ❌ Tidak Direkomendasikan | Santri belum memenuhi kriteria minimum |
