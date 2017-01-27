
                
<?php
require('includes/config.php');
require('layout/header.php');
//collect values from the url
if(!empty($_GET['x']) && !empty($_GET['y'])){
$memberID = trim($_GET['x']);
$active = trim($_GET['y']);

//if id is number and the active token is not empty carry on
if(is_numeric($memberID) && !empty($active)){

	//update users record set the active column to Yes where the memberID and active value match the ones provided in the array
	$stmt = $db->prepare("UPDATE members SET active = 'Yes' WHERE memberID = :memberID AND active = :active");
	$stmt->execute(array(
		':memberID' => $memberID,
		':active' => $active
	));

	//if the row was updated redirect the user
	if($stmt->rowCount() == 1){

		//redirect to login page
		header('Location: login.php?action=active');
		exit;

	}
	
}
	
}
else {
	    echo '<link rel="stylesheet" href="style/blankpage.css">
<div id="Content" class="clearfix">
<div   class="panel panel-default">';
		echo "<hr>&nbsp;&nbsp; Hesabınızı aktifleştirirken bir hata oluştu.<hr>";
		echo "</div></div>"; 
	}
?>


