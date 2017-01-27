<?php
include('password.php');
class User extends Password{

    private $_db;
	public $name;
	public $surname;
	public $memberID;
	public $telno;
	public $username;
	public $birthDate;
	public $email;
	public $notification;
	public $lastProduct;
	public $showTel;

    function __construct($db){
    	parent::__construct();
    
    	$this->_db = $db;
		if(isset($_SESSION['username']))
		$this->getValues($_SESSION['username']);
    }
	
	public function setLastProduct($lastProduct)
	{
		$stmt = $this->_db->prepare('UPDATE members  SET `lastProductID` = :lastProductID WHERE  memberID='.$this->memberID.' ');
						$stmt->execute(array(
								':lastProductID' => $lastProduct
								
								
						));	
	}
		
	public function log($log){
	
			$stmt = $this->_db->prepare('INSERT INTO logs (memberID,log,time) VALUES (:memberID, :log, :time)');
							$stmt->execute(array(
							':memberID' => $this->memberID,
							':log' => $log,
							':time' => date("Y-m-d H:i:s")
							
							
			));		
	}
	private function getValues($username){
		
		$stmt = $this->_db->prepare('SELECT * FROM members WHERE username = :username');
			$stmt->execute(array('username' => $username));
			$row = $stmt->fetch();
			$this->memberID = $row['memberID'];
			$this->username = $row['username'];
			$this->surname = $row['surname'];
			$this->name = $row['name'];
			$this->telno = $row['telno'];
			$this->birthDate = $row['birthDate'];
			$this->email = $row['email'];
			$this->notification = $row['notification'];
			$this->lastProduct = $row['lastProductID'];
			$this->showTel = $row['showTel'];
		}
	private function get_user_hash($username){	

		try {
			$stmt = $this->_db->prepare('SELECT password FROM members WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			return $row['password'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){
	$this->name = $username;
		$hashed = $this->get_user_hash($username);
		
		if($this->password_verify($password,$hashed) == 1){
		    
		    $_SESSION['loggedin'] = true;
			$_SESSION['username'] = $username;
		    return true;
		} 	
	}
		
	public function logout(){
		session_destroy();
	}
	
	public function checkPass($username,$password){
		$hashed = $this->get_user_hash($username);
		
			if($this->password_verify($password,$hashed) == 1){
				return true;
			}
			return false;
	}
	
	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
	
}


?>