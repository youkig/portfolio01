<!DOCTYPE html>
<?php include('config.php');?>

<?php
session_start();
?>

<?php
            $sql = "select siteadmin.*,seo_page.id as pid,seo_page.URL as URL2,seo_page.backupfile,seo_page.path as path2 From siteadmin inner join seo_page on siteadmin.id = seo_page.URLID Where seo_page.id=:id";
            $site = $dbh->prepare($sql);
            $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
            $site->execute();
            $result = $site->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($result)){
            foreach($result as $row){
                $sitename = $row['sitename'];
                $url = "http://".$row['URL2'];
                $backfolder = $row['URL'];
                $backupfile = $row['backupfile'];
                $backuppath = $row['path2'];
                $pageid = $row['pid'];
		$resultid = $row['id'];
            }
	}else{
	header("Location:https://autometa.autumn-tec.co.jp/seo_admin/index.php");
	exit();
	}
            ?>
            <?php
            //バックアップからファイルを元に戻す
            $message5="";
            if(!empty($_POST['backup'])){
                //バックアップファイルからテキストを取得する
                $bfile = fopen('./backup/'.$_POST['backfolder'].'/'.$_POST['backupfile'],'r');
                $contents="";
                while($line2 = fgets($bfile)){
                        $contents .= $line2;
                    }
                
                $file2 = fopen($backuppath,'w');
                if(fwrite($file2,$contents)){
                    fclose($file2);
                    $pageid = $_POST['pageid'];
                    $sql = "delete From seo_page Where id=:id";
                    $page = $dbh->prepare($sql);
                    $page->bindValue(':id',$pageid,PDO::PARAM_INT);
                    $page->execute();

                    $sql = "delete From seo_title Where siteid=:id";
                    $page = $dbh->prepare($sql);
                    $page->bindValue(':id',$pageid,PDO::PARAM_INT);
                    $page->execute();

                    $sql = "delete From seo_h1 Where siteid=:id";
                    $page = $dbh->prepare($sql);
                    $page->bindValue(':id',$pageid,PDO::PARAM_INT);
                    $page->execute();

                    $sql = "delete From seo_keyword Where siteid=:id";
                    $page = $dbh->prepare($sql);
                    $page->bindValue(':id',$pageid,PDO::PARAM_INT);
                    $page->execute();

                    $sql = "delete From seo_disc Where siteid=:id";
                    $page = $dbh->prepare($sql);
                    $page->bindValue(':id',$pageid,PDO::PARAM_INT);
                    $page->execute();
                    
                    $message5 = "<p class=\"alert alert-primary\">元に戻しました。</p>";
                }else{
                    $message5 = "<p class=\"alert alert-danger\">失敗しました</p>";
                }
               
            }
            ?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>メタ自動更新プログラム（詳細）</title>

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
    <link href="css/base.css" rel="stylesheet">        
    <script>
        function backupFile(){
            if(window.confirm('本当に元に戻してよろしいですか？')){
               document.getElementById('backupform').submit();
            }else{    
                return false;
            }
        }
    </script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        
           <?php include('include/leftpane.php');?>

           <?php include('include/header.php');?>

        <!-- page content -->
        <div class="right_col" role="main">

            <h3>タイトル、メタ詳細　登録・編集</h3>

            
	<p><a href="result.php?id=<?=$resultid?>" class="underline fs-4">一覧へ戻る</a></p>
            <div class="p-2 bg-dark text-white mb-5">
                <dl>
                    <dt>サイト名</dt>
                    <dd><?=$sitename?></dd>
                    <dt>URL</dt>
                    <dd><a href="<?=$url?>" target="_blank" class="text-white"><?=$url?></a></dd>
                </dl>
            </div>

            <?php
            //title更新
            $message ="";
            if(isset($_POST['titleupdate'])){
                $sql = "update seo_title set title=:title Where id=:id";
                $title = $dbh->prepare($sql);
                $target = str_replace('\n','',trim($_POST['title']));
                $title->bindValue(':id',$_POST['titleid'],PDO::PARAM_INT);
                $title->bindValue(':title',$target,PDO::PARAM_STR);
                $title->execute();
                $result = $title->rowCount();
                if($result>0){
                    $message = "<p class=\"alert alert-primary\">更新されました。</p>";
                }else{
                    $message = "<p class=\"alert alert-danger\">更新に失敗しました</p>";
                }
            }
            ?>

            <div class="block mb-5">
                <h4 class="text-white bg-info text-left p-1"> title 編集</h4>
                <?php
                echo $message;
                $sql = "select * From seo_title Where siteid=:id;";
                $site = $dbh->prepare($sql);
                $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                $site->execute();
                if($result = $site->fetchAll(PDO::FETCH_ASSOC)){
                    foreach($result as $row){
                        $title = $row['title'];
                        $titleid = $row['id'];  
                    }
                     
                }
                
                ?>
                <form action="details.php?id=<?=$_GET['id']?>" method="post">
                    <input type="hidden" name="titleid" value="<?=$titleid?>">
                <textarea name="title" id="title" cols="60" rows="3" style="width:60%;" class="mb-2"><?=$title?></textarea>
                <p><button type="submit" name="titleupdate" class="btn btn-primary" style="font-size:12px;">上記の内容で更新する</button></p>
                </form>
            </div>


            <?php
            //keywaord更新
            $message2 ="";
            if(isset($_POST['keywordupdate'])){
                $sql = "update seo_keyword set keyword=:keyword Where id=:id";
                $keyword = $dbh->prepare($sql);
                $target = str_replace('\n','',trim($_POST['keyword']));
                $keyword->bindValue(':id',$_POST['keywordid'],PDO::PARAM_INT);
                $keyword->bindValue(':keyword',$target,PDO::PARAM_STR);
                $keyword->execute();
                $result = $keyword->rowCount();
                if($result>0){
                    $message2 = "<p class=\"alert alert-primary\">更新されました。</p>";
                }else{
                    $message2 = "<p class=\"alert alert-danger\">更新に失敗しました</p>";
                }
            }
            ?>

            <div class="block mb-5">
                <h4 class="text-white bg-info text-left p-1"> keyword 編集</h4>
                <?php
                echo $message2;
                $sql = "select * From seo_keyword Where siteid=:id;";
                $site = $dbh->prepare($sql);
                $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                $site->execute();
                if($result = $site->fetchAll(PDO::FETCH_ASSOC)){
                    foreach($result as $row){
                        $keyword = $row['keyword'];
                        $keywordid = $row['id'];  
                    }
                     
                }
                
                ?>
                <form action="details.php?id=<?=$_GET['id']?>" method="post">
                    <input type="hidden" name="keywordid" value="<?=$keywordid?>">
                <textarea name="keyword" id="keyword" cols="60" rows="3" style="width:60%;" class="mb-2"><?=$keyword?></textarea>
                <p><button type="submit" name="keywordupdate" class="btn btn-primary" style="font-size:12px;">上記の内容で更新する</button></p>
                </form>
            </div>


            <?php
            //description更新
            $message3 ="";
            if(isset($_POST['descriptionupdate'])){
                $sql = "update seo_disc set disc=:disc Where id=:id";
                $desc = $dbh->prepare($sql);
                $target = str_replace('\n','',trim($_POST['description']));
                $desc->bindValue(':id',$_POST['descid'],PDO::PARAM_INT);
                $desc->bindValue(':disc',$target,PDO::PARAM_STR);
                $desc->execute();
                $result = $desc->rowCount();
                if($result>0){
                    $message3 = "<p class=\"alert alert-primary\">更新されました。</p>";
                }else{
                    $message3 = "<p class=\"alert alert-danger\">更新に失敗しました</p>";
                }
            }
            ?>

            <div class="block mb-5">
                <h4 class="text-white bg-info text-left p-1"> description 編集</h4>
                <?php
                echo $message3;
                $sql = "select * From seo_disc Where siteid=:id;";
                $site = $dbh->prepare($sql);
                $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                $site->execute();
                if($result = $site->fetchAll(PDO::FETCH_ASSOC)){
                    foreach($result as $row){
                        $description = $row['disc'];
                        $descid = $row['id'];  
                    }
                     
                }
                
                ?>
                <form action="details.php?id=<?=$_GET['id']?>" method="post">
                    <input type="hidden" name="descid" value="<?=$descid?>">
                <textarea name="description" id="description" cols="60" rows="3" style="width:60%;" class="mb-2"><?=$description?></textarea>
                <p><button type="submit" name="descriptionupdate" class="btn btn-primary" style="font-size:12px;">上記の内容で更新する</button></p>
                </form>
            </div>

            <?php
            //h1更新
            $message4 ="";
            if(isset($_POST['h1update'])){
                $sql = "update seo_h1 set h1=:h1 Where id=:id";
                $h1 = $dbh->prepare($sql);
                $target = str_replace('\n','',trim($_POST['h1']));
                $h1->bindValue(':id',$_POST['h1id'],PDO::PARAM_INT);
                $h1->bindValue(':h1',$target,PDO::PARAM_STR);
                $h1->execute();
                $result = $h1->rowCount();
                if($result>0){
                    $message4 = "<p class=\"alert alert-primary\">更新されました。</p>";
                }else{
                    $message4 = "<p class=\"alert alert-danger\">更新に失敗しました</p>";
                }
            }
            ?>


            <div class="block mb-5">
                <h4 class="text-white bg-info text-left p-1"> h1 編集</h4>
                <?php
                echo $message4;
                $sql = "select * From seo_h1 Where siteid=:id;";
                $site = $dbh->prepare($sql);
                $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                $site->execute();
                if($result = $site->fetchAll(PDO::FETCH_ASSOC)){
                    foreach($result as $row){
                        $h1 = $row['h1'];
                        $h1id = $row['id'];  
                    }
                     
                }
                
                ?>
                <form action="details.php?id=<?=$_GET['id']?>" method="post">
                    <input type="hidden" name="h1id" value="<?=$h1id?>">
                <textarea name="h1" id="h1" cols="60" rows="3" style="width:60%;" class="mb-2"><?=$h1?></textarea>
                <p><button type="submit" name="h1update" class="btn btn-primary" style="font-size:12px;">上記の内容で更新する</button></p>
                </form>
            </div>

            <?php
            //h5更新
            $message6 ="";
            if(isset($_POST['h5update'])){
                $sql = "update seo_h5 set h5=:h5 Where id=:id";
                $h5 = $dbh->prepare($sql);
                $target = str_replace('\n','',trim($_POST['h5']));
                $h5->bindValue(':id',$_POST['h5id'],PDO::PARAM_INT);
                $h5->bindValue(':h5',$target,PDO::PARAM_STR);
                $h5->execute();
                $result = $h5->rowCount();
                if($result>0){
                    $message6 = "<p class=\"alert alert-primary\">更新されました。</p>";
                }else{
                    $message6 = "<p class=\"alert alert-danger\">更新に失敗しました</p>";
                }
            }
            ?>

            <div class="block mb-5">
                <h4 class="text-white bg-info text-left p-1"> h5 編集</h4>
                <?php
                echo $message6;
                $sql = "select * From seo_h5 Where siteid=:id;";
                $site = $dbh->prepare($sql);
                $site->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
                $site->execute();
                if($result = $site->fetchAll(PDO::FETCH_ASSOC)){
                    foreach($result as $row){
                        $h5 = $row['h5'];
                        $h5id = $row['id'];  
                    }
                     
                }
                
                ?>
                <form action="details.php?id=<?=$_GET['id']?>" method="post">
                    <input type="hidden" name="h5id" value="<?=$h5id?>">
                <textarea name="h5" id="h5" cols="60" rows="3" style="width:60%;" class="mb-2"><?=$h5?></textarea>
                <p><button type="submit" name="h5update" class="btn btn-primary" style="font-size:12px;">上記の内容で更新する</button></p>
                </form>
            </div>


            


            <div class="block">
            <h4 class="text-white bg-warning text-left p-1"> バックアップからファイルを戻す</h4>
            <?=$message5?>
            <form action="details.php?id=<?=$_GET['id']?>" method="post" id="backupform">
                <input type="hidden" name="backupfile" value="<?=$backupfile?>">
                <input type="hidden" name="backfolder" value="<?=$backfolder?>">
                <input type="hidden" name="pageid" value="<?=$pageid?>">
                <input type="submit" name="backup" class="btn btn-danger" style="font-size:12px;" value="ファイルを元に戻す" onclick="backupFile();">
            </form>
            </div>
        <p><a href="result.php?id=<?=$resultid?>" class="underline fs-4">一覧へ戻る</a></p>
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
