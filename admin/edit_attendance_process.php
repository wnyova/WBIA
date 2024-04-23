<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['user_id']) && isset($_POST['attendance_date']) && isset($_POST['login_time']) && isset($_POST['logout_time'])) {
        // Sanitize and validate input data
        $user_id = intval($_POST['user_id']);
        $attendance_date = $_POST['attendance_date'];

        // Extract date and time components from combined values
        $login_datetime = $_POST['attendance_date'] . ' ' . $_POST['login_time'];
        $logout_datetime = $_POST['attendance_date'] . ' ' . $_POST['logout_time'];

        // Check if the user exists in the database
        $check_user_query = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $check_user_query->bind_param("i", $user_id);
        $check_user_query->execute();
        $user_result = $check_user_query->get_result();

        if ($user_result->num_rows > 0) {
            // Update the attendance record in the database
            $update_query = $conn->prepare("UPDATE user_attendance SET login_time = ?, logout_time = ? WHERE user_id = ? AND DATE(login_time) = ?");
            $update_query->bind_param("ssis", $login_datetime, $logout_datetime, $user_id, $attendance_date);

            if ($update_query->execute()) {
                // Attendance record updated successfully
                echo "Attendance record updated successfully.";
            } else {
                // Error updating attendance record
                echo "Error updating attendance record. Please try again.";
            }
        } else {
            // User does not exist in the database
            echo "User does not exist.";
        }
    } else {
        // Required fields not set
        echo "All fields are required.";
    }
} else {
    // Form not submitted via POST request
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
