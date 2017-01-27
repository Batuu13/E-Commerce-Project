<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="style/bootstrap/js/bootstrap.js"></script>
 <div id="Kategoriler" class="clearfix">
 		<div class="panel panel-primary clearfix">
        	
                <div  class="panel-heading">
                <center>KULLANICI PANELİ</center>
                </div>
               
                   
                    <div class="panel-body" style="padding:0px; font-size:18px;"">
                    <ul class="nav nav-pills nav-stacked">
                  <li style="background-color:#FFF;" role="presentation" <?php if($active == 1) echo 'class="active"';?>> <a href="memberpage.php" >Kişisel Bilgiler</a></li>
                  <li style="background-color:#FFF;" role="presentation" <?php if($active == 2) echo 'class="active"';?>> <a href="passwordchange.php" >Şifre Değiştir</a></li>
                  <li style="background-color:#FFF;" role="presentation" <?php if($active == 3) echo 'class="active"';?>> <a href="notifications.php" >Bildirimler <div align="right" class='badge badge-primary'><?php if($user->notification != 0)echo $user->notification; ?></div></a></li>
                  <li style="background-color:#FFF;" role="presentation" <?php if($active == 4) echo 'class="active"';?>> <a href="ilanlarim.php" >İlanlarım</a></li>
                   <li style="background-color:#FFF;" role="presentation" <?php if($active == 5) echo 'class="active"';?>> <a href="mesajlarim.php" >Mesajlarım<span style="margin-left:100px; color:#999; font-size:14px;" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>  </li>
                   <?php 
                   if($active == 5 || $active == 5.1 || $active == 5.2){
                   echo'
                    <li role="presentation" style="background-color:#F2F2F2; border-bottom:2px;border-bottom-style:inset; border-bottom-color:#D4D4D4 ;font-size:14px;" ';
                     if($active == 5.1) echo 'class="active"';
                    
                    echo'> <a href="mesajlarim.php" ><span style="margin-right:5px; margin-top:5px; font-size:12px;" class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>Gelen Mesajlar</a></li> 
                     <li  style="background-color:#F2F2F2;font-size:14px;" role="presentation"';
                       if($active == 5.2) echo 'class="active"';
                     echo'> <a href="gidenmesajlarim.php" ><span style="margin-right:5px; margin-top:5px;font-size:12px;" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>Giden Mesajlar</a></li> 
                     ';
                    }?>
                    <li style="background-color:#FFF;" role="presentation" > <a href="logout.php" ><b>Çıkış Yap</b></a></li>
                    </ul>
                    </div>
                    
           </div>    
                
</div>