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

## 📁 Struktur Folder

```
jadwal-kuliah/
├── config/
│   └── database.php 
├── includes/
│   └── functions.php
├── index.php  
├── tambah.php
├── edit.php
├── hapus.php
└── README.md
```