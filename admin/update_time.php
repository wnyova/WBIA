<?php
// update_time.php

// Include database connection
include 'db_connect.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data sent via AJAX
    $userId = $_POST['userId'];
    $date = $_POST['date'];
    $loginTime = $_POST['loginTime'];
    $logoutTime = $_POST['logoutTime'];

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE user_attendance SET login_time = ?, logout_time = ? WHERE user_id = ? AND DATE(login_time) = ?");
    $stmt->bind_param("ssis", $loginTime, $logoutTime, $userId, $date);

    // Execute the query
    if ($stmt->execute()) {
        // If the query executes successfully, return a success message
        echo json_encode(['status' => 'success', 'message' => 'Time updated successfully']);
    } else {
        // If there's an error, return an error message
        echo json_encode(['status' => 'error', 'message' => 'Failed to update time: ' . $conn->error]);
    }
} else {
    // If the request method is not POST, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
