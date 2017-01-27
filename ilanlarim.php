<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'İlanlarım';
$active = 4;
//include header template


?>

<link rel="stylesheet" href="style/single.css">

 <div id="Content" class="clearfix">
           <?php
		   include("profilkate.php"); // INCLUDING CATEGORIES FOR PROFILE
		   ?>
            <div id="Icerik" class="clearfix">
                
              <H4><u>İlanlar</u></H4>
              
              <?php
			  
			  	 if(isset($_POST['sil'])){
				
					$stmt = $db->prepare("SELECT title,categoryID FROM products WHERE productID =".$_POST['id']." "); 
					$stmt->execute(); 
					$row = $stmt->fetch();
				
					 $stmt = $db->prepare("SELECT parentID FROM categories WHERE categoryID =".$row['categoryID']." "); 
					$stmt->execute(); 
					$cat = $stmt->fetch();
				
					$stmt = $db->prepare('UPDATE categories SET  `productCount` = productCount - 1 WHERE categoryID = '.$row['categoryID'].'');
					$stmt->execute();
				
					$stmt = $db->prepare('UPDATE categories SET  `productCount` = productCount - 1 WHERE categoryID = '.$cat['parentID'].'');
					$stmt->execute();
				
					  
					
					$user->log(" bir ilan sildi :" . $row['title']); // UPDATE LOG
				
					$sql = "DELETE FROM products WHERE productID =  :mesajID";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':mesajID', $_POST['id'], PDO::PARAM_INT);   
					$stmt->execute();  
					
					
					
					
					
					echo'<div class="alert alert-success" role="alert"> İlanı başarıyla sildiniz.</div>';
					
					
					
					 
					
			  }
			  
			  
			  
			  	require_once('classes/paginator.php');
				$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				$links = (isset($_GET['links'])) ? $_GET['links'] : 6;
				$conn       = new mysqli( 'localhost', 'admin', '123321', 'batuhany_ikinciel' );
				 $query = "SELECT * FROM products WHERE memberID = ".$user->memberID."";
				 $paginator = new Paginator($conn ,$query);
             
			 		$results = $paginator->getData($limit,$page);
				echo $paginator->createLinks($links, 'pagination pagination-sm','ilanlarim');
				foreach($results->data as $row)
				{
							$color = "default";
							$durum = "Yayında değil.";
							if($row['active'] == 1)
							{
								$color = "success";	
								$durum = "Yayında";
							}
						echo'<div class="panel panel-'.$color.'">
									  <div class="panel-heading">
										<b><a href="ilangoster.php?id='.$row['productID'].'">'.$row['title'].' </a></b>
										<span style="float: right;"><b>'.$durum.'</b></span>
										
									  </div>
								  <div style="padding:8px;" class="panel-body">
								  <div class="row">
									  <div class="col-md-9">';
									  
									
									if (strlen($row['description']) > 125) {
									
										//  string böl
										$stringCut = substr($row['description'], 0, 125);
									
										echo $stringCut . "...";
										
									}
											else{	
												echo'<i>'.$row['description'].'  </i>';}
												echo'
									   </div>
									   <div class="col-md-3">
									   <form id="duzenle" action="ilanduzenle.php" method="post">
									   <input type="hidden" value="'.$row['productID'].'" name="ilanID">
											<a style="color:#337ab7;"  href="javascript:;" onclick="parentNode.submit();">Düzenle</a> | <a style="color:#337ab7;" data-toggle="modal" href="#ilanSil" >İlanı Sil</a> 
										</form>
									   </div>
								  </div>
										<div class="row">
											<div  class="col-md-4">
												Fiyat : '.$row['price'].'  
											</div>
											<div  title="Görüntülenme Sayısı" class="col-md-5">
												<span  style=" font-size:16px;color:#f00;" class="glyphicon glyphicon-eye-open"></span> <b> '.$row['visitCount'].'</b>
											</div>
											<div class="col-md-3">
												<span  title="Yayınlanma Tarihi" style="font-size:16px;color:#f00;" class="glyphicon glyphicon-calendar"></span> '.$row['postDate'].'
											</div>
										</div>
								  </div>
							</div>';
							
							
							
				echo '
					<div id="ilanSil" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  
						  <div class="modal-body">
							<p>İlanı silmek istediğinize emin misiniz?</p>
						  </div>
						  <div class="modal-footer">
						  <form action="ilanlarim.php" method="post"> 
						  <input type="hidden" name="id" value="'.$row['productID'].'">
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