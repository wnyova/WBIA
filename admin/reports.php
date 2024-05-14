<?php
setlocale(LC_TIME, 'id_ID.UTF-8');

include 'db_connect.php';
$eid = isset($_GET['eid']) ? $_GET['eid'] : '';
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

function getWorkdays($month, $year, $public_holidays) {
    $workdays = 0;
    $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($day = 1; $day <= $total_days; $day++) {
        $current_date = strtotime("$year-$month-$day");
        $current_day = date('N', $current_date);
        // Check if it's not Saturday (6) or Sunday (7) and not a public holiday
        if ($current_day >= 1 && $current_day <= 5 && !isset($public_holidays[$day])) {
            $workdays++;
        }
    }
    return $workdays;
}

// Calculate workdays for the selected month and year, excluding public holidays
$work_day = getWorkdays($selectedMonth, $selectedYear, $public_holidays);
?>

<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <b>Absensi</b>
            <div class="card-tools">
                <button class="btn btn-success btn-flat" type="button" id="print_record">
                    <i class="fa fa-print"></i> Cetak</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <label for="" class="mt-2">Nama</label>
                <div class="col-sm-4">
                    <select name="eid" id="eid" class="custom-select select2">
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
                <label for="month" class="mt-2 ml-3">Bulan:</label>
                <div class="col-sm-2">
                    <select name="month" id="month" class="custom-select select2">
                        <option value="">Semua Bulan</option>
                        <?php foreach (range(1, 12) as $monthNumber): ?>
                            <option value="<?php echo $monthNumber; ?>" <?php echo $selectedMonth == $monthNumber ? 'selected' : ''; ?>>
                                <?php echo strftime('%B', mktime(0, 0, 0, $monthNumber, 1)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <label for="year" class="mt-2 ml-3">Tahun:</label>
                <div class="col-sm-2">
                    <select name="year" id="year" class="custom-select select2">
                        <option value="">Semua Tahun</option>
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
                <center> Silahkan Pilih Nama Terlebih Dahulu.</center>
            <?php else: ?>
                <div class="card-body">
    <div class="table-responsive">
                 <table class="table table-condensed table-bordered table-hover" id="att-records">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Hari dan Tanggal</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Pulang</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Izin/Sakit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        // Loop through each date in the selected month and year
                        foreach ($attendance_records as $date => $records):
                            $login_date = date('Y-m-d', strtotime($date));
                            $login_day = strftime('%A', strtotime($login_date));
                            $login_datetime = $login_day . ' - ' . strftime('%d %B %Y', strtotime($login_date));
                            $day_of_week = date('N', strtotime($login_date)); // Get the day of the week (1 for Monday, 2 for Tuesday, etc.)
                            $row_class = ($day_of_week == 6 || $day_of_week == 7) ? 'weekend' : ''; // Check if it's Saturday (6) or Sunday (7)
                            
                            // Check if the "keterangan" column has a value corresponding to a public holiday
                            $day_of_month = date('j', strtotime($login_date));
                            if (isset($public_holidays[$day_of_month])) {
                                $row_class .= ' keterangan-filled'; // Add the class for rows with public holiday values in "keterangan" column
                            }
                        ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td class="text-center">
                                    <?php echo $i++ ?>
                                </td>
                                <td class="text-center">
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
            <?php endif; ?>
        </div>
    </div>
</div>


<noscript>
    <style>
        table#att-records {
            width: 80%;
            align-content: left;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
        }

        table#att-records td,
        table#att-records th {
            border: 1px solid;
            padding: 1px;
            font-size: 10px;
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

        .datadiri {
            padding-top: 2px 0;
            padding: 1px 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .datadiri p {
            margin-bottom: 0.01px;
        }

        .datadiri strong {
            display: inline-block;
            width: 120px; /* Adjust width as needed */
            font-weight: normal;
            text-align: left;
            margin-right: 1px;
        }

        .weekend {
            background-color: #a7b1e7; /* Grey background color for weekends */
        }

        tr.keterangan-filled {
            background-color: #a7b1e7; /* Light red background color */
        }
    </style>

    <!-- CSS for printing -->
    <style>
        @media print {
            table#att-records {
                margin-left: auto; /* Remove margins */
                margin-right: auto; /* Align table to the left */
            }
        }
    </style>

    <div class="headers">
        <img src="../logoplnsc2.png" alt="Logo">
        <h1>ABSENSI MAGANG
            <br><b id="periode"><?php echo strtoupper(strftime('%B %Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear))); ?></b></h1>
    </div>
    <div class="datadiri">
        <p><strong>Nama</strong>: <?php echo $user_fullname ?></p>
        <p><strong>Universitas</strong>: <?php echo isset($venue) ? ucwords($venue) : '' ?></p>
        <p><strong>Divisi</strong>: <?php echo isset($divisi) ? ucwords($divisi) : '' ?></p><br><br>
    </div>
</noscript>
<script>
    $(document).ready(function () {
    // Function to adjust column widths based on content
    function adjustColumnWidths() {
        $('#att-records').find('tr').each(function () {
            $(this).find('td').each(function (i) {
                var currentWidth = $(this).width();
                var maxWidth = 0;
                $(this).closest('table').find('tr').each(function () {
                    var cellWidth = $(this).find('td').eq(i).width();
                    maxWidth = Math.max(maxWidth, cellWidth);
                });
                $(this).css('width', maxWidth + 'px');
            });
        });
    }

    // Call the adjustColumnWidths function when the page loads
    adjustColumnWidths();

   $('#month').change(function () {
        var selectedMonth = $(this).val();
        var selectedMonthName = $('#month option:selected').text(); // Get the text of the selected option
        if (selectedMonth) {
            $('#periode').text('Periode ' + selectedMonthName); // Update the Periode text
            adjustColumnWidths(); // Adjust column widths when month changes
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

            // Create the new table element
            var newTable = $('<br><br><table style="width:25%; border: 1px solid black;"></table>');

            var totalweek = $('<tr></tr>');
            totalweek.append('<th style="text-align: left;">Total Hari Kerja : <?php echo $work_day; ?></th>');

            // Create the first row with cells for headings
            var newRow1 = $('<tr></tr>');
            newRow1.append('<th style="text-align: left;">Total Kehadiran : <?php echo $total_records; ?></th>');
            
            // Create the second row with sample data
            var newRow2 = $('<tr></tr>');
            newRow2.append('<td style="text-align: left;">Paraf Tim SDM : </td>');

            // Append rows to the new table
            newTable.append(totalweek);
            newTable.append(newRow1);
            newTable.append(newRow2);

            // Append the new table to the document
            ns.append(newTable);

            // Create the table element
            var table = $('<br><br><table style="width:100%; border: 1px solid black;"></table>');

            // Create the first row with cells for Intern and Mentor
            var row1 = $('<tr></tr>');
            row1.append('<td style="text-align: center;">Intern<br><br><br>___________________</td>');
            row1.append('<td style="text-align: center;">Mentor<br><br><br>___________________</td>');

            // Create the second row with cells for signatures
            var row2 = $('<tr></tr>');
            row2.append('<td style="text-align: center; padding-top: 1px;"><?php echo $user_fullname ?></td>');
            row2.append('<td style="text-align: center; padding-top: 1px;"></td>');

            // Append rows to the table
            table.append(row1);
            table.append(row2);

            // Append the table to the document
            ns.append(table);

            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write('<!DOCTYPE html><html><head><title>Print</title><link rel="stylesheet" type="text/css" href="print.css" media="print"></head><body>');
            nw.document.write(ns.html());
            nw.document.write('</body></html>');
            nw.document.close();
            setTimeout(() => {
                nw.print();
                nw.close();
            }, 1000);
        });

    });
</script>
