<?php
include 'db_connect.php';
$eid = isset($_GET['eid']) ? $_GET['eid'] : '';
if (!empty($eid)) {
    $stmt = $conn->prepare("SELECT ua.*, CONCAT(u.firstname, ' ', u.lastname) AS name, e.event_datetime, e.event, e.venue
                        FROM user_attendance ua 
                        LEFT JOIN events e ON ua.event_id = e.id 
                        LEFT JOIN users u ON ua.user_id = u.id 
                        WHERE u.id = ? AND ua.logout_time IS NOT NULL");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $attendees = $stmt->get_result();
}

?>
<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <b>Absen</b>
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
                        $users_query = $conn->query("SELECT id, CONCAT(firstname, ' ', lastname) AS fullname FROM users ORDER BY firstname ASC");
                        while ($row = $users_query->fetch_assoc()):
                            $user_id = $row['id'];
                            $user_fullname = ucwords($row['fullname']);
                            $user_attendance_url = "http://localhost/intern_plnsc/admin/index.php?page=attendance&eid=" . urlencode($user_id);
                            ?>
                            <option value="<?php echo $user_id ?>" data-cid="<?php echo $user_id ?>" <?php echo $eid == $user_id ? 'selected' : '' ?>>
                                <?php echo $user_fullname ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <hr>
            <?php if (empty($eid)): ?>
                <center> Silahkan Pilih Nama Terlebuh Dahulu.</center>
            <?php else: ?>
                <table class="table table-condensed table-bordered table-hover" id="att-records">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="">Nama</th>
                            <th class="">Jam Masuk</th>
                            <th class="">Jam Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($attendees->num_rows > 0):
                            while ($row = $attendees->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i++ ?>
                                    </td>
                                    <td class="">
                                        <?php echo ucwords($row['name']) ?>
                                    </td>
                                    <td class="">
                                        <?php echo $row['login_time'] ?>
                                    </td>
                                    <td class="">
                                        <?php echo $row['logout_time'] ?>
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
        <p><b>Acara:
                <?php echo isset($event) ? ucwords($event) : '' ?>
            </b></p>
        <p><b>Lokasi:
                <?php echo isset($venue) ? ucwords($venue) : '' ?>
            </b></p>
    </div>
</noscript>
<script>
    $(document).ready(function () {
        $('#eid').change(function () {
            var selectedUserId = $(this).val();
            var url = 'http://localhost/intern_plnsc/admin/index.php?page=attendance&eid=' + selectedUserId;
            location.href = url;
        });

        $('#print_record').click(function () {
            var _c = $('#att-records').clone();
            var ns = $('noscript').clone();
            ns.append(_c);

            // Add signature area below the table
            ns.append('<div style="padding-top:100px;margin-top: 20px;">'
                + '<label for="signature">Signature:</label>'
                + '___________________'
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
