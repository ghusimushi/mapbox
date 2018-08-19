<?php
    session_start();
    require_once 'database.php';

    if (isset($_GET['signout'])) {
        $_SESSION['login'] = '';
        session_destroy();
    }

    if (!isset($_SESSION['login']) || !$_SESSION['login']) {
        header("Location: login.php");
    }

    $errors = array();
    $form_inputs = array(
        'name' => 'Name can\'t be blank',
        'address' => 'Address can\'t be blank',
        'lat' => 'Latitude can\'t be blank',
        'lng' => 'Longitude can\'t be blank',
        'type' => 'Type can\'t be blank'
    );

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $query = "SELECT * FROM markers WHERE id={$_GET['id']}";
        $result = mysqli_query($con,$query);

        if (mysqli_num_rows($result) < 1) {
            $_SESSION['msg'] = "Data can't be retrieved!";
            header("Location: index.php");
        }
        $_SESSION['id'] = $_GET['id'];
    }

    if (isset($_POST['edit_submit'])) {
        foreach ($form_inputs as $input => $error_msg) {
            if (isset($_POST[$input]) && !empty($_POST[$input])) {
                $$input = mysqli_real_escape_string($con,$_POST[$input]);
            } else {
                $errors[] = $form_inputs[$input];
            }
        }

        include_once 'img_upload.php';

        $errors = array_merge($errors,$upload_errors);

        if (empty($errors)) {
            end($form_inputs);
            $query = "UPDATE markers SET ";

            foreach ($form_inputs as $input => $error_msg) {
                $query .= " {$input} =  '{$$input}' ";
//                if (key($form_inputs) != $input) {
                    $query .= " , ";
//                }
            }
            $query.=" img = '".basename($_FILES["img"]["name"])."' ";
            $query .= " WHERE id = {$_SESSION['id']}";
            reset($form_inputs);
            $result = mysqli_query($con,$query);
            if ($result) {
                $_SESSION['id'] = '';
                $_SESSION['msg'] = "Data edited successfully";
                header("Location: index.php");
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
    <title>AdminLTE 2 | General Form Elements</title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">

    <header class="main-header">

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->

            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="map.php" target="_blank">
                            <span class="hidden-xs">Go to Map</span>
                        </a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="<?php $_SERVER['PHP_SELF']; ?>?signout=true">
                            <span class="hidden-xs">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <?php
                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo "<h3 class=\"box-title\">{$error}</h3><br>";
                                }
                            }
                            ?>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo $_SERVER['PHP_SELF']."?id=".$_SESSION['id']; ?>" method="post" role="form" enctype="multipart/form-data">
                            <div class="box-body">
                                <h3>Edit Data</h3>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" value="<?php echo $row['name']; ?>" name="name" id="name" placeholder="Enter Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" value="<?php echo $row['address']; ?>" name="address" id="address" placeholder="Enter Address">
                                        </div>
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" class="form-control" value="<?php echo $row['lat']; ?>" name="lat" id="latitude" placeholder="Enter Latitude">
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" class="form-control" value="<?php echo $row['lng']; ?>" name="lng" id="longitude" placeholder="Enter Longitude">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <input type="text" class="form-control" value="<?php echo $row['type']; ?>" name="type" id="type" placeholder="Enter Type">
                                        </div>
                                        <div class="form-group">
                                            <label for="img">Image</label>
                                            <input type="file" value="<?php echo $row['img']; ?>" name="img" id="img">
                                        </div>
                                <?php } ?>

                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" name="edit_submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
