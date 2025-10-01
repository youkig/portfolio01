<?php
include('seo_admin/config.php');

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$sql = "insert into apiget (username,password) values (:username,:password)";
$api = $dbh->prepare($sql);
$api->bindValue(':username',$username,PDO::PARAM_STR);
$api->bindValue(':password',$password,PDO::PARAM_STR);
$api->execute();
?>