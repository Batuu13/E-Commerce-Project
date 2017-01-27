<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Members Page';
$active = 1;
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
					
					echo' <form action="memberpage.php" method="post"  >
						 <div class="row">
              	 <div class="col-md-3"><b>Kullanıcı Adı</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6">'.$user->username.'</div>
              </div>
                    <div class="row">
              	 <div class="col-md-3"><b>Ad</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6"><input class="form-control" name="name" type="text"  value="'.$user->name.'"></div>
              </div>
			  		 <div class="row">
              	 <div class="col-md-3"><b>Soyad</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6"><input class="form-control" name="surname" type="text"  value="'.$user->surname.'"></div>
              </div>
                   <div class="row">
              	 <div class="col-md-3"><b>E-mail</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6"><input class="form-control" name="email" type="email"  value="'.$user->email.'"></div>
              </div>
                   <div class="row">
              	 <div class="col-md-3"><b>Doğum Tarihi</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6"><input class="form-control"  name="birthDate" type="date"   value="'.$user->birthDate.'"></div>
              </div>
                   <div class="row">
              	 <div class="col-md-3"><b>Telefon Numarası</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6"><input class="form-control" name="telno" type="tel" value="'.$user->telno.'"></div>
			
              </div>
               <div class="row">
              	 <div class="col-md-4"><b>Telefon Gösterilsin Mi?</b></div>
				 <div class="col-md-1">:</div>
				 <div class="col-md-6" style="padding-top:6px;"><input  class="checkbox" type="checkbox"  name="showTel" value="1" ';  if($user->showTel == 1){echo "checked"; } echo'></div>
              </div>
                  <hr>
              <div align="right">
              <button  type="submit" name="kaydet" class="btn btn-primary btn-lg">Kaydet</button>
			  </div>
               </form>
					
					';
					
					
					}
					
				
				else{
						if(isset($_POST['kaydet']))
				{
					$degisenler = " ";
					if($user->name != $_POST['name'])
					{
						$degisenler .= $user->name . " -> " . $_POST['name']."  ";
					}
					if($user->surname != $_POST['surname'])
					{
						$degisenler .= $user->surname . " -> " . $_POST['surname']."  ";
					}
					if($user->email != $_POST['email'])
					{
						$degisenler.= $user->email . " -> " . $_POST['email']."  ";
					}
					if($user->telno != $_POST['telno'])
					{
						$degisenler .= $user->telno . " -> " . $_POST['telno']."  ";
					}
					if($user->birthDate != $_POST['birthDate'])
					{
						$degisenler.= $user->birthDate . " -> " . $_POST['birthDate']."  ";
					}
					
						$stmt = $db->prepare('INSERT INTO logs (memberID,log,time) VALUES (:memberID, :log, :time)');
							$stmt->execute(array(
							':memberID' => $user->memberID,
							':log' => "Profilini Güncelledi:" .$degisenler,
							':time' => date("Y-m-d H:i:s")
							
							
						));	
					
					
					
					
						$stmt = $db->prepare('UPDATE members  SET `name` = :name,`surname` =:surname,`email` =:email,`telno` =:telno,`birthDate` =:birthDate ,`showTel` =:showTel WHERE  memberID='.$user->memberID.' ');
						$stmt->execute(array(
								':name' => $_POST['name'],
								':surname' => $_POST['surname'],
								':email' => $_POST['email'],
								':telno' => $_POST['telno'],
								':birthDate' => $_POST['birthDate'],
								':showTel' => $_POST['showTel']
								
						));
					
					
					
					
					
					echo "<h4 class='alert alert-success' role='alert'>Profilinizi Güncellediniz.</h4>";
					header('Location: memberpage.php');
					// ADD REFRESH
				}
				?>
                
              <div class="row">
              	 <div class="col-md-4"><b>Kullanıcı Adı</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->username; ?></div>
              </div>
                    <div class="row">
              	 <div class="col-md-4"><b>Ad</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->name; ?></div>
              </div>
               		<div class="row">
              	 <div class="col-md-4"><b>Soyad</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->surname; ?></div>
              </div>
                   <div class="row">
              	 <div class="col-md-4"><b>E-mail</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->email; ?></div>
              </div>
                   <div class="row">
              	 <div class="col-md-4"><b>Doğum Tarihi</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->birthDate; ?></div>
              </div>
                   <div class="row">
              	 <div class="col-md-4"><b>Telefon Numarası</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6"><?php echo $user->telno; ?></div>
                 	
              </div>
                <div class="row">
              	 <div class="col-md-4"><b>Telefon Gösterilsin Mi?</b></div>
                 <div class="col-md-1">:</div>
				 <div class="col-md-6" style="padding-top:6px;"><input class="checkbox" type="checkbox"  name="showTel" value="1" disabled="disabled"  <?php if($user->showTel == 1){echo "checked"; } ?>></div>
              </div>
                  <hr>
               <form action="memberpage.php" method="post"  style="text-align:right;">
              <input type="submit" name="degistir" value="Değiştir" class="btn btn-primary btn-lg">
               </form>
               
               <?php
			   
				}
				
				?>
               
				
            </div>
        </div>
	
	


</div>

<?php 
//include header template
require('layout/footer.php'); 
?>