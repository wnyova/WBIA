<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_user"><i class="fa fa-plus"></i> Add New User</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Contact #</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $type = array('', "Admin", "Registrar");
                    $qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM users order by concat(lastname,', ',firstname,' ',middlename) asc");
                    while ($row = $qry->fetch_assoc()) :
                    ?>
                        <tr>
                            <th class="text-center"><?php echo $i++ ?></th>
                            <td><b><?php echo ucwords($row['name']) ?></b></td>
                            <td><b><?php echo $row['contact'] ?></b></td>
                            <td><b><?php echo $type[$row['type']] ?></b></td>
                            <td><b><?php echo $row['email'] ?></b></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Action
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a class="dropdown-item view_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item edit_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for editing user details -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Content for editing user details will be loaded here -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#list').dataTable();

        $('.view_user').click(function() {
            uni_modal("<i class='fa fa-id-card'></i> User Details", "view_user.php?id=" + $(this).attr('data-id'));
        });

        $('.edit_user').click(function() {
            var userId = $(this).attr('data-id');
            $.ajax({
                url: 'edit_user_modal.php',
                method: 'POST',
                data: {
                    id: userId
                },
                success: function(response) {
                    $('#editUserModal .modal-content').html(response);
                    $('#editUserModal').modal('show');
                }
            });
        });

        $('.delete_user').click(function() {
            _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')]);
        });
    });

    function save_user_changes(user_id) {
        var firstname = $('#edit_firstname').val();
        var middlename = $('#edit_middlename').val();
        var lastname = $('#edit_lastname').val();
        var contact = $('#edit_contact').val();
        var email = $('#edit_email').val();

        $.ajax({
            url: 'save_user_changes.php',
            method: 'POST',
            data: {
                id: user_id,
                firstname: firstname,
                middlename: middlename,
                lastname: lastname,
                contact: contact,
                email: email
            },
            success: function(response) {
                if (response == 1) {
                    alert('Changes saved successfully');
                    location.reload();
                } else {
                    alert('Failed to save changes');
                }
            },
            error: function(xhr, status, error) {
                alert('Error occurred while saving changes');
                console.error(xhr.responseText);
            }
        });
    }

    function delete_user($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script
