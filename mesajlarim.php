<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Mesajlarım';

//include header template


?><script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="style/bootstrap/js/bootstrap.js"></script>



<link rel="stylesheet" href="style/single.css">

 <div id="Content" class="clearfix">
           <?php
		   include("profilkate.php"); // INCLUDING CATEGORIES FOR PROFILE
		   ?>
            <div id="Icerik" class="clearfix">
                
              
              
              <?php

			 
			  if(isset($_POST['yanitla2'])){
				  
				  
				  $user->log($_POST['senderID']."'e bir mesaj yolladı  :". $_POST['mesaj']);
				  
				// UPDATE LOGS
				
				$stmt = $db->prepare('INSERT INTO mesajlar (senderID,recieverID,message,date,title,ilanNo) VALUES (:senderID,:recieverID,:message,:date,:title,:ilanNo)');
				$stmt->execute(array(
				':senderID' =>  $user->memberID,
				':recieverID' => $_POST['senderID'],
				':message' =>  $_POST['mesaj'],
				':date' => date("Y-m-d H:i:s"),
				':title' =>  $_POST['title'],
				':ilanNo' => $_POST['ilanNo']
				
				
			));
			
				echo'<div class="alert alert-success" role="alert"> Mesajınız gönderildi.</div>';
				  
			  }

			  
			  if(isset($_POST['yanitla'])){
				  
				  
				echo'  <div class="panel panel-default">
				  <div class="panel-heading"><a href="profil.php?id='.$_POST['sender'].'"><span style="float: left;">'.$_POST['isim'].'</span> </a>
  					<span style="float: right;">'.$_POST['tarih'].'</span>
					&nbsp;</div>
				  <div class="panel-body">
						'.$_POST['mesaj'].'
						
						<hr>
						<form action="mesajlarim.php" method="post">
							
						<label> Mesajınız </label>
						
						<textarea class="form-control" name="mesaj" rows="8"></textarea>
						<input type="hidden" name="senderID" value="'.$_POST['sender'].'">
						<input type="hidden" value="'.$_POST['ilanNo'].'" name="ilanNo">
						<input type="hidden" value="'.$_POST['title'].'" name="title">
						<hr>
						<span style="float: right;"><button class="btn btn-success" name="yanitla2" type="submit">Gönder</button> </span>
						</form>
				  </div>
				</div>';
				
			  }
			  
			  if(isset($_POST['sil'])){
				
					  $stmt = $db->prepare("SELECT message FROM mesajlar WHERE id =".$_POST['id']." "); 
					$stmt->execute(); 
					$row = $stmt->fetch();
					
					$user->log("bir mesajı sildi :" . $row['message']); // UPDATE LOG
				
					$sql = "DELETE FROM mesajlar WHERE id =  :mesajID";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':mesajID', $_POST['id'], PDO::PARAM_INT);   
					$stmt->execute();  
					
					echo'<div class="alert alert-success" role="alert"> Mesajı başarıyla sildiniz.</div>';
					
					
					
					 
					
			  }
			  
	if(!isset($_GET['mesajID']) && !isset($_POST['yanitla'])){	  
			  
			  	require_once('classes/paginator.php');
				$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				$links = (isset($_GET['links'])) ? $_GET['links'] : 6;
				$conn       = new mysqli( 'localhost', 'batuhany_admin', 'batu9595..', 'batuhany_ikinciel' );
				 $query = "SELECT * FROM mesajlar WHERE recieverID = ".$user->memberID."";
				 $paginator = new Paginator($conn ,$query);
             
			 		$results = $paginator->getData($limit,$page);
					
					if($results->total == 0)
			{
				echo'<div class="alert alert-info" role="alert"> Henüz bir bildiriminiz yok.</div>';
			}
			else{
					
				echo $paginator->createLinks($links, 'pagination pagination-sm');
				echo'<table class="table table-hover">';
				$i = 1;
				echo'
					 <thead>
					<tr>
					  <th>#</th>
					  <th>Gönderen</th>
					
					  <th>Konu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					  <th>Tarih</th>
					  
					</tr>
					
				  </thead>
				
				
				<tbody>';
				foreach($results->data as $row){
					
					  $stmt = $db->prepare("SELECT name,surname FROM members WHERE memberID =".$row['senderID']." "); 
					$stmt->execute(); 
					$row2 = $stmt->fetch();
					
					$read = ($row['read'] == 0) ? "warning" : "";
				echo'<tr class="'.$read.'">
							 
							  <th scope="row">
								<b>'.$i.' </b>
							  </th>
						 
						 		<td>
								<a href="profil.php?id='.$row['senderID'].'">	'.$row2['name'].' '.$row2['surname'] .'</a>
								</td>
							  	<td>
								<a href="mesajlarim.php?mesajID='.$row['id'].'">	' .$row['title']. ' </a>
								</td>
								<td>
									'.$row['date'].'
									
									
								</td>
							
						 
					</tr>';
				}
				echo'</tbody></table>';
				
			}
	}
	if(isset($_GET['mesajID'])){
			$stmt = $db->prepare('UPDATE mesajlar  SET `read` = :read WHERE  id ='.$_GET['mesajID'].' ');
						$stmt->execute(array(
								':read' => 1								
						));
		
			 $stmt = $db->prepare("SELECT * FROM mesajlar WHERE id =".$_GET['mesajID']." "); 
			 $stmt->execute(); 
			 $row = $stmt->fetch();
			 
			 $stmt = $db->prepare("SELECT * FROM products WHERE productID =".$row['ilanNo']." "); 
			 $stmt->execute(); 
			 $row2 = $stmt->fetch();
			 
			 $stmt = $db->prepare("SELECT name,surname FROM members WHERE memberID =".$row['senderID']." "); 
					$stmt->execute(); 
					$row3 = $stmt->fetch();
			 
			echo'<div class="alert alert-warning" role="alert">
					<b>İlan Bilgileri:</b>
					<div class="row">
						<div class="col-md-9">
						<a href="ilangoster.php?id='.$row['ilanNo'].'">'.$row2['title'].'</a>
						</div>
						<div class="col-md-3">
						<b>'.$row2['price'].'</b> TL
						</div>
					</div>	
			
				</div>
				
				<div class="panel panel-default">
				  <div class="panel-heading"><a href="profil.php?id='.$row['senderID'].'"><span style="float: left;">'.$row3['name'].' '.$row3['surname'] .'</span> </a>
  					<span style="float: right;">'.$row['date'].'</span>
					&nbsp;</div>
				  <div class="panel-body">
						'.$row['message'].'
						
						<hr>
						<span style="float: right;"><form action="mesajlarim.php" method="post">
						<input type="hidden" value="'.$row3['name'].' '.$row3['surname'] .'" name="isim">
						<input type="hidden" value="'.$row['ilanNo'].'" name="ilanNo">
						<input type="hidden" value="'.$row2['title'].'" name="title">
						<input type="hidden" value="'.$row['message'].'" name="mesaj">
						<input type="hidden" value="'.$row['date'].'" name="tarih">
						<input type="hidden" value="'.$row['senderID'].'" name="sender">
						<button class="btn btn-primary" name="yanitla" type="submit">Yanıtla</button> <button data-toggle="modal" data-target="#mesajSil" class="btn btn-danger" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Sil</button></form></span>
					
				  </div>
				</div>
				
				
				
				
				
				';		
				
				
				echo '
<div id="mesajSil" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body">
        <p>Mesajı silmek istediğinize emin misiniz?</p>
      </div>
      <div class="modal-footer">
	  <form action="mesajlarim.php" method="post"> 
	  <input type="hidden" name="id" value="'.$row['id'].'">
        <button type="button" class="btn btn-default" data-dismiss="modal">Vazgeç</button>
        <button name="sil" class="btn btn-danger" type="submit">Sil</button>
		 </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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