<?php
include 'db_connect.php';
$eid = isset($_GET['eid']) ? $_GET['eid'] : '';
$user_fullname = ''; // Inisialisasi variabel
$venue = ''; // Inisialisasi variabel
$divisi = '';

if (!empty($eid)) {
    $stmt = $conn->prepare("SELECT ua.*, CONCAT(u.firstname, ' ', u.lastname) AS name, e.event_datetime, e.event, e.venue,
                            DATE_FORMAT(ua.login_time, '%H:%i:%s') AS login_time_formatted,
                            DATE_FORMAT(ua.logout_time, '%H:%i:%s') AS logout_time_formatted
                        FROM user_attendance ua 
                        LEFT JOIN events e ON ua.event_id = e.id 
                        LEFT JOIN users u ON ua.user_id = u.id 
                        WHERE u.id = ?");

    // WHERE u.id = ? AND ua.logout_time IS NOT NULL");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $attendees = $stmt->get_result();

    // Mendapatkan nama karyawan berdasarkan $eid
    $user_query = $conn->prepare("SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname, nama_kampus, divisi FROM users WHERE id = ?");
    $user_query->bind_param("i", $eid);
    $user_query->execute();
    $user_result = $user_query->get_result();

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_fullname = ucwords($user_row['fullname']);
        $venue = $user_row['nama_kampus']; // Mendapatkan nama_kampus
        $divisi = $user_row['divisi'];
    }
}

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
                        $users_query = $conn->query("SELECT id, CONCAT(firstname,' ', middlename,' ', lastname) AS fullname FROM users ORDER BY firstname ASC");
                        while ($row = $users_query->fetch_assoc()):
                            $user_id = $row['id'];
                            $option_fullname = ucwords($row['fullname']); // Gunakan variabel yang berbeda untuk opsi nama
                            $user_attendance_url = "http://10.7.82.97/intern_plnsc/admin/index.php?page=attendance&eid=" . urlencode($user_id);
                            ?>
                            <option value="<?php echo $user_id ?>" data-cid="<?php echo $user_id ?>" <?php echo $eid == $user_id ? 'selected' : '' ?>>
                                <?php echo $option_fullname ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <hr>
            <?php if (empty($eid)): ?>
                <center> Silahkan Pilih Nama Terlebih Dahulu.</center>
            <?php else: ?>
                <table class="table table-condensed table-bordered table-hover" id="att-records">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal dan Hari</th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Jam Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($attendees->num_rows > 0):
                            while ($row = $attendees->fetch_assoc()):
                                // Mengambil nama lengkap dari pengguna
                                $fullname = ucwords($row['name']);

                                // Mendapatkan tanggal dari login_time
                                $login_date = date('Y-m-d', strtotime($row['login_time']));

                                // Mendapatkan nama hari dari login_date
                                $login_day = date('l', strtotime($login_date));

                                // Menggabungkan tanggal dan hari
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
                                        <?php echo $row['login_time_formatted'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $row['logout_time_formatted'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <th colspan="4">
                                    <center>No Records.</center>
                                </th>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<noscript>
    <style>
        table#att-records {
            width: 100%;
            border-collapse: collapse
        }

        table#att-records td,
        table#att-records th {
            border: 1px solid
        }

        .text-center {
            text-align: center
        }
    </style>
    <div>
        <p style="display: flex; justify-content: center;"><b>PT. PRIMA LAYANAN NIAGA SUKU CADANG (PLNSC)</b></p>
                <p>Nama Mahasiswa :
                    <?php echo $user_fullname ?>
                </p>Asal Kampus : <?php echo isset($venue) ? ucwords($venue) : '' ?></p>
                        <p>Bidang/Divisi : <?php echo isset($divisi) ? ucwords($divisi) : '' ?></p>
    </div>
</noscript>
<script>
    $(document).ready(function () {
        $('#eid').change(function () {
            var selectedUserId = $(this).val();
            var url = 'http://10.7.82.97/intern_plnsc/admin/index.php?page=attendance&eid=' + selectedUserId;
            location.href = url;
        });

        // $('#print_record').click(function () {
        //     var _c = $('#att-records').clone();
        //     var ns = $('noscript').clone();
        //     ns.append(_c);

        //     // Add signature area below the table
        //     ns.append('<div style="padding-top:100px;margin-top: 20px;">'
        //         + '<label for="signature">Signature:</label>'
        //         + '___________________'
        //         + '</div>');
        //     ns.append('<div style="padding-top:100px;margin-top: 20px;">'
        //         + '<label for="signature">Signature:</label>'
        //         + '___________________'
        //         + '</div>');

        //     var nw = window.open('', '_blank', 'width=900,height=600');
        //     nw.document.write(ns.html());
        //     nw.document.close();
        //     nw.print();
        //     setTimeout(() => {
        //         nw.close();
        //     }, 500);
        // });
        $('#print_record').click(function () {
            var _c = $('#att-records').clone();
            var ns = $('noscript').clone();
            ns.append(_c);

            // Add signature area side by side
            ns.append('<div style="display: flex; justify-content: space-between; padding-top:100px; margin-top: 15px;">'
                + '<div>'
                + '<label for="signature">Signature:</label>'
                + '___________________'
                + '</div>'
                + '<div>'
                + '<label for="signature">Signature:</label>'
                + '___________________'
                + '</div>'
                + '</div>');

            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write(ns.html());
            nw.document.close();
            nw.print();
            setTimeout(() => {
                nw.close();
            }, 500);
        });
    });
</script>