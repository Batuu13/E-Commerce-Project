<?php require('layout/header.php'); 




$stmt = $db->prepare("SELECT * FROM products WHERE productID =".$_GET['id']." "); 
$stmt->execute(); 
$row = $stmt->fetch();



if($user->memberID != $row['memberID'] && !isset($_POST['count'])){
	// UPDATE VISIT COUNT
	$stmt = $db->prepare("UPDATE products SET visitCount = visitCount + 1 WHERE  productID=".$_GET['id']." ");
	$stmt->execute();
}


$stmt = $db->prepare("SELECT * FROM members WHERE memberID =".$row['memberID']." "); 
$stmt->execute(); 
$sender = $stmt->fetch();

$title = $row['title'];
/// RESİM BÖL 

$resimler = explode("|",$row['images']);


/// RESİM BÖL


?>
<title><?php if(isset($title)){ echo $title; }?></title>

<link rel="stylesheet" href="style/product.css">
 <div id="Content" class=" clearfix">
 	<div class="panel panel-default">
            <div  class="panel-heading clearfix">
          <font style="font-size:18px;">  <b><?php echo $row['title']; ?> </b></font>
            </div>
           <div style="padding:5px;" class="panel-body">
             <div id="Resim"  class="clearfix">
                    
                          <?php 
                         if(count($row['images']) > 0)
						  {       
                            if(isset($_POST['resimDegis']))
                            {
                              
                                echo '<div><img class="img-rounded" height="83%" width="100%" src="'.$resimler[$_POST['resimNo']].'"></div>'; 
                            }
                            else{
                            
                            	echo '<div><img  class="img-rounded"  height="83%" width="100%" src="'. $resimler[0].'"></div>'; 
                            }
                         }
						 else{
							 
							 echo '<div><img  class="img-rounded" height="83%" width="100%" src="images/default.jpg"></div>'; 
							 
							 }   
                            
                            ?>
                            
            <div id="DigerResimler" class="clearfix">
                    
                    <?php   if(count($row['images']) > 0)
						  {   
						   ?>
                                <div class="panel panel-primary">
                                    <form action="ilangoster.php?id=<?php echo $_GET['id']; ?>" method="post" >
                                    <input type="hidden" name="count" value="1"> 
                                    <input type="hidden" name="resimDegis" value="1">
                                     <div class="row">
                                    <?php 
                                 
                                   
                                   
                                        for($i = 0;$i< count($resimler);$i++)
                                        {
                                       
                                        echo '<div style="position: relative; float: left;width: 60px; margin-left:15px;">
											<button class="img-thumbnail"  style="background-image:url('. $resimler[$i].');background-size: 62px 62px;height: 62px; width: 62px;background-repeat:no-repeat;margin:3px;"  type="image" 
                                         value="'.$i.'" name="resimNo" ></button></div>
										';
                                        
                                        
                                        }
                                  
                                    ?>
                                     </div>
                                    </form>
                                </div>
                        <?php 
						 }else{
						  	echo' 
									<p class="bg-primary" style="padding:8px;"> <span class="glyphicon glyphicon-info-sign" style="font-size:12px;"></span>  Henüz Bir Resim Koyulmamış!</p>
									';
									
									
						 }
						?>
                     </div>
            </div>
             
                    
                         
                                <div  id="Özellikler" class="clearfix">
                               
                             <ul class="list-group">
                             <?php
                             
                                $stmt = $db->prepare("SELECT il_adi FROM il WHERE il_kodu =".$row['il']." "); 
                                $stmt->execute(); 
                                $il = $stmt->fetch();
                                
                                $stmt = $db->prepare("SELECT ilce_adi FROM ilce WHERE ilce_kodu =".$row['ilce']." "); 
                                $stmt->execute(); 
                                $ilce = $stmt->fetch();
                                $adres = explode(" ",$row['postDate']);
                               echo'   <li class="list-group-item active"><b> Özellikler </b></li>
                                       <li style="padding:2px;text-align:center;" class="list-group-item">'.$il['il_adi'].' / '.$ilce['ilce_adi'].'</li>
                                       <li style="padding:5px;" class="list-group-item">İlan Kodu : <span style="float:right; color:#f00;">'.$row['productID'].'</span></li>
                                       <li style="padding:5px;" class="list-group-item">İlan Tarihi : <span style="float:right;">'.$adres[0].'</span></li>
                                       <li style="padding:5px;" class="list-group-item"><b>Fiyat : </b><span style="float:right;"><b>'.$row['price'].'</b></span></li>
                                       <li style="padding:5px;" class="list-group-item">Görüntülenme Sayısı : <span style="float:right;">'.$row['visitCount'].'</span></li>
                        ';
                            ?>     
                            
                                </ul>
                                
                                </div>
                        
                            <div id="Özellikler1" class="clearfix">
                            
                               		<div class="panel panel-danger clearfix">
                                      <div class="panel-heading"><b> Kullanıcı Bilgileri </b></div>
                                                      <div style="padding:0px; " class="panel-body">
                                                      <li style="border:0px; font-size:16px; text-align:center;border-bottom:2px; border-bottom-style:dotted; border-bottom-color:#CCC;" class="list-group-item">
													  <?php echo "<b>".$sender['name'] . " ". $sender['surname']."</b>" ; ?></li>
                                                      <?php
                                                      if($sender['showTel'] == 1)  
                                                      {
                                                      echo'<li style="border:0px; font-size:16px; text-align:center;border-bottom:2px; border-bottom-style:dotted;border-bottom-color:#CCC;" class="list-group-item"><b>Telefon : '.
													  $sender['telno'].'</b></li>	';									                          }
                                                      ?><li style="border:0px; font-size:16px; text-align:center; border-bottom:2px; border-bottom-style:dotted;border-bottom-color:#CCC;" class="list-group-item">
													  <?php $sender['registerDate'] ?></li>
                                                      <li style="border:0px; font-size:16px; text-align:center;border-bottom-style:dotted;border-bottom-color:#CCC;" class="list-group-item"><?php 
                                                     
                                                     
                                                     
                                                     echo' 
                                                     <div class="row">
                                                     <div class="col-md-6">
                                                     <a href="sikayet.php?id='.$row['productID'].'"><button class="btn btn-danger" name="yanitla" type="button">Şikayet Et</button></a></div>
                                                     <div class="col-md-6"> <form action="mesajlarim.php" method="post">
                                                    <input type="hidden" value="'.$sender['name'].' '.$sender['surname'] .'" name="isim">
                                                    <input type="hidden" value="'.$row['productID'].'" name="ilanNo">
                                                    <input type="hidden" value="'.$row['title'].'" name="title">
                                                    <input type="hidden" value="" name="date">
                                                    <input type="hidden" value="" name="mesaj">
                                                    <input type="hidden" value="'.$sender['memberID'].'" name="sender">
                                                    <button class="btn btn-primary" name="yanitla" type="submit">Mesaj At</button></form></div></div> ';
                                                      
                                                      
                                                      ?></li>
                                                     
                                                      </div>
                       			 </div>
                            
                           </div>
                            
                </div>                      
         </div>
         <div id="Icerik" class="panel panel-default clearfix">
                                    
                                     <div  class="panel-heading clearfix">
                                     <font style="font-size:16px;">  <b> AÇIKLAMA </b></font>
                                     </div> 
                                     <div class="panel-body">
                                		<?php echo $row['description']; ?>
                                     </div>
                                 </div>
   </div>
 <?php require('layout/footer.php'); 


?>