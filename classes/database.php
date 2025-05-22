<?php
class database{
 
    function opencon(): PDO{
        return new PDO(
            dsn: 'mysql:host=localhost;
            dbname=lms_app',
            username: 'root',
            password: '');
    }
 
    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {
 
        $con = $this->opencon();
       
        try {
            $con->beginTransaction();
 
            $stmt = $con->prepare("INSERT INTO users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);
           
            $userID = $con->lastInsertId();
           
 
            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userID, $profile_picture_path]);
 
            $con->commit();
 
            return $userID;
 
        } catch (PDOException $e) {
 
            $con->rollback();
            return false;
 
        }
 
    }
 
    function insertAddress($userID, $street, $barangay, $city, $province) {
 
        $con = $this->opencon();
 
        try {
            $con->beginTransaction();
 
            $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);
 
            $addressID = $con->lastInsertId();
 
            $stmt = $con->prepare("INSERT INTO users_address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressID]);
 
            $con->commit();
 
            return true;
 
        } catch (PDOException $e) {
 
            $con->rollback();
            return false;
 
        }
 
    }

        function loginUser($email, $password){
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Users WHERE user_email = ?");
        $stmt->execute([$email]);
        $email = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($email && password_verify($password, $email['user_password'])) {
            return $email; 
            
    }else{
        
        return false;
     
    }


}
 
function addAuthor($authorfirst, $authorlast, $authorbday, $authornat){

        $con = $this->opencon();

        try{
             $con->beginTransaction();

        $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
        $stmt->execute([$authorfirst, $authorlast, $authorbday, $authornat]);
        
         $authorID = $con->lastInsertID();
         $con->commit();

         return $authorID;

        }catch (PDOException $e){
 
            $con->rollback();
            return false;
 
        }
}

function addGenre($genreName){

        $con = $this->opencon();

        try{
             $con->beginTransaction();

        $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)");
        $stmt->execute([$genreName]);
        
         $genreID = $con->lastInsertID();
         $con->commit();

         return $genreID;

        }catch (PDOException $e){
 
            $con->rollback();
            return false;
 
        }
}
 
}
?>
