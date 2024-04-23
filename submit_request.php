<?php
// Start session to access session variables
session_start();

// Include the database connection file
include 'admin/db_connect.php';

// Check if the user is logged in
if(isset($_SESSION['login_id'])){
    // Get the user ID from the session
    $user_id = $_SESSION['login_id'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if all required fields are set
        if (isset($_POST['izin_sakit_type']) && isset($_POST['request_date']) && isset($_POST['request_comment'])) {
            // Sanitize and validate input data
            $izin_sakit_type = $_POST['izin_sakit_type'];
            $request_date = $_POST['request_date'];
            $request_comment = $_POST['request_comment'];

            // Insert the Izin/Sakit request into the database
            $insert_query = $conn->prepare("INSERT INTO izin_sakit_requests (user_id, izin_sakit_type, request_date, request_comment) VALUES (?, ?, ?, ?)");
            $insert_query->bind_param("isss", $user_id, $izin_sakit_type, $request_date, $request_comment);

            if ($insert_query->execute()) {
                // Request submitted successfully
                echo '<script>alert("Izin/Sakit request submitted successfully."); window.location.href = "index.php?page=permit_status";</script>';
            } else {
                // Error submitting request
                echo "Error submitting Izin/Sakit request. Please try again.";
            }
        } else {
            // Required fields not set
            echo "All fields are required.";
        }
    } else {
        // Form not submitted via POST request
        echo "Invalid request method.";
    }
} else {
    // User is not logged in
    echo "You are not logged in.";
}

// Close the database connection
$conn->close();
?>
