## Sistem Informasi Jadwal Kuliah

Aplikasi manajemen jadwal perkuliahan berbasis web dengan PHP Native dan Tailwind CSS. Sistem ini memungkinkan pengguna untuk mengelola jadwal perkuliahan dengan tampilan yang elegan, responsif, dan mudah digunakan.

## ✨ Fitur Utama

- **CRUD Lengkap** - Create, Read, Update, Delete jadwal kuliah
- **Pengurutan Dinamis** - Jadwal otomatis diurutkan berdasarkan hari (dimulai dari hari ini)
- **Tampilan per Hari** - Jadwal dikelompokkan berdasarkan hari dengan navigasi yang mudah
- **Notifikasi Real-time** - Alert sukses/gagal untuk setiap operasi
- **Desain Responsif** - Tampilan menyesuaikan dengan berbagai ukuran layar
- **Tema Hitam Putih Elegan** - Desain minimalis dengan fokus pada keterbacaan
- **Keamanan Dasar** - Menggunakan prepared statements untuk mencegah SQL injection

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP Native (tanpa framework)
- **Frontend**: Tailwind CSS
- **Database**: MySQL
- **Font**: Google Fonts (Inter)

## 🛠️ Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/username/jadwal-kuliah.git
cd jadwal-kuliah
```

2. **Konfigurasi Database**
- Buka file `config/database.php`
- Sesuaikan konfigurasi dengan database Anda:
```

## 📁 Struktur Folder

```
jadwal-kuliah/
├── config/
│   └── database.php          # Konfigurasi koneksi database
├── includes/
│   └── functions.php          # Fungsi-fungsi utama aplikasi
├── index.php                   # Halaman utama (daftar jadwal)
├── tambah.php                  # Form tambah jadwal
├── edit.php                    # Form edit jadwal
├── hapus.php                    # Proses hapus jadwal
└── README.md                    # Dokumentasi project
```

