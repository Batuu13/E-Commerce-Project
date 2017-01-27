<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Şikayet Et';

//include header template


?>
<link rel="stylesheet" href="style/blankpage.css">
<div id="Content" class="clearfix">
           <div   class="panel panel-primary">
                <div  class="panel-heading">
                <h3 class="panel-title">Şikayet Et</h3>
               		
                </div>
        

<div class="panel-body">

		<?php
		
			if(isset($_POST['gonder'])){
		
				// DİREK KİŞİYE / İLANA ŞİKAYET
				  
						
						
						$stmt = $db->prepare('INSERT INTO sikayetler (sikayetEden,hedefID,tur,ekleme,date) VALUES (:sikayetEden,:hedefID,:tur,:ekleme,:date)');
						$stmt->execute(array(
						':sikayetEden' =>  $user->memberID,
						':hedefID' => $_POST['hedefID'],
						':tur' =>  $_POST['tur'],
						':tur' =>  $_POST['ekleme'],
						':date' => date("Y-m-d H:i:s")
						
						
						
					));
					
						echo'<div class="alert alert-success" role="alert"> Şikayetiniz iletildi, en kısa zamanda kontrol edilecektir.</div>';
		
			}
			
				if(isset($_POST['gonder2'])){
		
				// ÖNERİ VE ŞİKAYET
				  
				
				
						$stmt = $db->prepare('INSERT INTO sikayetler (sikayetEden,hedefID,tur,ekleme,date) VALUES (:sikayetEden,:hedefID,:tur,:ekleme,:date)');
						$stmt->execute(array(
						':sikayetEden' =>  $user->memberID,
						':hedefID' => $_POST['hedefID'],
						':tur' =>  $_POST['tur'],
						':tur' =>  $_POST['ekleme'],
						':date' => date("Y-m-d H:i:s")
						
						
						
					));
					
						echo'<div class="alert alert-success" role="alert"> Şikayetiniz iletildi, en kısa zamanda kontrol edilecektir.</div>';
		
			}
			
			if(isset($_GET['id'])){
				
				  $stmt = $db->prepare("SELECT name,surname FROM members WHERE memberID =".$_GET['id']." "); 
					$stmt->execute(); 
					$row = $stmt->fetch();
				
				echo'<form  method="post" action="sikayet.php">
						 <div style="margin-bottom:20px;" class="row">
							<div class="col-md-2">
								Şikayet Edilen İlan : 
							</div>
							<div class="col-md-6">
								<b><a href="ilangoster.php?id='.$_GET['id'].'">'.$_GET['id'].'</a></b>
								<input type="hidden" value="'.$_GET['id'].'" name="hedefID"> 
							</div>
						 </div>
						 
						  <div style="margin-bottom:20px;" class="row">
							<div class="col-md-2">
								Şikayet Türü: 
							</div>
							<div class="col-md-3">
								<select name="tur" class="form-control">
								  <option>Uygunsuz İçerik</option>
								  <option>Hakaret</option>
								  <option>Sahte Üyelik veya İlan</option>
								  <option>Diğer</option>
								</select>
							</div>
						 </div>
						 
						  
						  <div style="margin-bottom:20px;" class="row">
							<div class="col-md-2">
								Eklemek İstedikleriniz: 
							</div>
							<div class="col-md-6">
								<textarea class="form-control" name="ekleme"></textarea> 
							</div>
						 </div>
						 
						 <hr>
						 
						 <input class="btn btn-danger" type="submit" value="Şikayet Et" name="gonder"/>
					</form>
			   ';
				
			}
			else{
				echo'
				<form method="post" action="sikayet.php">
						
						  <div style="margin-bottom:20px;" class="row">
							<div class="col-md-2">
								Tür: 
							</div>
							<div class="col-md-6">
								<select name="tur" class="form-control">
								  <option>Öneri</option>
								  <option>Şikayet</option>
								</select>
							</div>
						 </div>
						 
						  
						  <div style="margin-bottom:20px;" class="row">
							<div class="col-md-2">
								Düşünceleriniz: 
							</div>
							<div class="col-md-6">
								<textarea class="form-control" name="ekleme"></textarea> 
							</div>
						 </div>
						 
						 <hr>
						 
						 <input class="btn btn-danger" type="submit" value="Gönder" name="gonder2"/>
					</form>
				
				';
				
			}
		
		
		
		?>

</div>
    </div>
        </div>
<?php 
//include header template
require('layout/footer.php'); 
?>