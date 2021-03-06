<?php
ob_start();
session_start();

//set timezone
date_default_timezone_set('Europe/London');

//database credentials
define('DBHOST','localhost');
define('DBUSER','admin');
define('DBPASS','123321');
define('DBNAME','batuhany_ikinciel');

//application address
define('DIR','http://domain.com/');
define('SITEEMAIL','noreply@domain.com');

try {

	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('classes/user.php');
$user = new User($db); 

?>
<!--<base href="http://www.batuhanyaman.com/ikinciel/"> -->
<meta charset="utf-8">