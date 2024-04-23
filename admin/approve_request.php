<?php
// Include db_connect.php file to establish database connection
include 'db_connect.php';

// Check if request_id is set and not empty
if(isset($_GET['request_id']) && !empty($_GET['request_id'])) {
    // Sanitize the input to prevent SQL injection
    $request_id = mysqli_real_escape_string($conn, $_GET['request_id']);

    // Update request status to 'Approved'
    $sql = "UPDATE izin_sakit_requests SET request_status = 'Approved' WHERE id = $request_id";

    if ($conn->query($sql) === TRUE) {
        echo "Request status updated successfully.";
    } else {
        echo "Error updating request status: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close database connection
$conn->close();
?>
