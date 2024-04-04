<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      background-image: url(plnscUnit.jpg);
    }

    .login-box {
      margin: 100px auto;
      margin-top: 200px;
      max-width: 600px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .login-text {
      text-align: center;
      padding: 20px 0;
    }

    .login-logo img{
      width: 500px;
    }

    .login-card-body {
      padding: 30px;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <div class="login-logo">
      <img src="logoplnsc.png" alt="">
    </div>
    <div class="login-text">
      <h2>Absensi Magang PT PLN Suku Cadang</h2>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <form action="" id="login-form">
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" required placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" required placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <!-- <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div> -->
            <!-- /.col -->
            <!-- <div class="col-4" style=""> -->
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
	      <center> <a href="/intern_plnsc/admin">Login as Admin</a> </center>
            <!-- </div> -->
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#login-form').submit(function (e) {
        e.preventDefault();
        if ($(this).find('.alert-danger').length > 0)
          $(this).find('.alert-danger').remove();
        $.ajax({
          url: 'admin/ajax.php?action=login2',
          method: 'POST',
          data: $(this).serialize(),
          error: function (err) {
            console.log(err);
          },
          success: function (resp) {
            if (resp == 1) {
              location.href = 'index.php?page=home';
            } else {
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
            }
          }
        });
      });
    });
  </script>
</body>

</html>