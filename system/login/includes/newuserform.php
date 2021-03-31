<?php
class NewUserForm extends DbConn
{
    public function createUser($usr, $uid, $email, $pw)
    {
        try {
            $db = new DbConn;
            $tbl_members = $db->tbl_members;
            // prepare sql and bind parameters
			$stmt = $db->conn->prepare("INSERT INTO ".$tbl_members." (id, username, password, email, verified, watched_media, favorite_media, liked_media) VALUES (:id, :username, :password, :email, :verified, '', '', '')");
			$stmt->bindParam(':id', $uid);
			$stmt->bindParam(':username', $usr);
			$stmt->bindParam(':email', $email);
			$stmt->bindValue(':verified', ($db->conf_registration_verify ? '0' : '1'));
			$stmt->bindParam(':password', $pw);
			$stmt->execute();
			$err = '';
        } catch (PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }
        //Determines returned value ('true' or error code)
        if ($err == '') {
            $success = 'true';
        } else {
            $success = $err;
        };
        return $success;
    }
}