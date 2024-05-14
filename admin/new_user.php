<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $nama_kampus = $_POST['nama_kampus'];
    $divisi = $_POST['divisi'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encrypt the password using MD5
    $type = $_POST['type'];

    // Avatar upload handling
    $target_dir = "../assets/uploads/";
    $avatar = $target_dir . basename($_FILES["avatar"]["name"]);
    $avatar_tmp = $_FILES["avatar"]["tmp_name"];
    $avatar_name = basename($_FILES["avatar"]["name"]); // Get only the filename with extension

    // Move uploaded file to target directory
    if (move_uploaded_file($avatar_tmp, $avatar)) {
        // Insert data into the database
        $query = "INSERT INTO users (firstname, middlename, lastname, nama_kampus, divisi, contact, address, email, password, type, avatar, date_created) 
                  VALUES ('$firstname', '$middlename', '$lastname', '$nama_kampus', '$divisi', '$contact', '$address', '$email', '$password', '$type', '$avatar_name', CURRENT_TIMESTAMP)";

        if ($conn->query($query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Add your custom CSS styles here */
    </style>
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function () {
            $('#manage_user').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var form = $(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (response) {
                        // Show success popup
                        $('#successModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });

            // Redirect to view_user.php when "View User" button is clicked
            $('#viewUserBtn').click(function () {
                window.location.href = 'index.php?page=user_list';
            });

            // Reload the page when "Submit Another" button is clicked
            $('#submitAnotherBtn').click(function () {
                location.reload();
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Tambahkan Pengguna Baru</h2>
        <form action="" id="manage_user" enctype="multipart/form-data" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <!-- First Column -->
                    <div class="form-group">
                        <label for="firstname">Nama Depan</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="middlename">Nama Tengah</label>
                        <input type="text" name="middlename" id="middlename" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Nama Belakang</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_kampus">Nama Kampus</label>
                        <input type="text" name="nama_kampus" id="nama_kampus" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="divisi">Divisi</label>
                        <select name="divisi" id="divisi" class="form-control" required>
                            <option value="Keuangan Akuntansi dan SDM">Keuangan Akuntansi dan SDM</option>
                            <option value="Perencanaan dan Pengembangan Bisnis">Perencanaan dan Pengembangan Bisnis</option>
                            <option value="Marketing Technical Services 1">Marketing Technical Services 1</option>
                            <option value="Marketing Technical Services 2">Marketing Technical Services 2</option>
                            <option value="Procurement 1">Procurement 1</option>
                            <option value="Procurement 2">Procurement 2</option>
                            <option value="Sekretariat Perusahaan">Sekretariat Perusahaan</option>
                            <option value="Logistik">Logistik</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact">Nomor Kontak</label>
                        <input type="text" name="contact" id="contact" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Second Column -->
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" cols="30" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avatar</label>
                        <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="type">Role Pengguna</label>
                        <select name="type" id="type" class="form-control">
                            <option value="2">Registrar</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="checkmark-circle">
                        <div class="background"></div>
                        <div class="checkmark draw"></div>
                    </div>
                    <p class="mt-3">Form submitted successfully!</p>
                    <!-- Buttons in the success modal -->
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" id="viewUserBtn" class="btn btn-secondary mr-2">View User</button>
                        <button type="button" id="submitAnotherBtn" class="btn btn-secondary">Submit Another</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>