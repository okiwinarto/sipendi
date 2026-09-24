# PANDUAN PENGGUNA (USER MANUAL) SIPENDI
**Sistem Peminjaman Kendaraan Dinas — RSUD Sidawangi**  
*Fase 1: Kendaraan Dinas Non-Ambulans*

---

## 1. Pendahuluan
Aplikasi **SIPENDI** (*Sistem Peminjaman Kendaraan Dinas*) adalah platform resmi terintegrasi RSUD Sidawangi untuk mendigitalkan seluruh siklus pengelolaan kendaraan dinas operasional. Sistem ini menggantikan proses permohonan manual berbahan kertas guna mewujudkan transparansi, akuntabilitas, ketertiban pemeliharaan armada, dan pencegahan bentrok jadwal dinas.

---

## 2. Peran Pengguna (Roles)
Sistem membedakan akses ke dalam 4 peran utama:
1. **User Aplikasi (Pegawai Pemohon):** Pegawai/staf instalasi dan unit kerja yang memerlukan fasilitas kendaraan dinas operasional RSUD Sidawangi.
2. **Kepala Garasi (Pool Kendaraan):** Pejabat teknis pool yang memverifikasi permohonan, menetapkan unit armada laik jalan, menugaskan sopir dinas pool, mencatat serah terima keluar/masuk (BAST), serta mengelola pemeliharaan dan pajak.
3. **Pimpinan / Direksi:** Pejabat penentu kebijakan yang memberikan otorisasi persetujuan akhir atau penolakan pengajuan perjalanan dinas.
4. **Administrator IT:** Administrator teknis pengelola master data pengguna, organisasi, konfigurasi sistem, dan audit jejak digital (*audit trail*).

---

## 3. Panduan untuk Pegawai Pemohon (User Aplikasi)

### 3.1 Masuk ke Portal
1. Akses alamat portal: `https://[domain-rsud]/portal` (atau `http://127.0.0.1:8000/portal` pada lingkungan intranet).
2. Masukkan **Email Resmi** dan **Password** Anda.
3. Setelah berhasil masuk, Anda akan tiba di halaman Beranda Portal dengan ringkasan status permohonan Anda.

### 3.2 Memeriksa Jadwal Armada (Kalender Ketersediaan)
1. Buka menu **Cek Ketersediaan Armada** atau klik **Kalender** pada bilah navigasi.
2. Kalender menampilkan status tiap unit mobil secara visual:
   - Warna **Biru / Oranye:** Mobil sedang terjadwal dinas.
   - Slot Kosong: Mobil berstatus siap dipinjam.
3. Anda dapat mengklik tanggal yang diinginkan untuk langsung membuka formulir pengajuan dengan tanggal terpilih secara otomatis.

### 3.3 Mengajukan Peminjaman Mobil Baru
1. Klik tombol **Ajukan Peminjaman Mobil Baru** di Beranda atau buka menu `/portal/ajukan`.
2. Isi formulir dengan lengkap:
   - **Tujuan Perjalanan:** Keterangan agenda kedinasan (contoh: *Koordinasi Pengadaan Obat dengan Dinkes Provinsi Jabar*).
   - **Kota Tujuan:** Kota lokasi dinas (contoh: *Bandung*, *Cirebon*, *Kuningan*).
   - **Waktu Keberangkatan:** Tanggal & jam berangkat rencana (minimal tanggal hari ini ke depan).
   - **Waktu Kembali Rencana:** Tanggal & jam kepulangan rencana.
   - **Jumlah Penumpang:** Estimasi jumlah orang yang ikut dalam rombongan.
   - **Kebutuhan Pengemudi:**
     - *Sopir Dinas Pool:* Ditugaskan sopir resmi dari bagian garasi RSUD.
     - *Swakemudi:* Dikemudikan sendiri oleh pegawai pemohon (wajib memiliki SIM aktif).
   - **Prioritas:** Pilih *Biasa* atau *Mendesak (Cito)* untuk penanganan darurat.
   - **Nomor & Unggah Surat Tugas:** Lampirkan scan surat tugas dinas (format PDF/JPG/PNG).
3. Klik tombol **Kirim Pengajuan Peminjaman**. Kode registrasi unik (contoh: `SPD/FAR/202609/0001`) akan diterbitkan secara otomatis.

### 3.4 Memantau Status & Membatalkan Pengajuan
1. Masuk ke menu **Peminjaman Saya** (`/portal/riwayat`).
2. Status permohonan dapat berupa:
   - `Diajukan`: Menunggu verifikasi teknis oleh Kepala Garasi.
   - `Diverifikasi Garasi`: Armada dan sopir telah disiapkan, menunggu persetujuan Pimpinan.
   - `Disetujui`: Siap berangkat, silakan datang ke pool pada hari H untuk serah terima.
   - `Ditolak Garasi` / `Ditolak Pimpinan`: Pengajuan tidak disetujui beserta alasan tertulis.
   - `Kendaraan Keluar`: Mobil sedang beroperasi di luar pool.
   - `Selesai`: Mobil telah dikembalikan ke pool dan dicek kondisinya.
3. **Pembatalan Mandiri:** Selama status masih `Diajukan`, Anda dapat mengklik tombol **Batalkan** apabila agenda dinas batal dilaksanakan.

---

## 4. Panduan untuk Kepala Garasi

### 4.1 Verifikasi Teknis & Alokasi Armada
1. Buka menu **Verifikasi** (`/portal/verifikasi`).
2. Tab **Menunggu Verifikasi** menampilkan daftar pengajuan berstatus `diajukan`.
3. Klik tombol **Verifikasi & Alokasikan**:
   - Pilih unit mobil yang berstatus *Tersedia*, pajak aktif, dan tidak sedang dalam masa servis.
   - Pilih sopir dinas pool yang bertugas (jika pemohon meminta sopir).
   - Tambahkan catatan kesiapan teknis kendaraan jika diperlukan.
   - Klik **Konfirmasi Verifikasi**. Pengajuan otomatis diteruskan ke Pimpinan.
4. **Penolakan:** Apabila tidak tersedia armada atau alasan teknis lain, klik tombol **Tolak**, masukkan alasan penolakan secara jelas, dan konfirmasi.

### 4.2 Serah Terima Digital (Checkout - Kendaraan Keluar)
1. Buka menu **Serah Terima** (`/portal/serah-terima`).
2. Pada tab **Siap Berangkat (Checkout)**, pilih pengajuan yang disetujui pemohon yang hadir di pool.
3. Klik tombol **Checkout (Keluar)**:
   - Catat angka kilometer terakhir (odometer).
   - Periksa posisi indikator bahan bakar (*E, 1/4, 1/2, 3/4, Full*).
   - Lakukan pemeriksaan fisik (*Ban Serep, Dongkrak, Kunci Roda, Segitiga Pengaman, P3K, STNK*).
   - Unggah foto fisik kondisi awal kendaraan.
   - Klik **Simpan & Konfirmasi Berangkat**. Status booking berubah menjadi `kendaraan_keluar` dan armada menjadi `dipinjam`.

### 4.3 Serah Terima Digital (Checkin - Kendaraan Kembali)
1. Pada menu **Serah Terima**, buka tab **Sedang Berdinas (Checkin)**.
2. Saat mobil kembali ke garasi, klik tombol **Checkin (Kembali)**:
   - Masukkan angka odometer masuk (sistem memvalidasi KM kembali harus $\ge$ KM keluar).
   - Catat posisi BBM saat kembali.
   - Periksa kembali kelengkapan darurat dan fisik mobil.
   - Berikan penilaian rating kebersihan/kondisi (1 s/d 5 bintang).
   - Jika terdapat lecet atau kerusakan, centang *Ada Kerusakan* dan unggah foto bukti serta catatan kronologi.
   - Klik **Konfirmasi Pengembalian**. Status mobil otomatis kembali menjadi `tersedia`.
3. Klik tombol **Cetak BAST** untuk mengunduh bukti Berita Acara Serah Terima dalam format cetak resmi.

---

## 5. Panduan untuk Pimpinan / Direksi

### 5.1 Menyetujui atau Menolak Permohonan
1. Buka menu **Persetujuan** (`/portal/persetujuan`).
2. Antarmuka didesain ringkas (*mobile-first*) sehingga Pimpinan dapat meninjau melalui smartphone:
   - Nama pemohon dan unit kerja.
   - Kota & agenda dinas.
   - Waktu berangkat s/d kembali.
   - Unit mobil dan nama sopir yang telah dialokasikan oleh Kepala Garasi.
   - Tautan pratinjau berkas surat tugas dinas.
3. Klik tombol hijau **Setujui** untuk mengesahkan permohonan dinas, atau tombol merah **Tolak** (wajib mencantumkan alasan penolakan).
4. Pemohon dan Kepala Garasi akan langsung menerima notifikasi status keputusan tersebut.

### 5.2 Dashboard Analitik Eksekutif
1. Masuk ke menu **Dashboard** (`/portal/dashboard`).
2. Tinjau metrik kunci:
   - Total frekuensi peminjaman dalam periode terpilih (bulan ini / tahun ini).
   - Rasio utilisasi armada aktif.
   - Grafik frekuensi perjalanan per unit kerja RSUD.
   - Peringkat kendaraan paling produktif.

---

## 6. Panduan untuk Administrator IT

### 6.1 Panel Manajemen Backoffice (Filament)
1. Akses panel admin di `/admin`.
2. Kelola master data:
   - **Unit Kerja:** Menambah atau memperbarui instalasi kerja RSUD Sidawangi.
   - **Armada Kendaraan:** Mendaftarkan nomor polisi, nomor mesin/rangka, masa berlaku STNK, PKB 5 tahunan, uji KIR, serta interval servis berkala (kilometer/bulan).
   - **Sopir Dinas:** Mengelola data pengemudi dan tanggal kedaluwarsa SIM.
   - **Manajemen Pengguna & Peran:** Mengatur NIP, unit kerja, dan peran akun pegawai.

### 6.2 Audit Jejak Digital (Audit Trail)
- Seluruh tindakan pembuatan pengajuan, verifikasi garasi, keputusan pimpinan, serta checkout/checkin tercatat secara permanen melalui Spatie Activity Log dan dapat ditinjau di menu **Activity Logs**.

### 6.3 Pengingat Otomatis Servis & Pajak (Cron)
- Sistem menjalankan pengecekan terjadwal setiap hari pukul 06.00 WIB untuk mengingatkan:
  - Servis berkala berdasarkan selisih kilometer odometer.
  - Jatuh tempo pajak tahunan & 5 tahunan (H-30, H-14, H-7, H-1).
  - Masa berlaku SIM pengemudi dinas.
