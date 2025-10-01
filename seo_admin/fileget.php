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

        <h3>メタタグ登録</h3>
        <?php
        $sql = "select * From siteadmin Where id=:id AND disp=1";
        $site = $dbh->prepare($sql);
        $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
        $site->execute();
        $result = $site->fetchAll(PDO::FETCH_ASSOC);
        
        if(!empty($result)){
           foreach($result as $row){
            $path = $row['path'];
            $id = $row['id'];
            $sitename = $row['URL'];
          }
           $dirs = scandir($path);
        ?>
        <form action="fileget02.php" method="post">
          <input type="hidden" name="id" value="<?=$id?>">
          <input type="hidden" name="sitename" value="<?=$sitename?>">
        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>登録</th>
                          <th>ファイル名</th>
                          <th>物理パス</th>
                        </tr>
                      </thead>


                      <tbody>
                        <?php
                        $b=0;
                        $c=0;
                        foreach($dirs as $dir){
                            if($dir !== '.' && $dir !== '..' && strpos($dir,'control')===false && strpos($dir,'css')===false && strpos($dir,'img')===false && strpos($dir,'include')===false && strpos($dir,'js')===false && strpos($dir,'photo')===false && strpos($dir,'config')===false && strpos($dir,'seo_admin')===false && strpos($dir,'htaccess')===false){
                                if(is_dir($path.$dir)){
                                    $direc=scandir($path.$dir);
                                    foreach($direc as $dire){
                                        if($dire !== '.' && $dire !== '..'){
                                        $c++;
                                        //すでに登録されているかチェック
                                        $sql = "select path From seo_page Where path='".$path.$dir.'/'.$dire."'";
                                        $page = $dbh->prepare($sql);
                                        $page->execute();
                                        $result = $page->fetch(PDO::FETCH_ASSOC);
                                        var_dump($result);
                                          if(empty($result)){
                                            $b++;
                                        ?>
                            <tr>
                              <td style="color: red;">済</td>
                            <td><?=$dir.'/'.$dire?><input type="hidden" name="filename<?=$c?>" value="<?=$dire?>"><input type="hidden" name="dircname<?=$c?>" value="<?=$dir?>"></td>
                            <td><?=$path.$dir.'/'.$dire?><input type="hidden" name="dirname<?=$c?>" value="<?=$path.$dir.'/'.$dire?>"></td>
                            </tr>            
                                        <?php
                                          }
                                        }
                                    }
                                }else{
                                    $c++;
                                    //すでに登録されているかチェック
                                   
                                    $sql = "select path From seo_page Where path='".$path."/".$dir."'";
                                    $page = $dbh->prepare($sql);
                                    $page->execute();
                                    $result = $page->fetch(PDO::FETCH_ASSOC);
                                    
                                      if(!$result){
                                        $b++;
                        ?>
                            <tr>
                              <td><input type="checkbox" name="regichk<?=$c?>" value="1"></td>
                              <td><?=$dir?><input type="hidden" name="filename<?=$c?>" value="<?=$dir?>"></td>
                              <td><?=$path.'/'.$dir?><input type="hidden" name="dirname<?=$c?>" value="<?=$path.'/'.$dir?>"></td>
                            </tr>
                            
                        <?php
                                      }
                                }
                            }
                        }
                        ?>
                        
                      </tbody>
                    </table>
                    
                    <input type="hidden" name="count" value="<?=$c?>">
                    <?php
                    if($b==0){
                      echo "<p>既に登録済みか、登録できるファイルがありません。</p>";
                    }else{
                    ?>
                    <p><button type="submit" name="regist" class="btn btn-danger" onclick="return confirm('登録してよろしいですか？')">登録</button></p>
                    <?php
                    }
                    ?>
        </form>       
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
