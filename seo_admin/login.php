<!DOCTYPE html>
<?php include('config.php');?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>メタ自動更新プログラムログイン</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <?php
  //トークン発行
  session_start();
function setToken(){
  $TOKEN_LENGTH = 32;
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  
  $token = bin2hex($bytes);
  $_SESSION['crsf_token'] = $token;
  return $token;
}


$message ="";
  if(isset($_POST['login'])){
    if($_POST['email']=="" || $_POST['password']==""){
      $message = "<div class='center-block'><p class='alert alert-danger'>メールアドレスまたはパスワードを入力してください。</p></div>";
    }else{
      $sql = "select * From user Where loginid=:email";
      $user = $dbh->prepare($sql);
      $user->bindValue(':email',$_POST['email'],PDO::PARAM_STR);
      $user->execute();
      $result = $user->fetch(PDO::FETCH_ASSOC);
      
      if($result){
        $hashedPassword = $result['password'];
        if(password_verify($_POST['password'], $hashedPassword)){
        $_SESSION["uid"] = $result['id'];
        $_SESSION["userid"] = $result['email'];
        $_SESSION["username"] = $result['username'];

        // セッションハイジャック対策としてセッションIDのリジェネレーションを行う
        session_regenerate_id(true);

        
        header("location: index.php");
        exit();
        }else{
          $message = "<div class='center-block'><p class='alert alert-danger'>パスワードが違います。</p></div>";
        }
      }else{
        $message = "<div class='center-block'><p class='alert alert-danger'>メールアドレスまたはパスワードが違います。</p></div>";
      }
    }

  }elseif(isset($_POST['regist'])){
    //session_start();
    
        if($_SESSION['crsf_token'] !== $_POST["token"]){
            $message = "<div class='center-block'><p class='alert alert-danger'>アクセスが不正です。</p></div>";
        }else{
            unset($_SESSION['crsf_token']);
            if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
              $sql = "insert into user(username,loginid,password,password2,indate)";
              $sql .= " value (:username,:loginid,:password,:password2,:indate)";
              $prepare = $dbh->prepare($sql);
              $prepare->bindValue(':username',$_POST['username'],PDO::PARAM_STR);
              $prepare->bindValue(':loginid',$_POST['email'],PDO::PARAM_STR);
              $prepare->bindValue(':password',password_hash($_POST['password'],PASSWORD_DEFAULT),PDO::PARAM_STR);
              $prepare->bindValue(':password2',$_POST['password'],PDO::PARAM_STR);
              $prepare->bindValue(':indate',date('Y/m/d'),PDO::PARAM_STR);
              $prepare->execute();
              $result = $prepare->fetch(PDO::FETCH_ASSOC);
              //if($result){
                $message = "<div class='center-block'><p class='alert alert-primary'>登録が完了いたしました</p></div>";
            
              //}
            }          
         } 
  }
  
  ?>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
      <?php
      if($_GET['login_form']=='login'):
      ?>  
        <div class="animate form login_form">
          <section class="login_content">
            <form action="login.php" method="post">
              <h1>PHP版 メタ自動更新管理画面</h1>
              <?=$message;?>
              <div>
                <input type="text" name="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <button type="submit" name="login" id="login" class="btn btn-success">ログイン</button></a>
               
              </div>

              <div class="clearfix"></div>

              
            </form>
          </section>
        </div>
    <?php
    elseif($_GET['login_form']=='register'):
    ?>
        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form action="login.php" method="post">
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" name="username" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" name="email" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-primary" name="regist" onclick="window.confirm('登録してよろしいですか？')">登録</button>
              </div>
              <input type="hidden" name="token" value="<?= setToken()?>">
              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="?login_form=login" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

               
              </div>
            </form>
          </section>
        </div>
        <?php
    endif
    ?>
      </div>
    </div>
  </body>
</html>
