<?php
if(!isset($conn))
include 'admin/db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM attendees where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage_attendee">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<input type="hidden" name="event_id" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Nama Depan</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Nama Tengah</label>
							<input type="text" name="middlename" class="form-control form-control-sm"  value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Nama Belakang</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>

						<div class="form-group">
							<label for="" class="control-label">Jenis Kelamin</label>
							<select name="gender" id="" class="custom-select custom-select-sm">
								<option <?php echo isset($gender) && $gender == "Male" ? "selected" : '' ?>>Laki-laki</option>
								<option <?php echo isset($gender) && $gender == "Female" ? "selected" : '' ?>>Perempuan</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Nomor Kontak</label>
							<input type="contact" name="contact" class="form-control form-control-sm" required value="<?php echo isset($contact) ? $contact : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Email</label>
							<input type="email" name="email" class="form-control form-control-sm" required value="<?php echo isset($email) ? $email : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Alamat</label>
							<textarea name="address" id="" cols="30" rows="2" class="form-control" required><?php echo isset($address) ? $address : '' ?></textarea>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Status Absen</label>
							<input type="checkbox" name="status" id="" data-bootstrap-switch data-toggle="toggle" data-on="Present" data-off="Waiting" class="switch-toggle status" data-size="xs" data-onstyle="success" data-offstyle="danger" data-width="5rem" <?php echo isset($status) && $status == '1' ? 'checked' : '' ?>>
						</div>
					</div>
				</div>
			</form>
</div>
<style>
	img#cimg{
		max-height: 15vh;
		/*max-width: 6vw;*/
	}
</style>
<script>
	$('.status').bootstrapToggle()
	$('#manage_attendee').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'admin/ajax.php?action=save_attendee',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data Berhasil Disimpan.',"success");
					setTimeout(function(){
						location.reload()
					},750)
				}
			}
		})
	})

</script>