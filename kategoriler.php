<?php require('layout/header.php'); 

//if not logged in redirect to login page


//define page title
$title = 'Arama Yap';

//include header template

if(isset($_POST['kat']) ){
	
		$_SESSION['kat'] = $_POST['kat'];
	}
	else if(isset($_GET['katID']) ){
	
		$_SESSION['kat'] = $_GET['katID'];
	}			 
if(isset($_POST['kelime'])){
	
		$_SESSION['kelime'] = $_POST['kelime'];
	}


if(isset($_POST['delkat']) && !isset($_GET['page']))
{
	unset($_SESSION['kat']);
	$_POST['ara'] = true;
}
	if(isset($_POST['delkel']) && !isset($_GET['page']))
{
	$_SESSION['kelime'] ="";
		
}
	
?><script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="style/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" href="style/single.css">

 <div id="Content" class="clearfix">
  <form id="form1" name="form1" action="arama" method="post">
          <div id="Kategoriler" style="border:0px;"  class="clearfix">
         
               <div  class="panel panel-primary" >
              		 <div class="panel-heading">
                     	 Kategoriler
                     </div>
              		<div class="panel-body"  >
                    <?php
						
				
				$stmt = $db->prepare("SELECT productID,images,title,postDate,il,ilce,price,categoryID FROM products WHERE $construct"); 
				$stmt->execute(); 
				$rowForKat = $stmt->fetchall();
					
				 	foreach($rowForKat as $rowForKat){
												
													 echo'   
													 
														  <button type="submit" value="'.$rowForKat['categoryID'].'" name="kat" class="btnKate btn-block btn-white">'.$rowForKat['name'].'</button> 
														 
														  ';
												
										}
												
					?>
                    
                               
                	 </div>
                 </div>   
                  
             <div id="ekstra">
             	<div class="row">
                	 <div class="col-lg-12">
                            <font color="#A6A6A6">
                         <?php if(isset($row)){	echo 'Toplamda <b>'.count($row). '</b> sonuç bulundu.';}?>
                         
                            
                            </font>
               		</div> 
                    
                   
             </div>   
		</div> 
          </form>  
            <div id="Icerik" style="padding:0px; background-color:transparent; margin-top:25px; border:0px;"  class="clearfix">
               <?php


	
	
		

	if(isset($row) && count($row) != 0){
	
		
		echo'   <div class="panel panel-info">
                <table  class="table table-striped">
                 <thead>
                    <tr class="info">
                       <th  style="text-align:center;vertical-align:middle;">RESİM</th>
                      <th  style="text-align:center;vertical-align:middle;">BAŞLIK</th>
                      <th  style="text-align:center;vertical-align:middle;">FİYAT</th>
                      <th  style="text-align:center;vertical-align:middle;">TARİH</th>
                      <th  style="text-align:center;vertical-align:middle;">ADRES</th>
                    </tr>
                  </thead>
                    <tbody>';
                  
				
						foreach($row as $ilan)
						{
								
								
									$stmt = $db->prepare("SELECT il_adi FROM il WHERE il_kodu =".$ilan['il'].""); 
								$stmt->execute(); 
								$il= $stmt->fetch();
								
								$stmt = $db->prepare("SELECT ilce_adi FROM ilce WHERE ilce_kodu =".$ilan['ilce'].""); 
								$stmt->execute(); 
								$ilce = $stmt->fetch();
								
								$resim = explode("|",$ilan['images']);
								$date = explode(" ",$ilan['postDate']);
								
								?> <tr   bgcolor="#FFFFFF"  onmouseover="this.className='warning'" onmouseout="this.className=''"  onclick="document.location = 'ilan/<?php echo $ilan['productID']?>';">
									<?php echo'	 
										<th  style="text-align:center;vertical-align:middle;cursor:pointer;"><img src="'.$resim[0].'" height="45" width="65"></th>
										<th  style="text-align:center;vertical-align:middle;cursor:pointer;">'.$ilan['title'].'</th>
										<th  style="text-align:center;vertical-align:middle;cursor:pointer;">'.$ilan['price'].'</th>
										<th  style="text-align:center;vertical-align:middle;cursor:pointer;">'.$date[0].'<br>'.$date[1].'</th>
										<th  style="text-align:center;vertical-align:middle;cursor:pointer;">'.$il['il_adi'].'<br>'.$ilce['ilce_adi'].'</th>
								</tr>
								';
							}
							echo'<tbody>
                </table>
              </div>';
			  /// PAGINATION 
					$previous = (isset($_GET['page']) && $_GET['page'] != 0) ? $_GET['page']-1 : "#";
			echo'
						<nav align="center">
						  <ul class="pagination">
							<li>
							  <a href="arama.php?page='.$previous.'" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
							  </a>
							</li>
			';	
			for($k = 0; $k< ($toplam / $limit);$k++)
			{
					echo'		<li><a href="arama.php?page='.$k.'" onclick="form1.submit();">'.($k+1).'</a></li>';
			}
					$next = (isset($_GET['page']) && ((($_GET['page']+1) * $limit) <= $toplam)) ? $_GET['page']+1 : "#";
			echo'			<li>
							  <a href="arama.php?page='.$next.'" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
							  </a>
							</li>
						  </ul>
						</nav>
			
			';
		 /// PAGINATION 
	}
else if(isset($row) && count($row) == 0){
			echo'
			
			<div class="alert alert-danger" role="alert">Hiç bir Sonuç Bulunamadı.</div>
			
			';
			
			
			
			}
		else {
			echo'
			
			<div class="alert alert-danger" role="alert">Arama yapabilmek için önce yan taraftan bi kelime girin.</div>
			
			';
			
			
			
			}

					?>
                    
       		</div>
	
<?php }

catch(Exception $e){
	echo "Bir Hata Oluştu:" . $e;
	}?>

</div>

<?php 
//include header template
require('layout/footer.php'); 
?>