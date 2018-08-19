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

    if (isset($_GET['del']) && !empty($_GET['del'])) {
        $query = "DELETE FROM markers WHERE id={$_GET['del']} LIMIT 1";
        $result = mysqli_query($con,$query);
        if ($result) {
            $_SESSION['msg'] = "Data deleted successfully!";
            header("Location: index.php");
        } else {
            $_SESSION['msg'] = mysqli_error($con);
        }
    }

    $query = "SELECT * FROM markers";
    $result = mysqli_query($con,$query);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Data Tables</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">

  <header class="main-header">

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->


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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Restaurant Map Data</h3>
                <br><br>
                <p><a href="add_data.php" class="btn btn-primary">Add Data</a></p>
                <?php
                    if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
                        echo "<h3 class=\"box-title\">{$_SESSION['msg']}</h3>";
                        $_SESSION['msg'] = '';
                    }
                ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Type</th>
                    <th>Image</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    if (mysqli_num_rows($result) < 0) {
                        echo "<h3 class=\"box-title\">No data available</h3>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['lat']; ?></td>
                                <td><?php echo $row['lng']; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><img src="uploads/<?php echo $row['img']; ?>" height="50px" width="50px" alt=""></td>
                                <td><a href="edit_data.php?id=<?php echo $row['id']; ?>">Edit</a> &nbsp;&nbsp; <a href="index.php?del=<?php echo $row['id']; ?>">Delete</a></td>
                            </tr>
                        <?php }
                    }
                ?>


                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
