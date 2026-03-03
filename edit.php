<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? 0;
$jadwal = getJadwalById($conn, $id);

if (!$jadwal) {
    header('Location: index.php?status=error&message=data_tidak_ditemukan');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (updateJadwal($conn, $id, $_POST)) {
        // Redirect ke index dengan parameter success
        header('Location: index.php?status=success&action=edit');
        exit;
    } else {
        // Jika gagal, redirect ke index dengan parameter error
        header('Location: index.php?status=error&action=edit');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal | Jadwal Kuliah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }
    </style>
</head>
<body class="bg-[#fafafa] text-gray-800">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="index.php" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">📚</span>
                    </div>
                    <span class="font-semibold text-gray-900 text-lg tracking-tight">JadwalKuliah</span>
                </a>
                <a href="index.php" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-light text-gray-900 tracking-tight">Edit Jadwal</h1>
                <p class="text-gray-500 text-sm mt-1">Edit informasi jadwal perkuliahan</p>
            </div>

            <!-- Form -->
            <form method="POST" action="" class="p-8 space-y-6">
                <!-- Nama Kuliah -->
                <div>
                    <label for="nama_kuliah" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Mata Kuliah <span class="text-gray-400">*</span>
                    </label>
                    <input type="text" 
                           id="nama_kuliah" 
                           name="nama_kuliah" 
                           value="<?= htmlspecialchars($jadwal['nama_kuliah']) ?>"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700">
                </div>

                <!-- Kelas -->
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-gray-400">*</span>
                    </label>
                    <input type="text" 
                           id="kelas" 
                           name="kelas" 
                           value="<?= htmlspecialchars($jadwal['kelas']) ?>"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700">
                </div>

                <!-- Hari -->
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">
                        Hari <span class="text-gray-400">*</span>
                    </label>
                    <select id="hari" 
                            name="hari" 
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700 bg-white">
                        <option value="">Pilih Hari</option>
                        <?php foreach ($daftar_hari as $hari): ?>
                        <option value="<?= $hari ?>" <?= $jadwal['hari'] == $hari ? 'selected' : '' ?>>
                            <?= $hari ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jam Mulai dan Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Mulai <span class="text-gray-400">*</span>
                        </label>
                        <input type="time" 
                               id="jam_mulai" 
                               name="jam_mulai" 
                               value="<?= $jadwal['jam_mulai'] ?>"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Selesai <span class="text-gray-400">*</span>
                        </label>
                        <input type="time" 
                               id="jam_selesai" 
                               name="jam_selesai" 
                               value="<?= $jadwal['jam_selesai'] ?>"
                               required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700">
                    </div>
                </div>

                <!-- Ruangan -->
                <div>
                    <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-gray-400">*</span>
                    </label>
                    <input type="text" 
                           id="ruangan" 
                           name="ruangan" 
                           value="<?= htmlspecialchars($jadwal['ruangan']) ?>"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-gray-900 transition-colors duration-200 text-gray-700">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="index.php" class="px-6 py-3 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>