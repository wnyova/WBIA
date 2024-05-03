<!DOCTYPE html>
<html>
<head>
    <title>Pending Requests</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
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
    <h2>Pending Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Request Date</th>
            <th>Request Type</th>
            <th>Request Comment</th>
            <th>Proof</th>
            <th>Action</th>
        </tr>
        <?php
        // Include db_connect.php file to establish database connection
        include 'db_connect.php';

        // Fetch pending requests from the database with user details
        $sql = "SELECT izin_sakit_requests.id, CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) AS user_name, 
                izin_sakit_requests.request_date, izin_sakit_requests.izin_sakit_type, izin_sakit_requests.request_comment, izin_sakit_requests.request_proof 
                FROM izin_sakit_requests 
                INNER JOIN users ON izin_sakit_requests.user_id = users.id
                WHERE izin_sakit_requests.request_status = 'Pending'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["user_name"]."</td>";
                echo "<td>".$row["request_date"]."</td>";
                echo "<td>".$row["izin_sakit_type"]."</td>";
                echo "<td>".$row["request_comment"]."</td>";
                echo "<td><a href='".$row["request_proof"]."' target='_blank'>Click Here</a></td>";
                echo "<td><button class='approve-btn' onclick='approveRequest(".$row["id"].")'>Approve</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No pending requests</td></tr>";
        }
        ?>
    </table>

    <script>
        function approveRequest(requestId) {
            if (confirm('Are you sure you want to approve this request?')) {
                // Send AJAX request to approve the request
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page after successful approval
                        location.reload();
                    }
                };
                xhttp.open("GET", "approve_request.php?request_id=" + requestId, true);
                xhttp.send();
            }
        }
    </script>
</body>
</html>
