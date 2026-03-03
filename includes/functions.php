<?php
// Fungsi untuk membersihkan input
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk mendapatkan urutan hari berdasarkan hari ini
function getOrderedDays() {
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    
    // Dapatkan hari ini dalam bahasa Indonesia
    $hari_ini = date('l');
    $map_hari = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];
    
    $today_indonesia = $map_hari[$hari_ini];
    
    // Cari index hari ini
    $today_index = array_search($today_indonesia, $days);
    
    // Urutkan ulang array mulai dari hari ini
    $ordered_days = array_merge(
        array_slice($days, $today_index),
        array_slice($days, 0, $today_index)
    );
    
    return $ordered_days;
}

// Fungsi untuk mendapatkan semua jadwal dengan urutan dinamis
function getAllJadwal($conn) {
    $ordered_days = getOrderedDays();
    
    // Buat string untuk CASE statement
    $case_statement = "CASE hari ";
    foreach ($ordered_days as $index => $day) {
        $case_statement .= "WHEN '$day' THEN $index ";
    }
    $case_statement .= "END";
    
    $query = "SELECT * FROM jadwal 
              ORDER BY 
              $case_statement,
              jam_mulai ASC";
    
    $result = mysqli_query($conn, $query);
    $jadwal = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $jadwal[] = $row;
    }
    
    return $jadwal;
}

// Fungsi untuk mengelompokkan jadwal berdasarkan hari
function getJadwalByDay($conn) {
    $jadwal = getAllJadwal($conn);
    $jadwal_by_day = [];
    
    foreach ($jadwal as $row) {
        $hari = $row['hari'];
        if (!isset($jadwal_by_day[$hari])) {
            $jadwal_by_day[$hari] = [];
        }
        $jadwal_by_day[$hari][] = $row;
    }
    
    return $jadwal_by_day;
}

// Fungsi untuk mendapatkan jadwal berdasarkan ID
function getJadwalById($conn, $id) {
    $query = "SELECT * FROM jadwal WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menambah jadwal
function tambahJadwal($conn, $data) {
    $nama_kuliah = cleanInput($data['nama_kuliah']);
    $kelas = cleanInput($data['kelas']);
    $hari = cleanInput($data['hari']);
    $jam_mulai = cleanInput($data['jam_mulai']);
    $jam_selesai = cleanInput($data['jam_selesai']);
    $ruangan = cleanInput($data['ruangan']);
    
    $query = "INSERT INTO jadwal (nama_kuliah, kelas, hari, jam_mulai, jam_selesai, ruangan) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $nama_kuliah, $kelas, $hari, $jam_mulai, $jam_selesai, $ruangan);
    
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk mengupdate jadwal
function updateJadwal($conn, $id, $data) {
    $nama_kuliah = cleanInput($data['nama_kuliah']);
    $kelas = cleanInput($data['kelas']);
    $hari = cleanInput($data['hari']);
    $jam_mulai = cleanInput($data['jam_mulai']);
    $jam_selesai = cleanInput($data['jam_selesai']);
    $ruangan = cleanInput($data['ruangan']);
    
    $query = "UPDATE jadwal SET 
              nama_kuliah = ?, 
              kelas = ?, 
              hari = ?, 
              jam_mulai = ?, 
              jam_selesai = ?, 
              ruangan = ? 
              WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $nama_kuliah, $kelas, $hari, $jam_mulai, $jam_selesai, $ruangan, $id);
    
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk menghapus jadwal
function hapusJadwal($conn, $id) {
    $query = "DELETE FROM jadwal WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk format jam
function formatJam($jam) {
    return date('H:i', strtotime($jam));
}

// Fungsi untuk mendapatkan hari ini dalam bahasa Indonesia
function getHariIni() {
    $hari_ini = date('l');
    $map_hari = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];
    
    return $map_hari[$hari_ini];
}

// Daftar hari
$daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

// Fungsi untuk menghitung durasi dalam jam
function hitungDurasi($jam_mulai, $jam_selesai) {
    $mulai = strtotime($jam_mulai);
    $selesai = strtotime($jam_selesai);
    $durasi = ($selesai - $mulai) / 3600; // Konversi ke jam
    
    if ($durasi == 1) {
        return '1 jam';
    } elseif ($durasi < 1) {
        $menit = $durasi * 60;
        return round($menit) . ' menit';
    } else {
        return $durasi . ' jam';
    }
}

?>