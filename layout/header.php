<?php
require('includes/config.php'); 


?>
<!DOCTYPE html>
<html>

    <head>
    <link href='http://fonts.googleapis.com/css?family=Hind:400,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="style/boilerplate.css">
	<link rel="stylesheet" href="style/header.css">
  		
    
    <link href="style/bootstrap/css/bootstrap.css" rel="stylesheet">
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0">
    </head>
    <body>

    <div id="primaryContainer" class="primaryContainer clearfix">
        <div id="TopBarBack" class="clearfix">
            <div id="TopBar" class="clearfix">
             <div id="Sol" class="clearfix">
                <a href="index.php"><img src="images/logo3.png"></a>
                </div>
                <div id="Orta" class="clearfix">
              <form action="arama.php" method="post">   
                <div class="col-lg-12">
    		<div class="input-group">
   
      <input name="kelime" placeholder="Aranacak kelimeyi girin..." type="text" class="form-control">
      <span class="input-group-btn">
        <button class="btn btn-primary"   name="ara" type="submit"><span style="font-size:20px;" class="glyphicon glyphicon-search"></span></button>
      </span>
      
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
     </form>           
                </div>
               
                <div  id="Sag" class="clearfix">
                <?php 

				if(!$user->is_logged_in()){ 
              								echo ' <a style="color:#Fff;font-size:16px;" href="login.php">Giriş Yap</a> | <a style="color:#F00;font-size:16px;" href="register.php">Üye Ol!</a> ';
			   							}
										else{
											
												echo '<a href="memberpage.php" style="font-size:16px;color:#FFF;"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span><b> '.$user->name . " " . $user->surname  .'</b></a>';
												echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="notifications.php"><span class="glyphicon glyphicon-bell" style="font-size:26px;color:#FFF; position:absolute; margin-top:12px;" aria-hidden="true"> <a href="memberpage.php"><span style="background-color:#ff0000;position:absolute; margin-top:-8px;margin-left:10px;padding-left:6px;padding-top:1px;"  class="badge badge-primary">'; if($user->notification != 0)echo $user->notification; echo'</span></a>
												</span></a>';
											}
			   ?></div>
               <div align="right"  id="Ilan" class="clearfix">
                <a href="magaza.php"><button class="btn btn-danger" style="font-size:16px;" tabindex="5">Mağaza Aç!</button></a>
                <a href="add.php"><button class="btn btn-primary" style="font-size:16px;" tabindex="5">Ücretsiz İlan Ver!</button></a>
                </div>
                
            </div>
        </div>