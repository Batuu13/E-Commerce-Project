<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Bildirimler';
$active = 3;
//include header template


?>
<link rel="stylesheet" href="style/single.css">

 <div id="Content" class="clearfix">
         <?php
		   include("profilkate.php"); // INCLUDING CATEGORIES FOR PROFILE
		   ?>
            <div id="Icerik" class="clearfix">
                
              
                <?php
				
				
				if(!isset($_GET['bildirimID'])){	  
			  
			  	require_once('classes/paginator.php');
				$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				$links = (isset($_GET['links'])) ? $_GET['links'] : 6;
				$conn       = new mysqli( 'localhost', 'batuhany_admin', 'batu9595..', 'batuhany_ikinciel' );
				 $query = "SELECT * FROM notification WHERE recieverID = ".$user->memberID."";
				 $paginator = new Paginator($conn ,$query);
             
			 		$results = $paginator->getData($limit,$page);
					
					
					if($results->total == 0)
			{
				echo'<div class="alert alert-info" role="alert">Yeni bir bildiriminiz yok.</div>';
			}
			
				$stmt = $db->prepare('UPDATE members SET  `notification` = 0 WHERE memberID = '.$user->memberID.'');
					$stmt->execute();
					
				
				echo $paginator->createLinks($links, 'pagination pagination-sm');
				
				echo'<table class="table table-hover">';
				$i = 1;
				echo'
					 <thead>
					<tr>
					  
					 
					
					  
					</tr>
					
				  </thead>
				
				
				<tbody>';
							foreach($results->data as $row){
								
							switch($row['type'])
							{
								case 0:
								$type = "";
								break;	
								case 1:
								$type = "mesajlarim.php";
								break;
								case 2:
								$type = "ilanlarim.php";
								break;
														
							}
								$read = ($row['active'] == 0) ? "warning" : "";
							echo'<tr >
										 
										  <th scope="row">
											<a href="'.$type.'"><b>'.$row['message'].' </b></a>
										  </th>
									 
										
									 
								</tr>';
							}
				echo'</tbody></table>';
			
	}
				
				
				 
				?>
			
				
				
            </div>
        </div>
	
	


</div>

<?php 
//include header template
require('layout/footer.php'); 
?>