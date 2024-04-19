<?php
// Pastikan session telah dimulai
// session_start();
include('admin/db_connect.php');

// Periksa apakah user sudah masuk hari ini
$user_id = $_SESSION['login_id']; // Sesuaikan dengan nama variabel sesi Anda
$today = date('Y-m-d');
$check_attendance_query = "SELECT * FROM user_attendance WHERE user_id = ? AND DATE(login_time) = ?";
$stmt_check_attendance = $conn->prepare($check_attendance_query);
$stmt_check_attendance->bind_param("is", $user_id, $today);
$stmt_check_attendance->execute();
$result_check_attendance = $stmt_check_attendance->get_result();
$has_attendance_today = $result_check_attendance->num_rows > 0;

// Menonaktifkan tombol masuk jika sudah melakukan absensi hari ini
$disabled_attribute = $has_attendance_today ? 'disabled' : '';

$check_logout_query = "SELECT * FROM user_attendance WHERE user_id = ? AND DATE(logout_time) = ?";
$stmt_check_logout = $conn->prepare($check_logout_query);
$stmt_check_logout->bind_param("is", $user_id, $today);
$stmt_check_logout->execute();
$result_check_logout = $stmt_check_logout->get_result();
$has_logout_today = $result_check_logout->num_rows > 0;

// Menonaktifkan tombol pulang jika sudah melakukan absensi pulang hari ini
$disabled_logout_attribute = $has_logout_today ? 'disabled' : '';
?>

<div class="container mt-5">
  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="col-md-12 py-2">
            <div class="row">
              <div class="col-md-6 mb-3 mx-auto mt-2" style="padding-top:80px">
                <div class="card card-widget widget-user">
                  <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">
                      <h4>Absensi Intern</h4>
                    </h3>
                    <h5 class="widget-user-desc" id="current_datetime"></h5>
                  </div>
                  <div class="card-footer">
                    <div class="d-block py-1 px-1 w-100">
                      <p class="truncate">
                      <h4 style="justify-content:center;text-align:center;">Semangat berkerja insan Prima Layanan Niaga
                        Suku Cadang</h4>
                      </p>
                    </div>
                    <div class="mt-3">
                      <!-- Attendance Form -->
                      <form action="process_attendance.php" method="post" id="attendanceForm">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>">
                        <input type="hidden" name="event_id" value="1">
                        <!-- Misalnya, event_id diset ke 1 secara default -->
                        <input type="hidden" name="action" value="attendance">
                        <!-- Hapus input untuk waktu masuk -->
                        <div class="row">
                          <div class="col-md-6">
                            <!-- Tombol Masuk -->
                            <!-- <button type="button" class="btn btn-primary btn-block" id="masukButton">Masuk</button> -->
                            <button type="button" class="btn btn-primary btn-block" id="masukButton" <?php echo $disabled_attribute; ?>>Masuk</button>
                          </div>
                          <div class="col-md-6">
                            <!-- Tombol Pulang -->
                            <button type="button" class="btn btn-danger btn-block" id="pulangButton" <?php echo $disabled_logout_attribute; ?>>Pulang</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script untuk mengatur waktu dan validasi form -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Fungsi untuk memperbarui waktu saat halaman dimuat
    function updateCurrentTime() {
      var currentTimeElement = document.getElementById('current_datetime');
      var now = new Date();

      // Menentukan format tanggal dan waktu
      var options = {
        day: 'numeric',
        month: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        timeZoneName: 'short',
        timeZone: 'Asia/Jakarta'
      };

      // Membuat objek formatter dengan format yang diinginkan
      var formatter = new Intl.DateTimeFormat('id-ID', options);

      // Mendapatkan tanggal dan waktu dalam format yang diinginkan
      var formattedNow = formatter.format(now);

      currentTimeElement.textContent = formattedNow;
    }

    // Memanggil fungsi untuk memperbarui waktu saat halaman dimuat
    updateCurrentTime();

    // Mengatur interval untuk memperbarui waktu setiap detik (1000 milidetik)
    setInterval(updateCurrentTime, 1000);

    function disableMasukButton() {
      var today = new Date().toISOString().slice(0, 10);
      var hasAttendedToday = '<?php echo $has_attendance_today; ?>';
      if (hasAttendedToday && today === '<?php echo $today; ?>') {
        $('#masukButton').prop('disabled', true);
      }
    }

    disableMasukButton();

    function disablePulangButton() {
      var today = new Date().toISOString().slice(0, 10);
      var hasLoggedOutToday = '<?php echo $has_logout_today; ?>';
      if (hasLoggedOutToday && today === '<?php echo $today; ?>') {
        $('#pulangButton').prop('disabled', true);
      }
    }

    // Panggil fungsi disablePulangButton saat halaman dimuat
    disablePulangButton();

    // Tindakan saat tombol "Masuk" ditekan
    $('#masukButton').click(function () {
      // Mengambil waktu server saat ini
      var currentTimeISO = new Date().toISOString().slice(0, 19).replace('T', ' ');

      // Set waktu masuk ke waktu sekarang sebagai nilai input tersembunyi
      $('#attendanceForm').append('<input type="hidden" name="login_time" value="' + currentTimeISO + '">');

      // Kirim form absensi
      $('#attendanceForm').submit();
    });

    // Tindakan saat tombol "Pulang" ditekan
    $('#pulangButton').click(function () {
      var currentTime = new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
      // Set waktu pulang ke waktu sekarang sebagai nilai input tersembunyi
      $('#attendanceForm').append('<input type="hidden" name="logout_time" value="' + currentTime + '">');
      // Kirim form absensi
      $('#attendanceForm').submit();
    });
  });

  // Validasi form absensi sebelum disubmit
  $('#attendanceForm').submit(function (event) {
    var attendanceTime = $('#attendance_time').val();
    var currentTime = new Date().toISOString().slice(0, 16);
    if (attendanceTime > currentTime) {
      alert('Waktu absensi tidak bisa di masa depan.');
      event.preventDefault(); // Menghentikan submit form
    }
  });
</script>