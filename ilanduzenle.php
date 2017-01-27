<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'İlan Düzenle';



					//include header template
					
					$allowed = array('png', 'jpg', 'gif','zip');
					
							if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
							
								$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
							
								if(!in_array(strtolower($extension), $allowed)){
									echo '{"status":"error"}';
									exit;
								}
								
								$target_path_pdf ="uploads/".$user->username."/"; // random folder name
											
											if(!is_dir($target_path_pdf)){
											 mkdir($target_path_pdf);
											}
								
									
											$fileName= rand(0,1000)."-".$_FILES['upl']['name'];
											
											if(move_uploaded_file($_FILES['upl']['tmp_name'],$target_path_pdf."".$fileName)){
												echo '{"status":"success"}';
												
												
												$stmt = $db->prepare("UPDATE products SET `images` = CASE WHEN ISNULL(images) THEN '".$target_path_pdf."".$fileName."' ELSE CONCAT_WS('|',images,'".$target_path_pdf."".$fileName."') END WHERE  productID=".$_POST['ilanID']);
												$stmt->execute();
												
												$stmt = $db->prepare("SELECT images FROM products WHERE productID =".$_POST['ilanID']." "); 
												$stmt->execute(); 
												$row2 = $stmt->fetch();
												$_POST['images'] = $row2['images'];
											}
							}
	if(isset($_POST['sil']))
	{
			$exp = explode("|",$_POST['images']);
			
			 $ilk = false;
			$new = "";
			
			if (file_exists($_POST['sil'])) {
					unlink($_POST['sil']);
				} else {
					echo "Dosya Bulunamadı";
				}	
			
		if(count($exp)>1)
		{
				
		
			for($i = 0; $i< count($exp);$i++)
			{
				
			
				
				if($_POST['sil'] == $exp[$i] && $i == 0){
					$ilk = true;
				}
				
				  
				
				
				else if($_POST['sil'] != $exp[$i]){
					
						if($ilk || $i == 0){
							$new .= $exp[$i];
							$ilk = false;
						}
						else {
							$new .= "|".$exp[$i];
							
						}
					}
					
			}
		}
		if($new == "")
			{
				$stmt = $db->prepare('UPDATE products SET  `images` = NULL WHERE productID = '.$_POST['ilanID'].'');
			$stmt->execute();	
			}
			else{
			$stmt = $db->prepare('UPDATE products SET  `images` = "'.$new.'" WHERE productID = '.$_POST['ilanID'].'');
			$stmt->execute();	
			}
			$_POST['images'] = $new;
	}
							
	if(!isset($_POST['gonder']) && isset($_POST['ilanID'])){
	$stmt = $db->prepare("SELECT * FROM products WHERE productID =".$_POST['ilanID']." "); 
	$stmt->execute(); 
	$ilan = $stmt->fetch();
	
	
	$_POST['title'] = $ilan['title'];
	$_POST['aciklama'] = $ilan['description'];
	$_POST['il'] = $ilan['il'];
	$_POST['ilce'] = $ilan['ilce'];
	$_POST['price'] = $ilan['price'];
	$_POST['images'] = $ilan['images'];
	}
	
	

?>
	<link href="assets/css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="style/blankpage.css">
   
		<!-- JavaScript Includes -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="assets/js/jquery.ui.widget.js"></script>
		<script src="assets/js/jquery.iframe-transport.js"></script>
		<script src="assets/js/jquery.fileupload.js"></script>
		
		<!-- Our main JS file -->
		<script src="assets/js/script.js"></script>

                  
<div class="container">
 <div id="Content" class="clearfix">
        
            <div id="Icerik" class="clearfix">
          <?php if(!isset($_POST['gonder'])){?>
          
          		<div class="form-group">  
           
              <form id="upload"  method="post" action="ilanduzenle.php" enctype="multipart/form-data">
              	<input type="hidden" name="ilanID" <?php if(isset($_POST['ilanID']))echo'value="'.$_POST['ilanID'].'" ';?> />
                <input type="hidden" name="images" <?php if(isset($_POST['images']))echo'value="'.$_POST['images'].'" ';?> />
                           <div class="panel panel-primary">
                                   <div class="panel-heading">
                          
                                      <h4> İlan Detaylarını Girin </h4>
                                      
                                    </div>
                                             <div class="panel-body">
                                       
                                                <div style="margin-bottom:10px;" class="row">
                                                    <div style=" padding-top:5px;" class="col-md-2">
                                                          <label>BAŞLIK</label>
                                                     </div>
                                                     <div class="col-md-8">
                                                          <input type="text" name="title" <?php if(isset($_POST['title']))echo'value="'.$_POST['title'].'" ';?>  class="form-control" placeholder="Başlık">
                                                     </div>
                                                </div>  
                                            
                                            <div style="margin-bottom:10px;" class="row">
                                                <div style=" padding-top:5px;" class="col-md-2">   
                                                      <label>AÇIKLAMA</label>
                                                 </div>
                                                 <div class="col-md-8">
                                                      <textarea  rows="10"  class="form-control" name="aciklama"><?php if(isset($_POST['aciklama']))
													  echo $_POST['aciklama'];?></textarea>
                                                 </div>
                                            </div>     
                                            
                                            
                                                      
                                                  <div style="margin-bottom:10px;" class="row">
                                                      <div style=" padding-top:5px;" class="col-md-2">
                                                        <label>Fiyat</label>
                                                      </div>
                                                      <div class="col-md-3">
                                                          <div class="input-group">
                                                              <input  type="number" name="price" <?php if(isset($_POST['price']))echo'value="'.$_POST['price'].'" ';?>  class="form-control" placeholder="0">
                                                              <span class="input-group-addon">TL</span>
                                                          </div>
                                                       </div>
                                                  </div> 
                                                  <div style="margin-bottom:10px;" class="row">
                                                      <div style=" padding-top:5px;" class="col-md-2">
                                                        <label>İl</label>
                                                      </div> 
                                                       <div class="col-md-3">
                                                                <select onChange="upload.submit()" name="il" <?php if(isset($_POST['il']))echo'value="'.$_POST['il'].'" ';?> class="form-control">
                                                                <?php
																 $query = "SELECT * FROM il";
																	$sql = $db->prepare($query);
																	 $sql->execute();
																	$data = $sql->fetchAll();
																   
																	foreach($data as $row) 
																		{
																			echo '<option value="'.$row['il_kodu'].'"'; 
																			if(isset($_POST['il']) && $_POST['il']==$row['il_kodu']) 
																				echo 'selected';
																			echo'>'.$row['il_adi'].'</option>';
																		}
																?>
                                                                </select>
                                                        </div>
                                                  </div> 
                                             		  <div style="margin-bottom:10px;" class="row">
                                                      <div style=" padding-top:5px;" class="col-md-2">
                                                        <label>İlçe</label>
                                                      </div> 
                                                       <div class="col-md-3">
                                                                <select name="ilce" <?php if(isset($_POST['ilce']))echo'value="'.$_POST['ilce'].'" ';?>  class="form-control">
                                                                <?php
																if(isset($_POST['il'])){
																 $query = "SELECT * FROM ilce WHERE il_kodu =".$_POST['il']."";
																	$sql = $db->prepare($query);
																	 $sql->execute();
																	$data = $sql->fetchAll();
																   
																	foreach($data as $row) 
																		{
																			echo "<option value=".$row['ilce_kodu'].">".$row['ilce_adi']."</option>";
																		}
																}
																?>
                                                                </select>
                                                        </div>
                                                  </div> 
                                       </div>
                           
					</div>
                    <div class="panel panel-primary">
                               <div class="panel-heading">
                      
                                  <h4>Şuanki Fotoğraflarınız</h4>
                                  
                                </div>  
                            	
                           <div class="panel-body" >
                           	<div class="row">
                           <?php  if($_POST['images'] != NULL){
						   			$resimler = explode("|",$_POST['images']);
						  
						   			foreach($resimler as $resimler){
										
										echo'
											  <div align="center"  class="col-xs-6 col-md-3">
											 
												<a style="height:180px;width:180px;" data-container="body" data-toggle="popover" data-placement="top" data-content="asdasd" class="thumbnail">
												  <img style="height:170px;width:180px;" src="'.$resimler.'"  >
												</a>
												<button name="sil" value="'.$resimler.'" style="font-size:20px; margin-top:-10px;" class="text-danger"><b>Sil</b><span style="font-size:18px; margin-top:0px;" class="glyphicon glyphicon-remove"></span></button>
											  </div>
											  
											
										';
										
										}
						   }
						   else{
							   echo'	 <div align="center"  class="col-xs-6 col-md-3"><a style="height:180px;width:180px;" data-container="body" data-toggle="popover" data-placement="top" data-content="asdasd" class="thumbnail">
												  <img style="height:170px;width:180px;" src="images/default.jpg"  >
												</a></div>
							   	';
							   
							   }
						    ?>
                           </div>
                           
                        </div>
                      </div>
                            <div class="panel panel-primary">
                               <div class="panel-heading">
                      
                                  <h4>Fotoğraf Ekleyin </h4>
                                  
                                </div>        
                           <div class="panel-body" style="padding:0px;">
                           
              	
 <div id="upload1">
			<div id="drop">
				Buraya Sürükle<br />

				<a>Dosya Ekle</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

	 </div>        
                           
                           
                           
      </div>                     </div>
                           
                         
            
                 <div align="right">      
                  <input type="submit" name="gonder" class="btn btn-lg btn-primary" value="Gönder"/>
                  </div>
               </form>   
            
                
        
           <?php
		  }
		    
		   if(isset($_POST['gonder'])){
			 
				
				
				$user->log("bir ilan düzenledi : " . $_POST['title']. "  -  " .$_POST['aciklama']);
				
				
				$stmt = $db->prepare('UPDATE products SET  `title` = :title,  `description` = :description,  `price` = :price,  `lastupdateDate` = :lastupdateDate, `il` = :il,`ilce` = :ilce WHERE productID = '.$_POST['ilanID'].'');
				$stmt->execute(array(
				':title' => $_POST['title'],
				':description' => $_POST['aciklama'],
				':price' => $_POST['price'],
				':lastupdateDate' => date("Y-m-d H:i:s"),
				':il' => $_POST['il'],
				':ilce' => $_POST['ilce']
			));
				
		
				 
				echo'
			
				 
				 <div class="alert alert-success" role="alert"><b>Tebrikler!</b>İlanınızı güncellediniz. Kontrol edildikten sonra yayına alınacaktır.<br> <a href="ilanlarim.php">Geri dön.</a></div>
				
				';
				
				
			}
			
			  
		  
		
		   
		   ?>
           
           
               
           
          
            </div>
        </div>
	
	</div>

</div>

<?php 
//include header template
require('layout/footer.php'); 
?>