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
            $a = $_POST['count'];
            $id = $_POST['id'];
            $sitename = $_POST['sitename'];
            for($c=1; $c<=$a; $c++){
                if(isset($_POST['dirname'.$c])){
                    if(!empty($_POST['regichk'.$c])){
                        $result = glob($_POST['dirname'.$c]);

                            foreach($result as $val){
            
                                $file = fopen($val,'r+');

                                //ファイルページごとのIDを登録
                                $sql = "insert into seo_page(URL,sitename,URLID,path,filename) value (:URL,:sitename,:URLID,:path,:filename)";
                                $page = $dbh->prepare($sql);
                                if(!empty($_POST['dircname'.$c])){
                                $page->bindValue(':URL',$sitename.'/'.$_POST['dircname'.$c].'/'.$_POST['filename'.$c],PDO::PARAM_STR);
                                }else{
                                $page->bindValue(':URL',$sitename.'/'.$_POST['filename'.$c],PDO::PARAM_STR);
                                }
                                $page->bindValue(':sitename',$_POST['filename'.$c],PDO::PARAM_STR);
                                $page->bindValue(':URLID',$id,PDO::PARAM_INT);
                                $page->bindValue(':path',$_POST['dirname'.$c],PDO::PARAM_STR);
                                $page->bindValue(':filename',$_POST['filename'.$c],PDO::PARAM_STR);
                                $page->execute();

                                //ID最大値を取得
                                $sql = "select max(id) as mid From seo_page";
                                $page = $dbh->prepare($sql);
                                $page->execute();
                                $maxval = $page->fetch(PDO::FETCH_ASSOC);
                                $mid = $maxval['mid'];
                                
                            if($file){
                                $contents="";
                                while($line = fgets($file)){
                                    $contents .= $line;
                                    if(strpos($line,'<title>')!==false){
                                        $target = array('<title>','</title>','\n');
                                        $title = str_replace($target,'',$line); 
                                        //phpのタグが入っていた場合の処理
                                        if(strpos($title,'?>')!==false){
                                            $title2 = explode(">",$title);
                                            $title = $title2[1];
                                        }
                                        $sql = "insert into seo_title(siteid,title) value (:siteid,:title)";
                                        $site = $dbh->prepare($sql);
                                        $site->bindValue(':siteid',$mid,PDO::PARAM_INT);
                                        $site->bindValue(':title',trim($title),PDO::PARAM_STR);
                                        $site->execute();
                                    }
                        
                                    if(strpos($line,'<meta name="keywords"')!==false){
                                        $target = array('<meta name="keywords" content="','">','\n');
                                        $metakeyword = str_replace($target,'',$line);
                                        //echo "key:".$metakeyword."<br>";
                                        $sql = "insert into seo_keyword(siteid,keyword) value (:siteid,:keyword)";
                                        $site = $dbh->prepare($sql);
                                        $site->bindValue(':siteid',$mid,PDO::PARAM_INT);
                                        $site->bindValue(':keyword',trim($metakeyword),PDO::PARAM_STR);
                                        $site->execute();
                                    }
                        
                                    if(strpos($line,'<meta name="description"')!==false){
                                        $target = array('<meta name="description" content="','">','\n');
                                        $metadescri = str_replace($target,'',$line);
                                        //echo "des:".$metadescri."<br>";
                                        $sql = "insert into seo_disc(siteid,disc) value (:siteid,:disc)";
                                        $site = $dbh->prepare($sql);
                                        $site->bindValue(':siteid',$mid,PDO::PARAM_INT);
                                        $site->bindValue(':disc',trim($metadescri),PDO::PARAM_STR);
                                        $site->execute();
                                    }
                        
                                    if(strpos($line,'<h1>')!==false){
                                        $target = array('<h1>','</h1>','\n');
                                        $h1 = str_replace($target,'',$line);
                                        $sql = "insert into seo_h1(siteid,h1) value (:siteid,:h1)";
                                        $site = $dbh->prepare($sql);
                                        $site->bindValue(':siteid',$mid,PDO::PARAM_INT);
                                        $site->bindValue(':h1',trim($h1),PDO::PARAM_STR);
                                        $site->execute();
                                    }

                                    if(strpos($line,'<h5 id="autochangepg">')!==false){
                                        $target = array('<h5 id="autochangepg">','</h5>','\n');
                                        $h5 = str_replace($target,'',$line);
                                        $sql = "insert into seo_h5(siteid,h5) value (:siteid,:h5)";
                                        $site = $dbh->prepare($sql);
                                        $site->bindValue(':siteid',$mid,PDO::PARAM_INT);
                                        $site->bindValue(':h5',trim($h5),PDO::PARAM_STR);
                                        $site->execute();
                                    }
                                }
                            
                                //バックアップ作成
                                $backupfolder = $_POST['sitename'];
                                    if(!file_exists('./backup/'.$backupfolder)){       
                                        mkdir('./backup/'.$backupfolder,0777,true);
                                    }
                                $bpath = fopen('./backup/'.$backupfolder.'/'.$_POST['filename'.$c].'_'.$mid.'.txt','w');
                                fwrite($bpath, $contents);
                                fclose($bpath);

                                //バックアップファイル登録
                                $sql = "update seo_page set backupfile=:backupfile Where id=:mid";
                                $page = $dbh->prepare($sql);
                                $page->bindValue(':backupfile',$_POST['filename'.$c].'_'.$mid.'.txt',PDO::PARAM_STR);
                                $page->bindValue(':mid',$mid,PDO::PARAM_INT);
                                $page->execute();
                        
                                $result2 = $_POST['dirname'.$c];
                                $file2 = fopen($result2,'w');
                                //バックアップファイルからテキストを取得する
                                $bfile = fopen('./backup/'.$backupfolder.'/'.$_POST['filename'.$c].'_'.$mid.'.txt','r');
                                $contents="";
                                $contents .="<?php \$siteid=".$mid."?>".PHP_EOL;
                                $contents .="<?php include(\"include/autometa.php\");?>".PHP_EOL;
                                while($line2 = fgets($bfile)){
                                        if(strpos($line2,'<title>')!==false){
                                            if(isset($title2[0])){
                                                $contents .='<title>'.$title2[0].'><?=$n_title?></title>'.PHP_EOL;
                                                $title2 = [];
                                            }else{
                                                $contents .='<title><?=$n_title?></title>'.PHP_EOL;
                                            }
                                    }elseif(strpos($line2,'<meta name="keywords"')!==false){
                                        $contents .='<meta name="keywords" content="<?=$n_keyword?>">'.PHP_EOL;
                                    }elseif(strpos($line2,'<meta name="description"')!==false){
                                        $contents .='<meta name="description" content="<?=$n_description?>">'.PHP_EOL;
                                    }elseif(strpos($line2,'<h1>')!==false){
                                        $contents .='<h1><?=$n_h1?></h1>'.PHP_EOL;
                                    }elseif(strpos($line2,'<body>')!==false){
                                        $contents .='<body>'.PHP_EOL;
                                        $contents .='<?php'.PHP_EOL;
                                        $contents .='if(!empty($n_h5)){'.PHP_EOL;
                                        $contents .='?>'.PHP_EOL;
                                        $contents .='<h5 id="autochangepg"><?=$n_h5?></h5>'.PHP_EOL;
                                        $contents .='<?php'.PHP_EOL;
                                        $contents .='}'.PHP_EOL;
                                        $contents .='?>'.PHP_EOL;
                                    }elseif(strpos($line2,'<h5 id="autochangepg">')!==false){
                                        $contents .= PHP_EOL;
                                    }else{
                                        $contents .= $line2;
                                    }
                                }
                                fwrite($file2,$contents);
                                
                            }
                            
                        }
                    }
                }
            } //for分終わり
            ?>
        <p>登録されました。</p>
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
