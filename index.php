<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Cek status dari URL
$status = $_GET['status'] ?? '';
$action = $_GET['action'] ?? '';

$showAlert = false;
$alertType = '';
$alertMessage = '';

if ($status == 'success') {
    $showAlert = true;
    $alertType = 'success';
    if ($action == 'tambah') {
        $alertMessage = 'Jadwal berhasil ditambahkan!';
    } elseif ($action == 'edit') {
        $alertMessage = 'Jadwal berhasil diperbarui!';
    } elseif ($action == 'hapus') {
        $alertMessage = 'Jadwal berhasil dihapus!';
    }
} elseif ($status == 'error') {
    $showAlert = true;
    $alertType = 'error';
    if ($action == 'tambah') {
        $alertMessage = 'Gagal menambahkan jadwal. Silakan coba lagi.';
    } elseif ($action == 'edit') {
        $alertMessage = 'Gagal memperbarui jadwal. Silakan coba lagi.';
    } elseif ($action == 'hapus') {
        $alertMessage = 'Gagal menghapus jadwal. Silakan coba lagi.';
    }
}

// Ambil semua data jadwal yang sudah diurutkan
$jadwal_by_day = getJadwalByDay($conn);
$hari_ini = getHariIni();
$ordered_days = getOrderedDays();

// Ambil semua data jadwal yang sudah diurutkan
$jadwal_by_day = getJadwalByDay($conn);
$hari_ini = getHariIni();
$ordered_days = getOrderedDays();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah | Sistem Informasi Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        }

        .day-indicator {
            position: relative;
            overflow: hidden;
        }

        .day-indicator::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #000, transparent);
        }

        .today-badge {
            background: linear-gradient(135deg, #f5f5f5 0%, #e5e5e5 100%);
        }
    </style>
</head>

<body class="bg-[#fafafa] text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg"><i class="fas fa-user-graduate"></i></span>
                    </div>
                    <span class="font-semibold text-gray-900 text-lg tracking-tight">JadwalKuliah</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500 hidden sm:block">
                        <?= $hari_ini ?>, <?= date('d M Y') ?>
                    </span>
                    <a href="tambah.php" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Jadwal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Alert Notifications -->
    <?php if ($showAlert): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="<?= $alertType == 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' ?> border rounded-xl p-4 animate-fade-in">
                <div class="flex items-center">
                    <?php if ($alertType == 'success'): ?>
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php endif; ?>
                    <p class="ml-2 text-sm <?= $alertType == 'success' ? 'text-green-700' : 'text-red-700' ?>">
                        <?= $alertMessage ?>
                    </p>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                        <svg class="w-4 h-4 <?= $alertType == 'success' ? 'text-green-600' : 'text-red-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
        </style>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-light text-gray-900 tracking-tight">Jadwal Perkuliahan</h1>
            <!-- <p class="text-gray-500 mt-1">Jadwal diurutkan berdasarkan hari, dimulai dari <?= $ordered_days[0] ?></p> -->
        </div>

        <!-- Schedule by Day -->
        <?php if (empty($jadwal_by_day)): ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal</h3>
                <p class="text-gray-500 mb-6">Mulai tambahkan jadwal perkuliahan Anda</p>
                <a href="tambah.php" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal Pertama
                </a>
            </div>
        <?php else: ?>
            <!-- Day Navigation -->
            <div class="flex overflow-x-auto pb-4 mb-6 gap-2 scrollbar-hide">
                <?php foreach ($ordered_days as $day): ?>
                    <?php
                    $hasSchedule = isset($jadwal_by_day[$day]);
                    $isToday = ($day == $hari_ini);
                    ?>
                    <a href="#<?= strtolower($day) ?>"
                        class="flex-shrink-0 px-4 py-2 <?= $isToday ? 'bg-gray-900 text-white' : ($hasSchedule ? 'bg-white text-gray-900 border border-gray-200' : 'bg-gray-50 text-gray-400 border border-gray-100') ?> rounded-xl text-sm font-medium hover:<?= $isToday ? 'bg-gray-800' : 'bg-gray-100' ?> transition-colors duration-200">
                        <?= $day ?>
                        <?php if ($hasSchedule): ?>
                            <span class="ml-2 <?= $isToday ? 'bg-white text-gray-900' : 'bg-gray-200 text-gray-600' ?> px-2 py-0.5 rounded-full text-xs">
                                <?= count($jadwal_by_day[$day]) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Schedule Grid by Day -->
            <div class="space-y-8">
                <?php foreach ($ordered_days as $day): ?>
                    <?php if (isset($jadwal_by_day[$day])): ?>
                        <section id="<?= strtolower($day) ?>" class="scroll-mt-20">
                            <!-- Day Header -->
                            <div class="flex items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-900"><?= $day ?></h2>
                                <?php if ($day == $hari_ini): ?>
                                    <span class="ml-3 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full today-badge">
                                        Hari Ini
                                    </span>
                                <?php endif; ?>
                                <div class="flex-1 ml-4 h-px bg-gradient-to-r from-gray-200 to-transparent"></div>
                            </div>

                            <!-- Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($jadwal_by_day[$day] as $row): ?>
                                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden card-hover">
                                        <!-- Card Header -->
                                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white">
                                                        <?= htmlspecialchars($row['kelas']) ?>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900 mt-2">
                                                        <?= htmlspecialchars($row['nama_kuliah']) ?>
                                                    </h3>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="px-6 py-4">
                                            <div class="space-y-3">
                                                <!-- Jam -->
                                                <div class="flex items-center text-sm">
                                                    <div class="w-8 text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-gray-600">
                                                        <?= formatJam($row['jam_mulai']) ?> - <?= formatJam($row['jam_selesai']) ?>
                                                        <span class="text-gray-400 text-xs ml-2">
                                                            (<?= hitungDurasi($row['jam_mulai'], $row['jam_selesai']) ?>)
                                                        </span>
                                                    </span>
                                                </div>

                                                <!-- Ruangan -->
                                                <div class="flex items-center text-sm">
                                                    <div class="w-8 text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-gray-600">Ruangan <?= htmlspecialchars($row['ruangan']) ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Footer -->
                                        <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>ID: <?= $row['id'] ?></span>
                                                <span><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Summary Card -->
        <?php if (!empty($jadwal_by_day)): ?>
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Jadwal</h3>
                        <p class="text-2xl font-light text-gray-900 mt-1">
                            <?= array_sum(array_map('count', $jadwal_by_day)) ?> Mata Kuliah
                        </p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-sm font-medium text-gray-500">Hari Aktif</h3>
                        <p class="text-2xl font-light text-gray-900 mt-1">
                            <?= count($jadwal_by_day) ?> Hari
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                ©2025 JadwalKuliah. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        // Smooth scroll untuk navigasi hari
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>