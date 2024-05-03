<!DOCTYPE html>
<html lang="en">
<?php
include 'db_connect.php';

// Fetch users from database and populate the select dropdown
$users_query = $conn->query("SELECT id, CONCAT(firstname,' ', middlename,' ', lastname) AS fullname FROM users ORDER BY firstname ASC");
$users = [];
while ($row = $users_query->fetch_assoc()) {
    $users[$row['id']] = ucwords($row['fullname']);
}

?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Tambahkan Absen</title>

    <?php include('./header.php'); ?>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <b>Tambahkan Absen</b>
                    </div>
                    <div class="card-body">
                        <form id="edit-attendance-form">
                            <div class="form-group">
                                <label for="user_id">Pilih Pengguna:</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Pilih Pengguna</option>
                                    <?php foreach ($users as $user_id => $fullname): ?>
                                        <option value="<?php echo $user_id; ?>"><?php echo $fullname; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="attendance_date">Pilih Tanggal:</label>
                                <input type="date" id="attendance_date" name="attendance_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="login_time">Jam Masuk:</label>
                                <input type="time" id="login_time" name="login_time" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="logout_time">Jam Pulang:</label>
                                <input type="time" id="logout_time" name="logout_time" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#edit-attendance-form').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: 'edit_attendance_process.php',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        alert(response);
                        // You can handle the response here, maybe redirect the user to another page or show a success message
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr);
                        alert('An error occurred while processing your request. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
