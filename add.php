<?php require('layout/header.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); } 

//define page title
$title = 'Ücretsiz İlan Ekle';



if(isset($_POST['cat1'])){$_SESSION['cat1'] = $_POST['cat1'];}
if(isset($_POST['cat2'])){$_SESSION['cat2'] = $_POST['cat2'];}/*
if(isset($_POST['title'])){$_SESSION['title'] = $_POST['title'];}
if(isset($_POST['aciklama'])){$_SESSION['aciklama'] = $_POST['aciklama'];}
if(isset($_POST['fiyat'])){$_SESSION['fiyat'] = $_POST['fiyat'];}
if(isset($_POST['il'])){$_SESSION['il'] = $_POST['il'];}
if(isset($_POST['ilce'])){$_SESSION['ilce'] = $_POST['ilce'];}*/
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
												
												$stmt = $db->prepare("UPDATE products SET `images` = CONCAT_WS('|',images,'".$target_path_pdf."".$fileName."') WHERE  productID=".$user->lastProduct);
											$stmt->execute();
									
											}
							}

		if(isset($_POST['devam']))
		{
					
				$stmt = $db->prepare('INSERT INTO products (categoryID,memberID) VALUES (:categoryID, :memberID)');
				$stmt->execute(array(
				':categoryID' =>  $_POST['cat2'],
				':memberID' => $user->memberID
				
			));
				
			$user->setLastProduct($db->lastInsertId());
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
              <div class="form-group">   
                <?php 
			  
			  if(!isset($_SESSION['cat1']) || !isset($_SESSION['cat2'])){   
			  ?>    
                      <div class="alert alert-info">
                      
                        <h1>İlerleme Durumu</h1>
                        
                                <div class="progress">
                                  <div class="progress-bar progress-bar-striped progress-bar-danger active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 5%">
                                    <span class="sr-only">45% Complete</span>
                                  </div>
                                </div>
                      </div>
                  
             
               <form id="form1" name="form1" action="add.php" method="post"> 
               <div class="panel panel-primary">
                                   <div class="panel-heading">
                          
                                      <h4> Kategorinizi seçin </h4>
                                      
                                    </div>
             <div class="panel-body">
             
             	<div class="row">
						<?php
                        
                        $query = "SELECT * FROM categories WHERE parentID = '0'";
                    $sql = $db->prepare($query);
                     $sql->execute();
                    $data = $sql->fetchAll();
                    
                    echo'<div class="col-md-4">
                        
                        <select size="15" name="cat1" multiple class="form-control" onChange="form1.submit()">';	
                    foreach($data as $row) 
                        {
                            echo "<option "; if(isset($_POST['cat1']) && $row['categoryID']==$_POST['cat1'])echo'style="background-color:#ff0;"'; echo" value=".$row['categoryID'].">".$row['name']."</option>";
                        }
                        
                    echo'</select></div>';
                    
                if(isset($_POST['cat1'])){		
                        $query = "SELECT * FROM categories WHERE parentID = ".$_POST['cat1']."";
                    $sql = $db->prepare($query);
                     $sql->execute();
                    $data = $sql->fetchAll();
                    
                    echo'<div class="col-md-4">
                        <select size="15" name="cat2" multiple class="form-control">';	
                    foreach($data as $row) 
                        {
                            echo "<option value=".$row['categoryID'].">".$row['name']."</option>";
                        }
                        
                    echo'</select>
					</div>';	
                }
                        ?>
                 
				</div>	
                 
            </div>
            </div>
            <br />
            <div align="right">
            
            <button type="submit" name="devam" class="btn btn-success btn-lg" value="Devam ->" />DEVAM
            <span class="glyphicon glyphicon-menu-right"></span>
            </button>
            </div> 
				</form>
           <?php
			  }
			  else if(isset($_SESSION['cat1']) && isset($_SESSION['cat2']) && !isset($_POST['gonder'])){
				  
				 
		   ?>
           
          
           <div class="alert alert-info">
                      
                        <h1>İlerleme Durumu</h1>
                        
                                <div class="progress">
                                  <div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                                    <span class="sr-only">45% Complete</span>
                                  </div>
                                </div>
                      </div>
           
              <form id="upload"  method="post" action="add.php" enctype="multipart/form-data">
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
			 
				
				
				$user->log($user->memberID." bir ilan yayınladı : " . $user->lastProduct);
				
				
				$stmt = $db->prepare('UPDATE products SET  `categoryID` = :categoryID , `memberID` = :memberID,  `title` = :title,  `description` = :description,  `price` = :price,  `lastupdateDate` = :lastupdateDate,  `postDate` = :postDate, `visitCount` = :visitCount,`completed` = :completed,`il` = :il,`ilce` = :ilce WHERE productID = '.$user->lastProduct.'');
				$stmt->execute(array(
				':categoryID' =>  $_SESSION['cat2'],
				':memberID' => $user->memberID,
				':title' => $_POST['title'],
				':description' => $_POST['aciklama'],
				':price' => $_POST['price'],
				':lastupdateDate' => date("Y-m-d H:i:s"),
				':postDate' => date("Y-m-d H:i:s"),
				':visitCount' => 1,
				':completed' => 1,
				':il' => $_POST['il'],
				':ilce' => $_POST['ilce']
			));
				
				$stmt = $db->prepare('UPDATE categories SET  `productCount` = productCount + 1 WHERE categoryID = '.$_SESSION['cat2'].'');
				$stmt->execute();
				
				$stmt = $db->prepare('UPDATE categories SET  `productCount` = productCount + 1 WHERE categoryID = '.$_SESSION['cat1'].'');
				$stmt->execute();
				
				 unset($_SESSION['cat1']);
				  unset($_SESSION['cat2']);
				 
				echo'
				<div class="alert alert-info">
                      
                        <h1>İlerleme Durumu</h1>
                        
                                <div class="progress">
                                  <div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span class="sr-only">45% Complete</span>
                                  </div>
                                </div>
                 </div>
				 
				 
				 <div class="alert alert-success" role="alert"><b>Tebrikler!</b> İlanınız incelendikten sonra yayına alınacaktır.</div>
				
				';
				
				
			}
			
			  
		  
		
		   
		   ?>
           
           
               
            </div>
          
            </div>
        </div>
	
	</div>


</div>

<?php 
//include header template
require('layout/footer.php'); 
?>