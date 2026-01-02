# SPK Kepulangan Santri - Metode SMART
**Sistem Pendukung Keputusan Kepulangan Santri Pondok Pesantren Annuqayah Latee II**

![SPK Badge](https://img.shields.io/badge/Method-SMART-green)
![Laravel](https://img.shields.io/badge/Framework-Laravel-red)
![Tailwind](https://img.shields.io/badge/UI-TailwindCSS-blue)

Aplikasi ini adalah Sistem Pendukung Keputusan (SPK) yang dirancang untuk membantu pengurus pondok pesantren dalam menentukan rekomendasi kepulangan santri berdasarkan kriteria-kriteria objektif menggunakan metode **SMART (Simple Multi Attribute Rating Technique)**.

## 🚀 Fitur Utama

### 1. Dashboard Eksekutif
- Statistik jumlah santri, kriteria, dan hasil rekomendasi.
- Visualisasi grafik (Chart.js) untuk sebaran status dan top ranking santri.
- Akses cepat ke modul-modul utama.

### 2. Manajemen Data Master
- **Data Santri**: CRUD data santri lengkap dengan fitur **Import & Export Excel**.
- **Data Kriteria**: Pengaturan bobot kriteria dinamis dengan validasi total bobot.
- **Data Periode**: Manajemen periode penilaian (misal: "Periode Maret 2024") untuk pengarsipan riwayat yang rapi.

### 3. Sistem Penilaian & Perhitungan SMART
- **Input Penilaian**: Form penilaian santri berdasarkan kriteria yang aktif.
- **Kalkulasi Otomatis**:
    - Normalisasi Bobot ($w_j / \Sigma w$).
    - Nilai Utility ($u_i(a_i)$).
    - Nilai Akhir ($u_i(a_i) \times w_{normal}$).
- **Transparansi Perhitungan**: Fitur "Detail Perhitungan" yang menampilkan rumus dan langkah-langkah kalkulasi step-by-step untuk setiap santri.

### 4. Analisis Sensitivitas (Robustness Test)
- Fitur simulasi untuk menguji ketahanan (robustness) hasil keputusan.
- Pengguna dapat mengubah bobot kriteria secara real-time menggunakan **Slider UI**.
- Membandingkan **Ranking Lama vs Ranking Baru** untuk melihat dampak perubahan bobot.
- Visualisasi indikator input vs normalisasi otomatis.

### 5. Laporan & Output
- **Rekomendasi Keputusan**: Status "Direkomendasikan", "Pertimbangkan", atau "Tidak Direkomendasikan" berdasarkan threshold nilai.
- **Cetak PDF**: Laporan resmi dengan kop surat, daftar peringkat, dan tanda tangan pimpinan.

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel (PHP Framework)
- **Frontend**: Blade Templates, Tailwind CSS
- **Interactivity**: Alpine.js (untuk Modal, Dropdown, dan UI reaktif tanpa reload)
- **Database**: MySQL
- **Library Pendukung**:
    - `maatwebsite/excel` (Import/Export Data)
    - `dompdf/dompdf` (Cetak Laporan PDF)
    - `chart.js` (Visualisasi Grafik)

## 📦 Cara Instalasi

Ikuti langkah berikut untuk menjalankan proyek ini di komputer lokal (Localhost):

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/spk-pondok.git
    cd spk-pondok
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment**
    - Copy file `.env.example` menjadi `.env`.
    - Atur koneksi database di file `.env`:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=spk_pondok
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Key & Migrate Database**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```
    *(Gunakan `--seed` untuk mengisi database dengan data dummy awal)*

5.  **Jalankan Aplikasi**
    ```bash
    php artisan serve
    ```
    Buka `http://127.0.0.1:8000` di browser.

## 📝 Alur Penggunaan

1.  **Login** sebagai Administrator.
2.  Masuk ke menu **Periode**, buat periode baru dan set sebagai "Aktif".
3.  Pastikan **Data Kriteria** dan bobotnya sudah sesuai.
4.  Input **Data Santri** (bisa manual atau import Excel).
5.  Lakukan **Penilaian** pada menu SPK SMART -> Hitung.
6.  Lihat hasil pada menu **Rekomendasi**.
7.  (Opsional) Gunakan **Analisis Sensitivitas** untuk simulasi perubahan bobot.
8.  Cetak laporan hasil keputusan.

---
**Skripsi Tahun 2025/2026**
