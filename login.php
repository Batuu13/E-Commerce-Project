<?php
require('layout/header.php'); 
//include config


//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: index.php'); } 

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){ 

		header('Location: index.php');
		exit;
	
	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}

}//end if submit

//define page title
$title = 'Login';

//include header template

?>
<link rel="stylesheet" href="style/blankpage.css">
<div id="Content" class="clearfix">
             <div   class="panel panel-primary">
                <div  class="panel-heading">
                <h1 class="panel-title ">Üye Girişi</h1>
               		
                </div>
<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<br />
             
                
               
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
							break;
						case 'resetAccount':
							echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
							break;
					}

				}

				
				?>

				<div class="form-group">
               <h3> Kullanıcı Adı:</h3>
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Kullanıcı Adı" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
				</div>

				<div class="form-group">
                 <h3> Şifre:</h3>
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Şifre" tabindex="3">
				</div>
				
				<div class="row">
					<div class="col-xs-9 col-sm-9 col-md-9">
						 <a style="color:#09F;" href='reset.php'>Şifremi Unuttum!</a> | <a style="color:#09F;" href='register.php'>Üye değil misin? Üye Ol!</a>
              
					</div>
				</div>
				
				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Giriş Yap" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
                   
				</div>
			</form>
             <br />
		</div>
	</div>



</div>
   </div>
        </div>
	

<?php 
//include header template
require('layout/footer.php'); 
?>
