# Catatan Dasar Aplikasi KMS Suara

## Gambaran Umum

Aplikasi ini adalah sistem operasional terminal bus berbasis PHP CodeIgniter dengan tampilan AdminLTE. Dari struktur kode, nama sidebar, controller, model, dan skema database, aplikasi ini berfungsi sebagai platform untuk monitoring bus, pengumuman audio, display TV/signage, dan pengelolaan lost and found.

Brand/tampilan aplikasi mengarah ke **TTPG AUDIO**, kemungkinan digunakan untuk operasional Terminal Terpadu Pulo Gebang.

## Fungsi Utama

### 1. Monitoring Pergerakan Bus

Aplikasi mencatat dan memantau pergerakan bus di area terminal. Status/area bus yang digunakan:

- `masuk`
- `kedatangan`
- `pengendapan`
- `keberangkatan`
- `berangkat`

Data utama bus disimpan di tabel `audio_queue`, sedangkan riwayat perpindahan area disimpan di tabel `bus_history`.

Fitur terkait:

- Input bus masuk berdasarkan plat nomor.
- Pencarian nama PO dari database kedua/manifest.
- Update area bus.
- Pencegahan bus langsung keluar tanpa melewati area pelayanan terminal.
- History waktu masuk, waktu keluar, dan durasi di tiap area.
- Display TV per area.

File penting:

- `application/controllers/Bus_monitor.php`
- `application/models/Bus_model.php`
- `application/views/bus_monitor/`

### 2. Sistem Pengumuman Audio

Aplikasi memiliki sistem antrian audio untuk pengumuman terminal. Semua pengumuman masuk ke tabel `audio_queue` dengan status seperti:

- `pending`
- `playing`
- `done`
- `cancelled`

Jenis audio yang didukung:

- `bus`: pengumuman bus masuk.
- `announcer`: panggilan manual penumpang/pengumuman umum.
- `prayer`: pengumuman waktu sholat.
- `ads`: iklan atau pesan layanan.
- `youtube`: playlist musik.

Fitur terkait:

- Ambil audio berikutnya berdasarkan prioritas.
- Tandai audio selesai.
- Replay audio.
- Tambah pengumuman manual.
- Tambah pengumuman sholat.
- Tambah iklan/pesan layanan.
- Jadwal iklan.
- Playlist musik YouTube.

Kontrol utama di box **Status Audio Saat Ini**:

- `Putar Berikutnya`: mengambil item `pending` berikutnya dari `audio_queue`, mengubahnya menjadi `playing`, lalu membacakannya.
- `Muat Ulang`: menyegarkan tampilan daftar queue tanpa mengubah status data.
- `Lewati Audio Ini`: menghentikan suara lokal dan menandai item yang sedang `playing` sebagai `done`, sehingga tidak diputar ulang.
- `Stop Suara`: hanya menghentikan suara lokal/browser dan musik latar, tanpa mengubah status queue di database.

File penting:

- `application/controllers/Audio.php`
- `application/models/Audio_model.php`
- `application/views/audio.php`

#### Jadwal Iklan dan Manajemen Iklan

Konfigurasi jadwal iklan dipisahkan ke menu **Manajemen Iklan** dengan URL `manajemen_iklan`. Halaman `/audio` tetap menjadi halaman pemutar/antrian audio yang mengecek jadwal aktif dan memasukkan iklan ke queue saat waktunya tiba.

Alur konfigurasi jadwal:

- Operator membuka menu `Manajemen Iklan`.
- Operator menambah atau mengedit judul, pesan iklan, interval putar, tanggal mulai/selesai, jam mulai/selesai, hari pengulangan, dan status aktif.
- Data disimpan ke tabel `ads_schedule`.

Alur pemutaran jadwal di halaman `/audio`:

- Saat halaman `/audio` terbuka, browser memanggil `audio/get_ads_schedule`.
- Jadwal disimpan di variabel JavaScript `adsData`.
- Setiap 60 detik browser menjalankan pengecekan jadwal.
- Jika tanggal, jam, hari, status aktif, dan interval sudah cocok, browser memanggil `audio/add_ads`.
- `audio/add_ads` memasukkan pesan ke `audio_queue` sebagai type `ads`.
- Audio queue kemudian dibacakan oleh TTS/browser.
- Setelah jadwal dipicu, browser memanggil `audio/update_last_played` agar jadwal tidak langsung terpicu lagi sebelum interval berikutnya.

Catatan perbaikan:

- Parameter durasi tidak diperlukan untuk iklan TTS, karena lama bacaan mengikuti jumlah kata/panjang teks.
- Sumber looping sebelumnya berasal dari dua jalur pemicu jadwal yang berjalan bersamaan: JavaScript halaman `/audio` dan auto-inject di `Audio_model::get_all_queue()`.
- Auto-inject dari `get_all_queue()` sebaiknya tidak dilakukan, karena endpoint refresh queue dipanggil berkala dan bisa memasukkan iklan terus-menerus saat jadwal masih aktif.
- Pemicu jadwal harus menghormati `interval_minutes` dan `last_played`.
- Jika ingin fitur tetap berjalan walaupun halaman `/audio` ditutup, gunakan cron/backend scheduler yang juga menghormati `last_played`, bukan proses dari endpoint refresh queue.

Fungsi modul Manajemen Iklan:

- Menampilkan daftar jadwal iklan dalam bentuk tabel.
- Tambah jadwal iklan.
- Edit jadwal iklan.
- Aktif/nonaktifkan jadwal iklan.
- Hapus jadwal iklan.
- Filter berdasarkan kata kunci dan status.

File penting modul Manajemen Iklan:

- `application/controllers/Manajemen_iklan.php`
- `application/models/Ads_model.php`
- `application/views/manajemen_iklan/index.php`
- `application/views/manajemen_iklan/form.php`

### 3. Dashboard Operasional

Dashboard menampilkan ringkasan kondisi terminal secara harian dan realtime.

Data yang ditampilkan:

- Total bus masuk hari ini.
- Bus aktif di terminal.
- Bus di area kedatangan.
- Bus di area pengendapan.
- Bus di area keberangkatan.
- Bus keluar/berangkat.
- Status kapasitas area.
- Aktivitas terbaru.
- Laporan bulanan.

File penting:

- `application/controllers/Dashboard.php`
- `application/views/dashboard.php`
- `application/views/dashboard/kapasitas.php`

### 4. TV Display dan Monitor Per Area

Aplikasi menyediakan halaman display untuk layar TV/monitor publik.

Display yang tersedia:

- Semua area/global.
- TV Bus Masuk.
- TV Kedatangan.
- TV Pengendapan.
- TV Keberangkatan.
- TV Pintu Keluar.

File penting:

- `application/views/bus_monitor/tv.php`
- `application/views/bus_monitor/tv_masuk.php`
- `application/views/bus_monitor/tv_kedatangan.php`
- `application/views/bus_monitor/tv_pengendapan.php`
- `application/views/bus_monitor/tv_keberangkatan.php`
- `application/views/bus_monitor/tv_keluar.php`

### 5. Digital Signage

Aplikasi memiliki modul digital signage untuk mengatur konten layar tertentu.

Konten yang bisa dikelola:

- Gambar.
- Video.
- Teks.
- Layout seperti `single`, `dual`, `video_text`, dan `carousel`.

Data signage disimpan di tabel `signages`.

File penting:

- `application/controllers/Signage.php`
- `application/controllers/Player.php`
- `application/models/Signage_model.php`
- `application/views/signage/`

### 6. Lost and Found

Aplikasi memiliki modul pencatatan barang ditemukan dan proses pengambilan barang.

Data yang dicatat:

- Nama barang.
- Kategori.
- Deskripsi.
- Lokasi ditemukan.
- Tanggal ditemukan.
- Nama dan kontak penemu.
- Status `ditemukan` atau `diambil`.
- Data pengambil.
- Foto barang, foto pengambilan, dan foto identitas.

Saat barang ditemukan, aplikasi juga bisa membuat pengumuman audio otomatis.

File penting:

- `application/controllers/Lost.php`
- `application/models/Lost_model.php`
- `application/views/lost/`

### 7. Integrasi CCTV / ALPR

Aplikasi memiliki endpoint webhook untuk menerima hasil pembacaan plat nomor otomatis dari CCTV/ALPR.

Endpoint:

- `cctv/alpr`

Alur:

- Menerima plat nomor via POST.
- Validasi token keamanan.
- Normalisasi plat nomor.
- Cari nama PO dari database kedua.
- Buat data bus masuk.
- Buat history area `masuk`.
- Buat pengumuman audio bus masuk.
- Catat aktivitas.

File penting:

- `application/controllers/Cctv.php`

### 8. Autentikasi dan Log Aktivitas

Aplikasi memiliki login user dan pencatatan aktivitas.

Data user disimpan di tabel `users`.

Role yang tersedia:

- `admin`
- `teknisi`

Aktivitas seperti login, logout, update bus, input bus masuk, dan deteksi ALPR dicatat melalui `Activity_model`.

File penting:

- `application/controllers/Auth.php`
- `application/models/User_model.php`
- `application/models/Activity_model.php`
- `application/views/auth/login.php`

## Database Utama

File SQL utama:

- `suara (3).sql`

Tabel penting:

- `audio_queue`
- `bus_history`
- `ads_schedule`
- `activity_logs`
- `lost_found`
- `signages`
- `users`
- `youtube_playlist`

## Kesimpulan

Aplikasi ini adalah platform operasional terminal yang menggabungkan:

- Monitoring pergerakan bus.
- Pengumuman audio terminal.
- Display TV informasi bus.
- Digital signage.
- Lost and found.
- Integrasi kamera ALPR.
- Dashboard dan laporan operasional.

Secara praktis, aplikasi ini dipakai untuk membantu petugas terminal mengatur informasi bus, mengumumkan informasi penting ke penumpang, dan menampilkan status operasional di layar publik.
