<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Şifre Değiştir';
$active = 2;
//include header template


?>
<link rel="stylesheet" href="style/single.css">

 <div id="Content" class="clearfix">
         <?php
		   include("profilkate.php"); // INCLUDING CATEGORIES FOR PROFILE
		   ?>
            <div id="Icerik" class="clearfix">
                
              <H4><u>KİŞİSEL BİLGİLER</u></H4>
                <?php 
				
			
				
				
				
				if(isset($_POST['degistir']))
				{
					if(strlen($_POST['suanki']) < 4){
							$error[] = 'Şuanki şifreniz boş olamaz.';
						}
		
					if($user->checkPass($user->username,$_POST['suanki'])){
					$error[] = 'Şuanki şifreniz yanlış.';
						}
					if(strlen($_POST['yeni1']) < 4 || strlen($_POST['yeni2']) < 4){
							$error[] = 'Yeni şifre çok kısa.';
						}
					if($_POST['yeni1'] != $_POST['yeni2']){
							$error[] = 'Yeni şifre uyuşmuyor.';
						}
				if(isset($error)){
					echo '<p class="alert alert-danger" role="alert">';
					foreach($error as $error){
						echo $error.'<br>';
					}
					echo '</p>';
				}
				else{			
					$stmt = $db->prepare('UPDATE members  SET `password` = :password WHERE  memberID='.$user->memberID.' ');
						$stmt->execute(array(
								':password' => $user->password_hash($_POST['yeni1'], PASSWORD_BCRYPT)
								
								
						));
						
					
					$stmt = $db->prepare('INSERT INTO logs (memberID,log,date) VALUES (:memberID, :log, :date)');
							$stmt->execute(array(
							':memberID' => $user->memberID,
							':log' => "Şifresini Değiştirdi",
							':date' => date("Y-m-d H:i:s")
							
							
						));	
						
					// UPDATE LOGS !!
					
					echo "<h4 class='alert alert-success' role='alert'>Şifrenizi Değiştirdiniz.</h4>";
					// ADD REFRESH
				}
				}
					
				
				
				?>
                
             <form action="passwordchange.php" method="post"  >
						 
                    <div class="row">
              	 <div class="col-md-4"><b>Şuanki Şifreniz</b></div>
				 <div class="col-md-6"><input class="form-control"  type="password" name="suanki"></div>
              </div>
			  		 <div class="row">
              	 <div class="col-md-4"><b>Yeni Şifreniz</b></div>
				 <div class="col-md-6"><input class="form-control" value="<?php if(isset( $_POST['yeni1']))echo $_POST['yeni1'] ?>" type="password" name="yeni1"></div>
              </div>
                   <div class="row">
              	 <div class="col-md-4"><b>Yeni Şifrenizin Tekrarı</b></div>
				 <div class="col-md-6"><input class="form-control" value="<?php if(isset( $_POST['yeni2']))echo $_POST['yeni2'] ?>"  type="password" name="yeni2"></div>
              </div>
                   
              
                  <hr>
               <div align="right">
              <button type="submit" name="degistir" class="btn btn-primary btn-lg">Değiştir</button>
               </div>
               </form>
               
             
               
				
            </div>
        </div>
	
	

</div>

<?php 
//include header template
require('layout/footer.php'); 
?>