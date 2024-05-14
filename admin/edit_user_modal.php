<?php
// Include database connection
include 'db_connect.php';

// Check if user ID is provided
if (isset($_POST['id'])) {
    $user_id = $_POST['id'];
    
    // Fetch user details from the database
    $qry = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $row = $qry->fetch_assoc();

    // Display user details in the form
    if ($row) {
        ?>
        <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="edit_user_form">
                <div class="form-group">
                    <label for="edit_firstname">Nama Depan</label>
                    <input type="text" class="form-control" id="edit_firstname" name="edit_firstname" value="<?php echo $row['firstname']; ?>">
                </div>
                <div class="form-group">
                    <label for="edit_middlename">Nama Tengah</label>
                    <input type="text" class="form-control" id="edit_middlename" name="edit_middlename" value="<?php echo $row['middlename']; ?>">
                </div>
                <div class="form-group">
                    <label for="edit_lastname">Nama Belakang</label>
                    <input type="text" class="form-control" id="edit_lastname" name="edit_lastname" value="<?php echo $row['lastname']; ?>">
                </div>
                <div class="form-group">
                    <label for="edit_contact">Nomor Kontak</label>
                    <input type="text" class="form-control" id="edit_contact" name="edit_contact" value="<?php echo $row['contact']; ?>">
                </div>
                <!-- Hide the role field -->
                <div class="form-group" style="display: none;">
                    <label for="edit_role">Role</label>
                    <input type="text" class="form-control" id="edit_role" name="edit_role" value="<?php echo $type[$row['type']]; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" class="form-control" id="edit_email" name="edit_email" value="<?php echo $row['email']; ?>">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="save_user_changes(<?php echo $user_id; ?>)">Simpan Perubahan</button>
        </div>
        <?php
    } else {
        echo "User not found.";
    }
}
?>
