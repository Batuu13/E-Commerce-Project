<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Profil';

//include header template


?>
<link rel="stylesheet" href="style/blankpage.css">
<div id="Content" class="clearfix">
           <div   class="panel panel-primary">
                <div  class="panel-heading">
                <h3 class="panel-title">Üye Profili</h3>
               		
                </div>
        

<div class="panel-body">

		<?php
		
			if(isset($_GET['id'])){
		
				$stmt = $db->prepare("SELECT * FROM members WHERE memberID =".$_GET['id']." "); 
					$stmt->execute(); 
					$row = $stmt->fetch();
		
				echo'<hr><div style="margin-bottom:8px;" class="row">
              	 <div class="col-md-3"><b>Ad</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$row['name'].'</div>
              </div>
               		<div style="margin-bottom:8px;" class="row">
              	 <div class="col-md-3"><b>Soyad</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$row['surname'].'</div>
              </div>
                  
                   <div style="margin-bottom:8px;" class="row">
              	 <div class="col-md-3"><b>Doğum Tarihi</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$row['birthDate'].'</div>
              </div>
			  <div style="margin-bottom:8px;" class="row">
              	 <div class="col-md-3"><b>Üyelik Tarihi</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$row['registerDate'].'</div>
              </div>
			  ';
              
			if($row['showTel'] == 1)  {
			   echo'    <div style="margin-bottom:8px;" class="row">
              	 <div class="col-md-3"><b>Telefon Numarası</b></div>
				  <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$row['telno'].'</div>
              </div>';}
			}
			else{
				header('Location: index.php');
			}
		
		echo'<hr>';
		?>

</div>
    </div>
        </div>
<?php 
//include header template
require('layout/footer.php'); 
?>