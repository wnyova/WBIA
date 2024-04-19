<?php
// Include database connection
include 'db_connect.php';

if (isset($_POST['id'])) {
    // Get the updated values from the POST data
    $user_id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Update the user details in the database
    $update_query = "UPDATE users SET firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', contact = '$contact', email = '$email' WHERE id = $user_id";
    if ($conn->query($update_query) === TRUE) {
        echo 1; // Success
    } else {
        echo 0; // Error
    }
} else {
    echo 0; // Error
}
?>
