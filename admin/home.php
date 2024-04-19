<?php include('db_connect.php') ?>
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 1): ?>

<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Mahasiswa magang</span>
        <span class="info-box-number">
          <?php echo $conn->query("SELECT * FROM users where type = 2")->num_rows; ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?php echo "Divisi" ?></h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
    <hr class="border-primary">
  </div><!-- /.container-fluid -->
</div>

<div class="row">
  <?php
  // Fetch distinct 'divisi' values from the users table where division is not blank
  $result = $conn->query("SELECT DISTINCT divisi FROM users WHERE divisi != ''");
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      ?>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box" onclick="showDetails('<?php echo $row['divisi']; ?>', 'divisi')">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text" style="font-size: 12px;"><?php echo $row['divisi']; ?></span>
            <span class="info-box-number">
              <?php
              // Count the number of users for each 'divisi'
              $divisi = $row['divisi'];
              $count_result = $conn->query("SELECT * FROM users WHERE divisi = '$divisi'");
              echo $count_result->num_rows;
              ?>
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <?php
    }
  }
  ?>
</div>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?php echo "Universitas" ?></h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
    <hr class="border-primary">
  </div><!-- /.container-fluid -->
</div>

<div class="row">
  <?php
  // Fetch distinct 'university' values from the users table where university is not blank
  $result = $conn->query("SELECT DISTINCT nama_kampus FROM users WHERE nama_kampus != ''");
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      ?>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box" onclick="showDetails('<?php echo $row['nama_kampus']; ?>', 'nama_kampus')">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text" style="font-size: 12px;"><?php echo $row['nama_kampus']; ?></span>
            <span class="info-box-number">
              <?php
              // Count the number of users for each 'universitas'
              $university = $row['nama_kampus'];
              $count_result = $conn->query("SELECT * FROM users WHERE nama_kampus = '$university'");
              echo $count_result->num_rows;
              ?>
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <?php
    }
  }
  ?>
</div>

<!-- Popup Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="userDetailsContent"></div>
      </div>
    </div>
  </div>
</div>

<?php else: ?>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      Welcome <?php echo $_SESSION['login_name'] ?>!
    </div>
  </div>
</div>
<?php endif; ?>

<script>
function showDetails(value, type) {
  $.ajax({
    url: 'get_user_details.php',
    type: 'POST',
    data: { value: value, type: type },
    success: function(response) {
      $('#userDetailsContent').html(response);
      $('#userDetailsModal').modal('show');
    }
  });
}
</script>
