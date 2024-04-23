<?php
include 'admin/db_connect.php';
// Start session to access session variables
session_start();

// Get the logged-in user's ID from the session
$login_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : '';

$eid = isset($_GET['eid']) ? $_GET['eid'] : $login_id;
$user_fullname = ''; // Initialize variables
$venue = ''; // Initialize variables
$divisi = '';
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // Get the current month if not selected
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y'); // Get the current year if not selected

if (!empty($eid)) {
    // Fetch user details
    $user_query = $conn->prepare("SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname, nama_kampus, divisi FROM users WHERE id = ?");
    $user_query->bind_param("i", $eid);
    $user_query->execute();
    $user_result = $user_query->get_result();

    // If user details found, set the variables
    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_fullname = ucwords($user_row['fullname']);
        $venue = $user_row['nama_kampus'];
        $divisi = $user_row['divisi'];
    }
}

// Initialize an empty array to hold attendance records
$attendance_records = [];

if (!empty($eid)) {
    // Get the first and last date of the selected month and year
    $start_date = date('Y-m-01', strtotime(date("Y-m-d", mktime(0, 0, 0, $selectedMonth, 1, $selectedYear))));
    $end_date = date('Y-m-t', strtotime(date("Y-m-d", mktime(0, 0, 0, $selectedMonth, 1, $selectedYear))));

    // Loop through each date in the selected month and year
    $current_date = $start_date;
    while ($current_date <= $end_date) {
        // Prepare the query to fetch attendance records for the current date
        $stmt = $conn->prepare("SELECT ua.*, CONCAT(u.firstname, ' ', u.lastname) AS name, e.event_datetime, e.event, e.venue,
                                DATE_FORMAT(ua.login_time, '%H:%i:%s') AS login_time_formatted,
                                DATE_FORMAT(ua.logout_time, '%H:%i:%s') AS logout_time_formatted
                            FROM user_attendance ua 
                            LEFT JOIN events e ON ua.event_id = e.id 
                            LEFT JOIN users u ON ua.user_id = u.id 
                            WHERE u.id = ? 
                                AND DATE(ua.login_time) = ?");
        $stmt->bind_param("is", $eid, $current_date);

        $stmt->execute();
        $attendance_records[$current_date] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Move to the next date
        $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
    }
    $total_records = 0;
    foreach ($attendance_records as $date => $records) {
        if (!empty($records)) {
            $total_records++;
        }
    }
}

// Fetch public holidays for the selected month and year
$public_holidays_query = $conn->prepare("SELECT DAY(holiday_date) AS day, holiday_name FROM public_holidays WHERE MONTH(holiday_date) = ? AND YEAR(holiday_date) = ?");
$public_holidays_query->bind_param("ii", $selectedMonth, $selectedYear);
$public_holidays_query->execute();
$public_holidays_result = $public_holidays_query->get_result();
$public_holidays = array();
while ($row = $public_holidays_result->fetch_assoc()) {
    $public_holidays[$row['day']] = $row['holiday_name'];
}
?>
<br>
<br>
<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <b>Attendance</b>
            <div class="card-tools">
                <button class="btn btn-success btn-flat" type="button" id="print_record">
                    <i class="fa fa-print"></i> Print</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <label for="" class="mt-2">Name</label>
                <div class="col-sm-4">
                    <select name="eid" id="eid" class="custom-select select2" disabled>
                        <option value=""></option>
                        <?php
                        // Fetch users from database and populate the select dropdown
                        $users_query = $conn->query("SELECT id, CONCAT(firstname,' ', middlename,' ', lastname) AS fullname FROM users ORDER BY firstname ASC");
                        while ($row = $users_query->fetch_assoc()):
                            $user_id = $row['id'];
                            $option_fullname = ucwords($row['fullname']);
                            $user_attendance_url = "./index.php?page=reports&eid=" . urlencode($user_id);
                            ?>
                            <option value="<?php echo $user_id ?>" <?php echo $eid == $user_id ? 'selected' : '' ?>>
                                <?php echo $option_fullname ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <label for="month" class="mt-2 ml-3">Select Month:</label>
                <div class="col-sm-2">
                    <select name="month" id="month" class="custom-select select2">
                        <option value="">All Months</option>
                        <?php foreach (range(1, 12) as $monthNumber): ?>
                            <option value="<?php echo $monthNumber; ?>" <?php echo $selectedMonth == $monthNumber ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $monthNumber, 1)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <label for="year" class="mt-2 ml-3">Select Year:</label>
                <div class="col-sm-2">
                    <select name="year" id="year" class="custom-select select2">
                        <option value="">All Years</option>
                        <?php
                        $currentYear = date("Y");
                        for ($i = $currentYear; $i >= 2024; $i--) {
                            echo "<option value='$i' " . ($selectedYear == $i ? 'selected' : '') . ">$i</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <hr>
            <?php if (empty($eid)): ?>
                <center> Please select Name First.</center>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-hover" id="att-records">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Date and Day</th>
                                <th class="text-center">In</th>
                                <th class="text-center">Out</th>
                                <th class="text-center">Information</th>
                                <th class="text-center">Permit/Sick</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            // Loop through each date in the selected month and year
                            foreach ($attendance_records as $date => $records):
                                $login_date = date('Y-m-d', strtotime($date));
                                $login_day = date('l', strtotime($login_date));
                                $login_datetime = $login_day . ' - ' . date('d F Y', strtotime($login_date));
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i++ ?>
                                    </td>
                                    <td class="">
                                        <?php echo $login_datetime ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo !empty($records) ? $records[0]['login_time_formatted'] : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo !empty($records) ? $records[0]['logout_time_formatted'] : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $day_of_month = date('j', strtotime($login_date));
                                        echo isset($public_holidays[$day_of_month]) ? $public_holidays[$day_of_month] : '-';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        // Check if the request is approved for this date
                                        $approval_query = $conn->prepare("SELECT request_status FROM izin_sakit_requests WHERE user_id = ? AND request_date = ?");
                                        $approval_query->bind_param("is", $eid, $login_date);
                                        $approval_query->execute();
                                        $approval_result = $approval_query->get_result();
                                        if ($approval_result->num_rows > 0) {
                                            $row = $approval_result->fetch_assoc();
                                            if ($row['request_status'] === 'Approved') {
                                                echo '<span>&#10004;</span>'; // Checkmark symbol
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<noscript>
    <style>
        table#att-records {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
        }

        table#att-records td,
        table#att-records th {
            border: 1px solid;
            padding: 8px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .text-center {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .headers {
            text-align: center;
            margin-bottom: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .headers img {
            max-width: 120px;
            /* Adjust the width of the logo as needed */
            max-height: 100px;
            /* Adjust the height of the logo as needed */
            float: left;
            /* Align the logo to the left */
            margin-right: 20px;
            /* Add some spacing between the logo and the title */
        }

        .headers h1 {
            display: inline-block;
            /* Ensure the title is on the same line as the logo */
            margin-right: 50px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
        }

        .headers h1 b {
            display: inline-block;
            /* Ensure the title is on the same line as the logo */
            margin-right: 5px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
        }

        .info {
            font-family: Arial, Helvetica, sans-serif;
        }

    </style>
    <div class="headers">
        <img src="logoplnsc2.png" alt="Logo">
        <h1>Internship Attendance
        <br><b id="periode"><?php echo date('F Y', mktime(0, 0, 0, $selectedMonth, 1)); ?></b></h1>
    </div>
    <div class="info">
        <p>Student Name&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $user_fullname ?></p>
        <p>University&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo isset($venue) ? ucwords($venue) : '' ?></p>
        <p>Division&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo isset($divisi) ? ucwords($divisi) : '' ?></p>
        <p>Total Attendance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $total_records; ?></p>
    </div>
</noscript>
<script>
    $(document).ready(function () {
        // Update Periode text when month dropdown changes
        $('#month').change(function () {
            var selectedMonth = $(this).val();
            var selectedMonthName = $('#month option:selected').text(); // Get the text of the selected option
            if (selectedMonth) {
                $('#periode').text('Periode ' + selectedMonthName); // Update the Periode text
            }
        });

        $('#eid, #month, #year').change(function () {
            var selectedUserId = $('#eid').val();
            var selectedMonth = $('#month').val();
            var selectedYear = $('#year').val();
            var url = './index.php?page=reports';
            if (selectedUserId) {
                url += '&eid=' + selectedUserId;
            }
            if (selectedMonth) {
                url += '&month=' + selectedMonth;
            }
            if (selectedYear) {
                url += '&year=' + selectedYear;
            }
            location.href = url;
        });

        $('#print_record').click(function () {
            var _c = $('#att-records').clone();
            var ns = $('noscript').clone();
            ns.append(_c);

            // Add signature area side by side
            ns.append('<div style="display: flex; justify-content: space-between; padding-top:100px; margin-top: 15px;">' +
                '<div>' +
                '<label for="signature">Intern<br><br><br><br><br></label>' +
                '___________________' +
                '</div>' +
                '<div>' +
                '<label for="signature">Mentor<br><br><br><br><br></label>' +
                '___________________' +
                '</div>' +
                '</div>');

            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write(ns.html());
            nw.document.close();
            setTimeout(() => {
                nw.print();
                nw.close();
            }, 1000);

        });
    });
</script>
