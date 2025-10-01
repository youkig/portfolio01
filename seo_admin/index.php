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

    <title>メタ自動更新プログラム（メイン画面）</title>

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

        <h3>登録サイト</h3>
        <?php
        $sql = "select * From siteadmin Where disp=1 order by URL";
        $site = $dbh->prepare($sql);
        $site->execute();
        $result = $site->fetchAll(PDO::FETCH_ASSOC);
        
        if($result){
        ?>
        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>ファイル一覧</th>  
                          <th>サイト名</th>
                          <th>サイトURL</th>
                          <th>物理パス</th>
                          <th>登録日</th>
                      
                        </tr>
                      </thead>


                      <tbody>
                        <?php
                        foreach($result as $val){
                        
                        $sql = "select * From seo_page Where URLID=:URLID";
                        $page = $dbh->prepare($sql);
                        $page->bindValue(':URLID',$val['id'],PDO::PARAM_INT);
                        $page->execute();
                        $result = $page->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                          <td>
                          <?php
                          if($result){
                            echo "<a href=\"result.php?id=".$result['URLID']."\" style=\"text-decoration:underline;\">[ファイル一覧]</a>";
                          }
                          ?></td>
                          <td><?=$val['sitename'];?>  <i class="fa fa-sitemap"></i><a href="fileget.php?id=<?=$val['id']?>" class="link-success" style="text-decoration:underline;">[メタタグ登録]</a></td>
                          <td><a href="http://<?=$val['URL'];?>" target="_blank" style="text-decoration:underline;"><?=$val['URL'];?></a></td>
                          <td><?=$val['path'];?></td>
                          <td><?=$val['indate'];?></td>
                          
                        </tr>
                       <?php
                        }
                       ?>
                        
                      </tbody>
                    </table>
          <?php
          }
          ?>
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
