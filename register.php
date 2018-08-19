<?php
    session_start();
    require_once 'database.php';
    $errors = array();
    $form_inputs = array(
            'full_name' => 'Full Name can\'t be blank',
            'email' => 'Email can\'t be blank',
            'password' => 'Password can\'t be blank'
    );
    if (isset($_POST['reg_submit'])) {
        foreach ($form_inputs as $input => $error_msg) {
            if (isset($_POST[$input]) && !empty($_POST[$input])) {
                $$input = mysqli_real_escape_string($con,$_POST[$input]);
            } else {
                $errors[] = $form_inputs[$input];
            }
        }

        if (empty($errors)) {
            end($form_inputs);
            $query = "INSERT INTO users ( ";
            foreach ($form_inputs as $input => $error_msg) {
                $query .= " `$input` ";
                if (key($form_inputs) != $input) {
                    $query .= " , ";
                }
            }
            $query .= " ) ";
            $query .= " VALUES ( ";
            foreach ($form_inputs as $input=>$error_msg) {
                $query .= " '{$$input}' ";
                if (key($form_inputs) != $input) {
                    $query .= " , ";
                }
            }
            $query .= " ); ";
            reset($form_inputs);
            $result = mysqli_query($con,$query);
            if ($result) {
                $_SESSION['reg'] = "Registration Successful! Please log in.";
                header("Location: login.php");
            } else {
                $errors[] = mysqli_error($con);
            }

        }

    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Registration Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Restaurant </b>Map</a>
  </div>

  <div class="register-box-body">

    <?php

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class=\"login-box-msg\">$error</p>";
            }
        }

    ?>

    <p class="login-box-msg">Register a new membership</p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="full_name" class="form-control" placeholder="Full name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="reg_submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
