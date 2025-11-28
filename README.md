Light Novel Reader

Platform manajemen koleksi Light Novel berbasis Web. Proyek ini memungkinkan pengguna membaca informasi, melacak progres membaca, dan mengelola koleksi novel pribadi menggunakan integrasi Jikan API (MyAnimeList). Dibangun sebagai Tugas Akhir menggunakan Laravel 11.

⚡ Fitur Utama

    Integrasi API Eksternal: Mengambil data novel real-time dari Jikan API (MyAnimeList).

    Perpustakaan Pribadi: Kelola koleksi dengan status Ingin Dibaca, Sedang Dibaca, atau Selesai.

    Reading History: Pelacakan otomatis chapter terakhir yang dibaca.

    Sistem Review: Berikan rating dan komentar pada novel favorit.

    Optimasi Performa: Implementasi Server-side Caching untuk meminimalisir rate limit API.

🛠️ Tech Stack

    Framework: Laravel 11.x

    Database: SQLite

    Auth: Laravel Breeze

    API: Jikan API v4

    Frontend: Blade Templates (Tailwind CSS Ready)

🚀 Instalasi

Pastikan Anda telah menginstal PHP >= 8.2, Composer, dan Node.js.

    Clone Repository
    Bash

git clone https://github.com/username/light-novel-reader.git
cd light-novel-reader

Install Dependencies
Bash

composer install
npm install

Setup Environment Salin file .env dan generate app key.
Bash

cp .env.example .env
php artisan key:generate

Konfigurasi Database (SQLite) Buka file .env, hapus konfigurasi DB lama dan ubah menjadi:
Ini, TOML

DB_CONNECTION=mysql
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=KuroReader
DB_USERNAME=root
DB_PASSWORD=

Migrasi & Build
Bash

php artisan migrate
npm run build

Jalankan Server
Bash

    php artisan serve

    Akses aplikasi di: http://localhost:8000

🗄️ Skema Database & Arsitektur

Aplikasi ini menggunakan pendekatan Hybrid Data Storage:

    Lokal (SQLite): Menyimpan data User, Library Status, History Baca, dan Review.

    Eksternal (API): Data detail novel (Judul, Sinopsis, Cover) diambil langsung dari Jikan API dan tidak disimpan permanen di database lokal untuk menghemat ruang dan memastikan data selalu up-to-date.

Tabel Utama:

    users: Autentikasi pengguna.

    user_libraries: Menyimpan relasi user dengan novel (via ID API) dan status baca.

    reading_histories: Mencatat timestamp dan chapter terakhir.

    reviews: Menyimpan rating dan komentar user.

🔌 API & Caching Strategy

Aplikasi menggunakan Jikan API v4. Karena adanya Rate Limiting dari API, sistem caching diterapkan pada service layer:
Tipe Data	Durasi Cache
Search Results	1 Jam (3600s)
Novel Details	1 Jam (3600s)
Popular Novels	2 Jam (7200s)

🛣️ Struktur Route

    Public:
    
        GET / (Homepage/Popular)

        GET /novels (Search & Detail)

    User (Auth Required):

        GET /library (Daftar koleksi)

        POST /history (Update progress baca)

        POST /reviews (Kirim review)

🧪 Troubleshooting

Masalah: "Database is locked" SQLite memiliki keterbatasan pada concurrent writes. Jika terjadi error ini, tambahkan konfigurasi berikut pada config/database.php di bagian koneksi sqlite:
PHP

'busy_timeout' => 5000,

Masalah: API Rate Limit Jika data tidak muncul, kemungkinan IP terkena rate limit dari Jikan. Tunggu beberapa saat atau perpanjang durasi cache di NovelApiService.

📄 Lisensi & Kredit

Proyek ini dibuat untuk keperluan akademik (Tugas Akhir).
