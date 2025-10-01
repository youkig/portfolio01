<?php

//DB接続

try{
    $dbh = new PDO('mysql:host=mysql80.salmonyak7.sakura.ne.jp;dbname=salmonyak7_autometa','salmonyak7_autometa','Ja5pc9nTuMg');
} catch (PDOException $e) {
     echo "接続失敗: ". $e->getMessage(). "\n";
    exit();
}

//login check
class LoginCh{
    protected $userid;
    protected $id;
    protected $username;
    

    public function logincheck($userid, $id){
        $sql = "SELECT * From user Where loginid=:email and id=:id and valid=1";
        $dbh2 = new PDO('mysql:host=mysql80.salmonyak7.sakura.ne.jp;dbname=salmonyak7_autometa','salmonyak7_autometa','Ja5pc9nTuMg');
        $login = $dbh2->prepare($sql);
        $login->bindValue(":email", $userid, PDO::PARAM_STR);
        $login->bindValue(":id", $id, PDO::PARAM_STR);
        $login->execute();
        
        
        
        if($result = $login->fetch(PDO::FETCH_ASSOC)){
            //return $this->username = $result['name'];
            $username = $result['name'];
            
            return [$username];
        }else{
            header("location: login.php");
        }
    }
}


?>