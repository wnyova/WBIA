<!DOCTYPE html>
<html>
<head>
    <br>
    <title>Daftar Izin</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            overflow-x: auto; /* Enable horizontal scrolling on small screens */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            white-space: nowrap; /* Prevent line breaks */
        }
        th {
            background-color: #f2f2f2;
        }
        .approve-btn {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Perizinan</h2>
    <div style="overflow-x: auto;">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal Perizinan</th>
                <th>Tipe Perizinan</th>
                <th>Keterangan Perizinan</th>
                <th>Bukti</th>
                <th>Status</th>
            </tr>
            <?php
            // Include db_connect.php file to establish database connection
            include 'admin/db_connect.php';

            // Start session to access session variables
            session_start();

            // Check if the user is logged in
            if(isset($_SESSION['login_id'])){
                // Get the user ID from the session
                $user_id = $_SESSION['login_id'];

                // Fetch requests from the database with user details
                $sql = "SELECT izin_sakit_requests.id, CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) AS user_name, 
                        izin_sakit_requests.request_date, izin_sakit_requests.izin_sakit_type, izin_sakit_requests.request_comment, izin_sakit_requests.request_proof, izin_sakit_requests.request_status
                        FROM izin_sakit_requests 
                        INNER JOIN users ON izin_sakit_requests.user_id = users.id
                        WHERE izin_sakit_requests.request_status IN ('Pending', 'Approved') AND izin_sakit_requests.user_id = $user_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        // Modify request type
                        $request_type = $row["izin_sakit_type"];
                        if ($request_type == "Izin") {
                            $request_type = "Permit";
                        } elseif ($request_type == "Sakit") {
                            $request_type = "Sick";
                        }

                        echo "<tr>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["user_name"]."</td>";
                        echo "<td>".$row["request_date"]."</td>";
                        echo "<td>".$request_type."</td>"; // Display modified request type
                        echo "<td>".$row["request_comment"]."</td>";
                        echo "<td><a href='".htmlspecialchars($row["request_proof"], ENT_QUOTES, 'UTF-8')."' target='_blank'>Click Here</a></td>"; // Display proof as hyperlink
                        echo "<td>".$row["request_status"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No requests</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>User not logged in</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
