<?php

class database{
    function opencon(): PDO{
        return new PDO(
            dsn: 'mysql:host=localhost;
            dbname=dbs_app',
            username: 'root',
            password: '');
    }

    function signupUser($firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password, $profile_picture_path){
        
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO Users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username,  $password]);

            $userID = $con->lastInsertId();
            $con->commit();

            return $userID;

        }catch (PDOException $e){
            $con->rollBack();
            return false;
        }

    }

}

?>

