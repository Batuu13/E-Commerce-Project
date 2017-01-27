<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Giden Mesajlar';

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

			 
			 
			  
			
			  
	if(!isset($_GET['mesajID'])){	  
			  
			  	require_once('classes/paginator.php');
				$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				$links = (isset($_GET['links'])) ? $_GET['links'] : 6;
				$conn       = new mysqli( 'localhost', 'batuhany_admin', 'batu9595..', 'batuhany_ikinciel' );
				 $query = "SELECT * FROM mesajlar WHERE senderID = ".$user->memberID."";
				 $paginator = new Paginator($conn ,$query);
             
			 		$results = $paginator->getData($limit,$page);
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
					
					  $stmt = $db->prepare("SELECT name,surname FROM members WHERE memberID =".$row['recieverID']." "); 
					$stmt->execute(); 
					$row2 = $stmt->fetch();
					
					$read = ($row['read'] == 0) ? "warning" : "";
				echo'<tr >
							 
							  <th scope="row">
								<b>'.$i.' </b>
							  </th>
						 
						 		<td>
								<a href="profil.php?id='.$row['recieverID'].'">	'.$row2['name'].' '.$row2['surname'] .'</a>
								</td>
							  	<td>
								<a href="gidenmesajlarim.php?mesajID='.$row['id'].'">	' .$row['title']. ' </a>
								</td>
								<td>
									'.$row['date'].'
									
									
								</td>
							
						 
					</tr>';
				}
				echo'</tbody></table>';
	}
	if(isset($_GET['mesajID'])){
			
			 $stmt = $db->prepare("SELECT * FROM mesajlar WHERE id =".$_GET['mesajID']." "); 
			 $stmt->execute(); 
			 $row = $stmt->fetch();
			 
			 $stmt = $db->prepare("SELECT * FROM products WHERE productID =".$row['ilanNo']." "); 
			 $stmt->execute(); 
			 $row2 = $stmt->fetch();
			 
			 $stmt = $db->prepare("SELECT name,surname FROM members WHERE memberID =".$row['recieverID']." "); 
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
						
						
					
				  </div>
				</div>
				
				
				
				
				
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