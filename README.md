# Sistem Informasi Manajemen Santri (SIMS)
**Pondok Pesantren Annuqayah Latee II**

![Development Method](https://img.shields.io/badge/Method-Waterfall-blue)
![Laravel](https://img.shields.io/badge/Framework-Laravel-red)
![Tailwind](https://img.shields.io/badge/UI-TailwindCSS-blue)

Aplikasi ini adalah Sistem Informasi Manajemen (SIM) yang dirancang untuk digitalisasi administrasi pondok pesantren. Sistem ini dikembangkan menggunakan metode **Waterfall** untuk memastikan alur pengembangan yang terstruktur dan sesuai dengan kebutuhan pengguna.

## 🚀 Fitur Utama

### 1. Dashboard Integrasi
- Ringkasan statistik real-time (Jumlah Santri, Izin Aktif, Pelanggaran Terbaru).
- Grafik visualisasi data santri per rayon/jenjang.

### 2. Manajemen Data Master
- **Data Santri**: Pengelolaan biodata lengkap santri.
- **Data Akademik**: Tahun ajaran, periode, kelas, dan jenjang pendidikan.
- **Data Asrama**: Manajemen gedung (rayon) dan kamar.

### 3. Modul Perizinan (Licensing)
- **Digitalisasi Izin**: Pengajuan izin pulang/keluar secara digital.
- **Validasi Bertingkat**: Sistem persetujuan oleh pengurus berwenang.
- **Surat Jalan Otomatis**: Cetak surat izin resmi dengan format baku.
- **Riwayat Perizinan**: Tracking history keluar-masuk santri.

### 4. Modul Kedisiplinan (Violations)
- **Pencatatan Pelanggaran**: Input data pelanggaran santri.
- **Poin & Sanksi**: Manajemen jenis pelanggaran dan bobot poin.
- **Laporan Kedisiplinan**: Rekapitulasi perilaku santri untuk evaluasi.

### 5. Modul Keuangan (SPP)
- **Pembayaran SPP**: Pencatatan transaksi pembayaran bulanan.
- **Tagihan & Tunggakan**: Monitoring status pembayaran santri.
- **Laporan Keuangan**: Rekap pemasukan periode tertentu.

## 🛠️ Metode Pengembangan: Waterfall

Sistem ini dibangun mengikuti tahapan SDLC **Waterfall**:
1.  **Requirement Analysis**: Analisis kebutuhan administrasi pesantren.
2.  **System Design**: Perancangan database (ERD) dan antarmuka (UI/UX).
3.  **Implementation**: Coding menggunakan Laravel & Tailwind CSS.
4.  **Testing**: Pengujian fungsionalitas sistem (Black Box Testing).
5.  **Maintenance**: Pemeliharaan dan update fitur.

## 📝 Alur Sistem & Diagram

### 1. Use Case Diagram (Global)
```mermaid
usecaseDiagram
    actor "Administrator" as Admin
    actor "Pengurus Asrama" as Staff

    package "Sistem Informasi Santri" {
        usecase "Manajemen Data Santri" as UC_Santri
        usecase "Manajemen Perizinan" as UC_Izin
        usecase "Manajemen Pelanggaran" as UC_Langgar
        usecase "Manajemen Keuangan (SPP)" as UC_SPP
        usecase "Laporan & Cetak" as UC_Report
    }

    Admin --> UC_Santri
    Admin --> UC_Izin
    Admin --> UC_Langgar
    Admin --> UC_SPP
    Admin --> UC_Report

    Staff --> UC_Izin
    Staff --> UC_Langgar
```

### 2. Entity Relationship Diagram (ERD)
Sesuaikan dengan struktur database saat ini:

```mermaid
erDiagram
    USERS {
        uuid id PK
        string name
        string email
        string role
    }
    
    STUDENTS {
        uuid id PK
        string nis UK
        string name
        uuid rayon_id FK
        uuid room_id FK
        string status
    }
    
    STUDENT_LICENSES {
        uuid id PK
        uuid student_id FK
        date start_date
        date end_date
        string type
        string status
    }
    
    VIOLATIONS {
        uuid id PK
        uuid student_id FK
        uuid violation_type_id FK
        date date
        string note
    }
    
    SPP_PAYMENTS {
        uuid id PK
        uuid student_id FK
        uuid academic_year_id FK
        int month
        decimal amount
    }

    STUDENTS ||--o{ STUDENT_LICENSES : "requests"
    STUDENTS ||--o{ VIOLATIONS : "commits"
    STUDENTS ||--o{ SPP_PAYMENTS : "pays"
    USERS ||--o{ STUDENT_LICENSES : "approves"
```

### 3. Detail Modul: Perizinan
**Flowchart Validasi Izin**
```mermaid
flowchart TD
    Start([Mulai]) --> InputData[Input Data Santri & Tanggal]
    InputData --> CheckDurasi{Cek Durasi Izin}
    CheckDurasi -->|<= 3 Hari| StatusOK[Status: Approved]
    CheckDurasi -->|> 3 Hari| CheckAlasan{Cek Alasan}
    CheckAlasan -->|Sakit/Penting| StatusOK
    CheckAlasan -->|Lainnya| Warning[Tampilkan Peringatan]
    Warning --> StatusOK
    
    StatusOK --> SaveDB[(Simpan ke Database)]
    SaveDB --> GenSurat[Generate Surat Izin PDF]
    GenSurat --> End([Selesai])
```

### 4. Desain Prototipe
**Dashboard Perizinan**
![Dashboard Perizinan Mockup](public/docs/images/licensing_dashboard_mockup.png)

---
**Skripsi Tahun 2025/2026**
