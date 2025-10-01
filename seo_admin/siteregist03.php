<!DOCTYPE html>
<?php include('config.php');?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>メタ自動更新プログラム（新規サイト登録）</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>
<?php
session_start();
?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        
           <?php include('include/leftpane.php');?>

           <?php include('include/header.php');?>

        <!-- page content -->
        <div class="right_col" role="main">

        <h3>新規サイト登録（登録）</h3>

        <div>
            <?php
            $message = "";
            if(isset($_POST['url']) && isset($_POST['sitename']) && isset($_POST['path'])){
                $sql = "select * From siteadmin Where URL=:URL AND path=:PATH";
                $site = $dbh->prepare($sql);
                $site->bindValue(':URL',$_POST['url'],PDO::PARAM_STR);
                $site->bindValue(':PATH',$_POST['path'],PDO::PARAM_STR);
                $site->execute();
                $result = $site->fetch(PDO::FETCH_ASSOC);
                if(!$result){
                    $sql = "insert into siteadmin(URL,sitename,path,indate)";
                    $sql .= " value (:URL,:SITENAME,:PATH,:INDATE)";
                    $site = $dbh->prepare($sql);
                    $site->bindValue(':URL',$_POST['url'],PDO::PARAM_STR);
                    $site->bindValue(':SITENAME',$_POST['sitename'],PDO::PARAM_STR);
                    $site->bindValue(':PATH',$_POST['path'],PDO::PARAM_STR);
                    $site->bindValue(':INDATE',date('Y/m/d'),PDO::PARAM_STR);
                    $site->execute();
                    $message = "登録しました。";
                }else{
                    $message = "すでに登録されています。";
                }
            }
            ?>
            <div class="center-block">
                <p class="alert alert-primary"><?=$message;?></p>
            </div>
        </div>

        </div>
        <!-- /page content -->

        
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="../vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
  </body>
</html>
