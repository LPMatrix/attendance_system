<?php 
	/**
	* 
	*/
	class library
	{
		
		public function Register($name, $email, $std_id, $password,$level){
	        try {
	            $db = DB();
	            $date=date('Y:m:d');
	            $query = $db->prepare("INSERT INTO users(name, std_id, password, email, user_date, user_level) VALUES (:name,:std_id,:password,:email, :user_date, :user_level)");
	            $query->bindParam("name", $name, PDO::PARAM_STR);
	            $query->bindParam("email", $email, PDO::PARAM_STR);
	            $query->bindParam("std_id", $std_id, PDO::PARAM_STR);
	            $enc_password = hash('sha256', $password);
	            $query->bindParam("user_date", $date);
	            $query->bindParam("user_level", $level);
	            $query->bindParam("password", $enc_password, PDO::PARAM_STR);
	            $query->execute();
	            return $db->lastInsertId();
	        } catch (PDOException $e) {
	            exit($e->getMessage());
	        }
    	}

    	public function Login($name, $password)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT * FROM users WHERE (name=:name OR email=:name) AND password=:password");
            $query->bindParam("name", $name, PDO::PARAM_STR);
            $enc_password = hash('sha256', $password);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetch(PDO::FETCH_OBJ);
                return array($result->user_id, $result->user_level);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

        public function redirect($url){
        header("Location: $url");
    }
    
	    public function Logout(){
	        session_destroy();
	        unset($_SESSION['user_id']);
	        return true;
	    }

	    public function runQuery($sql){
	        $db = DB();
	        $stmt = $db->prepare($sql);
	        return $stmt;
	    }


	}
 ?>