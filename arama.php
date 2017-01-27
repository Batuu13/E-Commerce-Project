<?php require('layout/header.php'); 

//if not logged in redirect to login page


//define page title
$title = 'Arama Yap';

//include header template

if(isset($_POST['kat']) ){
	
	
		$_SESSION['kat'] = $_POST['kat'];
	}
	else if(isset($_GET['katID']) ){
	
		$stmt = $db->prepare("SELECT categoryID FROM categories WHERE link_adresi = ?"); 
				$stmt->execute(array($_GET['katID'])); 
				$kategori = $stmt->fetch();

	
		$_SESSION['kat'] = $kategori['categoryID'];
	}			 
if(isset($_POST['kelime'])){
	
	$_POST['kelime'] = str_replace("'","",$_POST['kelime']);
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
         
           <div  class="panel panel-primary" style="border:0px;">
          <button style="width:100%;" class="btn btn-lg btn-success" name="ara" type="submit">ARA <span style="font-size:15px;" class="glyphicon glyphicon-search"></span></button><br />
          </div>
               <div  class="panel panel-primary" >
              		 <div class="panel-heading">
                     	 Kelime Filtreleme
                     </div>
              		<div class="panel-body"  >
                    
                    
                     		
                                
                                    <input type="text" name="kelime" value="<?php   if(isset($_SESSION['kelime'])){echo $_SESSION['kelime'];}?>" class="form-control" placeholder="Bir kelime girin...">
                               
                	 </div>
                 </div>    <?php   
				 try{
					 






          	if(isset($_POST['ara']) || isset($_SESSION['kat']) || isset($_GET['page']))
{
	
	
	
	
	
	
				
				$arama = explode(" ",mb_strtolower($_SESSION['kelime'],'UTF-8'));
				$construct=" ";
				$x = "";

	
		
					foreach($arama as $keyword)
					{
						$x++;
						if($x==1)
							$construct .="LOWER(title) LIKE '%$keyword%' ";
						else
							$construct .="AND LOWER(title) LIKE '%$keyword%'";
					
					}
			
				if(isset($_SESSION['kat']))
				{
					
					$construct .=" AND categoryID = ".$_SESSION['kat'];
				}
	
				if(isset($_POST['fiyat2']) && isset($_POST['fiyat1']))
				{
					if($_POST['fiyat1'] == "" || $_POST['fiyat1'] < 0)
					{
						$_POST['fiyat1']= 0;
					}
					if($_POST['fiyat2'] == "" || $_POST['fiyat2'] < 0)
					{
						$_POST['fiyat2']= 0;
					}
					
						if($_POST['fiyat2'] != 0 || $_POST['fiyat1'] != 0)
						{
							$construct .=" AND price BETWEEN ".$_POST['fiyat1']." AND ".$_POST['fiyat2'];
						}
						
				}
		
				if(isset($_POST['il']) && $_POST['il'] != "Hepsi")
				{
					
					$construct .=" AND il = ".$_POST['il'];
				}
		
				$page = $db->prepare("SELECT count(productID) AS total FROM products WHERE ?"); 
				$page->execute(array($construct));
      			$toplam = $page->fetch(PDO::FETCH_COLUMN);
				$limit = 10;
				$offset = (isset($_GET['page'])) ? $_GET['page'] * $limit : 0;
				
				
				$stmt = $db->prepare("SELECT productID,images,title,postDate,il,ilce,price,categoryID FROM products WHERE $construct LIMIT $limit OFFSET $offset"); 
				$stmt->execute(); 
				$row = $stmt->fetchall();
				
				$stmt = $db->prepare("SELECT productID,images,title,postDate,il,ilce,price,categoryID FROM products WHERE $construct"); 
				$stmt->execute(); 
				$rowForKat = $stmt->fetchall();
				
				$kategori = array();
				foreach($rowForKat as $foo)
				{
					$stmt = $db->prepare("SELECT name,categoryID FROM categories WHERE categoryID = ?"); 
					$stmt->execute(array($foo['categoryID'])); 
					$temp = $stmt->fetch();
					$ekle = 1;
					
						
							 for($i =0; $i < count($kategori);$i++)
								 {
									 
									 
									 if($kategori[$i]['name'] == $temp['name'])
									 {
										$ekle = 0;
										$kategoriCount[$i]++;	 
									 }
									
								 }
					if($ekle)
						{
							$kategori[] = $temp;
							$kategoriCount[count($kategori)-1] = 1;
						}
				
				}
}	
	if((!isset($_SESSION['kat']) && isset($_POST['ara'])) || (isset($_GET['page']) && !isset($_SESSION['kat'])))
	{
	?>      
                  <div  class="panel panel-primary" style="padding:0px;" >
              		 <div class="panel-heading">
                                            Kategoriler
                                            </div>
                       		
                                         
                                            <?php 
											$a = 0;
											foreach($kategori as $foo1){
												
													 echo'   
													 
														  <button type="submit" value="'.$foo1['categoryID'].'" name="kat" class="btnKate btn-block btn-white">'.$foo1['name'].' ( '.$kategoriCount[$a++].' )</button> 
														 
														  ';
												
										}
											?>
                                           
                               
                        
                	 
                 </div>  
             <?php }?>        
                    <div  class="panel panel-primary" >
                     <div class="panel-heading">
                     	 Diğer Özellikler
                     </div>
                        <div class="panel-body"  >
                                   
                                 <label>FİYAT ARALIĞI</label>
									<div class="row" aria-describedby="helpBlock">
                                          <div class="col-xs-6">
                                            <input <?php if(isset($_POST['fiyat1']) && $_POST['fiyat1'] != 0){ echo 'style="background-color:#F9F193;"';} ?> value="<?php if(isset($_POST['fiyat1'])){echo $_POST['fiyat1'];}else{echo 0;} ?>" name="fiyat1" type="number" class="form-control" >
                                          </div>
                                         
                                          <div class="col-xs-6">
                                            <input <?php if(isset($_POST['fiyat2']) && $_POST['fiyat2'] != 0){ echo 'style="background-color:#F9F193;"';} ?> value="<?php if(isset($_POST['fiyat2'])){echo $_POST['fiyat2'];}else{echo 0;} ?>" name="fiyat2" type="number" class="form-control" >
                                          </div>
                                   </div>
                               
                               <label style="margin-top:6px;">ADRES</label>
                               <select <?php if(isset($_POST['il']) && $_POST['il'] != "Hepsi"){ echo 'style="background-color:#F9F193;"';} ?> name="il" class="form-control">
                               <option>Hepsi</option>
                               <?php
                               $stmt = $db->prepare("SELECT il_adi,il_kodu FROM il"); 
								$stmt->execute(); 
								$iller = $stmt->fetchall();
								
									foreach($iller as $iller)
									{
											echo '<option value="'.$iller['il_kodu'].'"'; 
																			if(isset($_POST['il']) && $_POST['il']==$iller['il_kodu']) 
																				echo 'selected';
																			echo'>'.$iller['il_adi'].'</option>';
									}
							   
							   ?>  
                                 
                                </select>
                               
                                   
                         </div>
                 </div>      
                        
                    
                        
          </div>
    
             <div id="ekstra">
             	<div class="row">
                	 <div class="col-lg-4">
                            <font color="#A6A6A6">
                         <?php if(isset($row)){	echo 'Toplamda <b>'.count($row). '</b> sonuç bulundu.';}?>
                         
                            
                            </font>
               		</div> 
                     <?php 
					 
					 
					 
					 
						echo'<div class="col-lg-4">';	 
					 if(isset($_SESSION['kat'])){
						 
												 
												 $stmt = $db->prepare("SELECT name,categoryID FROM categories WHERE categoryID = ?"); 
											$stmt->execute(array($_SESSION['kat'])); 
											$kat = $stmt->fetch();
												 
												 
												 echo '
                                                  <div  style="padding:4px;" align="center"  class="alert alert-danger alert-dismissible" role="alert">
                                                   <strong>'.$kat['name'].' </strong>
                                                       <button type="submit" name="delkat" class="close"  style="float:right; right:0px;" aria-label="Close"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                                    </div>
													';
											} 
					echo'</div><div class="col-lg-4">';						
					if(isset($_SESSION['kelime']) && $_SESSION['kelime'] != "" ){
													
													 
											 echo '
													  <div  style="padding:4px;" align="center"  class="alert alert-warning alert-dismissible" role="alert">
															   <strong>'.$_SESSION['kelime'].' </strong>
																<button type="submit" name="delkel"  class="close"  style="float:right; right:0px;" aria-label="Close">
																<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
														</div>
													';
													}
													?>
                    </div>
             </div>   
		</div> 
          </form>  
            <div id="Icerik" style="padding:0px; background-color:transparent; margin-top:25px; border:0px;"  class="clearfix">
               <?php


	
	
		

	if(isset($row) && count($row) != 0){
	
		
		echo'   
				
                <table   class="table table-striped ">
               
				 <thead >  
                    <tr bgcolor="#428bca" style="color:fff;">
                       <th  style="text-align:center;vertical-align:middle;">RESİM</th>
                      <th  style="text-align:center;vertical-align:middle;">BAŞLIK</th>
                      <th  style="text-align:center;vertical-align:middle;">FİYAT</th>
                      <th  style="text-align:center;vertical-align:middle;">TARİH</th>
                      <th  style="text-align:center;vertical-align:middle;">ADRES</th>
                   </div></tr> 
                 
				 
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
              ';
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