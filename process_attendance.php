<?php
session_start();
include('admin/db_connect.php');

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $action = $_POST['action'];

    // Mengambil nama depan pengguna berdasarkan user_id
    $user_query = "SELECT firstname FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($user_query);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $stmt_user->store_result();
    $stmt_user->bind_result($firstname);

    // Mengambil waktu server saat ini
    $current_time = date('Y-m-d H:i:s');

    // Memeriksa apakah pengguna ditemukan dan mendapatkan nama depannya
    if ($stmt_user->num_rows > 0 && $stmt_user->fetch()) {
        if ($action == 'attendance') {
            // Deklarasikan variabel $stmt di sini
            $stmt = null;

            if (isset($_POST['login_time'])) {
                // Menggunakan waktu server saat ini sebagai waktu masuk
                $login_time = $current_time;
                $insert_query = "INSERT INTO user_attendance (user_id, event_id, login_time, created_user, created_at) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iisss", $user_id, $event_id, $login_time, $firstname, $current_time);
            } elseif (isset($_POST['logout_time'])) {
                // Konversi waktu dari form ke waktu dengan zona waktu yang sesuai
                $logout_time = date('Y-m-d H:i:s', strtotime($_POST['logout_time']));
                $update_query = "UPDATE user_attendance SET logout_time = ?, created_user = ? WHERE user_id = ? AND event_id = ? AND DATE(login_time) = DATE(NOW())";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssii", $logout_time, $firstname, $user_id, $event_id);
            }

            // Pastikan $stmt sudah didefinisikan sebelum memanggil metode execute
            if ($stmt !== null && $stmt->execute()) {
                // Redirect to home page after successful submission
                header("Location: submit_masuk.php");
                exit();
            } elseif ($stmt !== null) {
                // If the query fails
                echo "Error: " . $stmt->error;
            }

            if ($stmt !== null) {
                $stmt->close();
            }
        }
    } else {
        // Jika pengguna tidak ditemukan
        echo "User not found.";
    }

    // Tutup statement yang digunakan untuk mengambil nama pengguna
    $stmt_user->close();

    $conn->close();
}
?>
