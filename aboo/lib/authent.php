<?php
class Authent{

    static function islogged(){
        if(isset($_SESSION['authent']) && isset($_SESSION['authent']['email']) && isset($_SESSION['authent']['password'])){
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT email,password FROM user WHERE email = :email AND password = :password';
            $q = $pdo->prepare($sql);
            $q->execute(array('email'=>$_SESSION['authent']['email'],'password'=>$_SESSION['authent']['password']));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            $count = $q->rowCount($sql);
            Database::disconnect();
                if($count == 1){
                    return true;
                } else {
                    return false;
                }
        } else {
            return false;
        }
    }
}
