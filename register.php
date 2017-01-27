<?php require('layout/header.php'); 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: memberpage.php'); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Kullanıcı adı çok kısa.';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Bu kullanıcı adı kullanımda.';
		}
			
	}
	
	if(strlen($_POST['telno']) != 11){
		$error[] = 'Lütfen geçerli bir telefon numarası girin.(05012345678)';
	} else {
		$stmt = $db->prepare('SELECT telno FROM members WHERE telno = :telno');
		$stmt->execute(array(':telno' => $_POST['telno']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['telno'])){
			$error[] = 'Bu telefon numarası kullanımda.';
		}
			
	}
	
	if(strlen($_POST['name']) < 1){
		$error[] = 'İsminiz çok kısa.';
	}
	
	if(strlen($_POST['name']) < 1){
		$error[] = 'Soyadınız çok kısa.';
	}
	
	if(strlen($_POST['birthdate']) == 0){
		$error[] = 'Lütfen geçerli bir doğum tarihi giriniz.';
	}
	
	if(strlen($_POST['password']) < 3){
		$error[] = 'Şifre çok kısa.';
	}
	

	

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Lütfen geçerli bir e-mail adresi giriniz.';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Bu e-mail zaten kayıtlı.';
		}
			
	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (name,surname,username,telno,birthdate,password,email,registerDate,active) VALUES (:name, :surname, :username, :telno, :birthdate, :password, :email, :registerDate, :active)');
			$stmt->execute(array(
				':name' => $_POST['name'],
				':surname' => $_POST['surname'],
				':username' => $_POST['username'],
				':telno' => $_POST['telno'],
				':birthdate' => $_POST['birthdate'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':registerDate' => date("Y-m-d H:i:s"),
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');


			include 'class.phpmailer.php';
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = 'smtp.sitem.com';
			$mail->Port = 587;
			$mail->Username = 'benim@adresim.com';
			$mail->Password = 'çokgizlişifre';
			$mail->SetFrom($mail->Username, 'Benim Adım');
			$mail->AddAddress($_POST['email'], $name ." ". $surname);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = 'Mail Başlığı';
			$mail->MsgHTML('Mailin içeriği!');
			//send email
			$to = 
			$subject = "Registration Confirmation";
			$body = "Thank you for registering at demo site.\n\n To activate your account, please click on this link:\n\n ".DIR."activate.php?x=$id&y=$activasion\n\n Regards Site Admin \n\n";
			$additionalheaders = "From: <".SITEEMAIL.">\r\n";
			$additionalheaders .= "Reply-To: $".SITEEMAIL."";
			mail($to, $subject, $body, $additionalheaders);

			//redirect to index page
			header('Location: register.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Demo';

//include header template

?>
<link rel="stylesheet" href="style/blankpage.css">
<div id="Content" class="clearfix">
           <div   class="panel panel-primary">
                <div  class="panel-heading">
                <h3 class="panel-title">Üye Kayıt Ekranı</h3>
               		
                </div>
        

<div class="container">

	<div class="row">

	    <div class="col-md-6 ">
			<form role="form"   method="post" action="#" autocomplete="off">
				<hr>
			
				<font style="font-size:16px; color:#d9534f;">&nbsp;&nbsp;Üye olarak ücretsiz ilan vermeye başlayabilirsiniz.</font><hr>
 <div class="well">
				<?php
				//check for any errors
				
				if(isset($error)){
					echo '<p class="alert alert-danger" role="alert">';
					foreach($error as $error){
						echo $error.'<br>';
					}
					echo '</p>';
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='alert alert-success' role='alert'>Başarıyla kayıt oldunuz! Lütfen mail adresinize gelen linke tıklayıp üyeliğinizi aktifleştiriniz.</h2>";
				}
				
				
				?>
                
               
                	
               
 				<div class="form-group">
					<input type="text" name="name" id="name" class="form-control input-lg" placeholder="Adınız" value="<?php if(isset($error)){ echo $_POST['name']; } ?>" tabindex="2">
				</div>
                 <div class="form-group">
					<input type="text" name="surname" id="surname" class="form-control input-lg" placeholder="Soy Adınız" value="<?php if(isset($error)){ echo $_POST['surname']; } ?>" tabindex="2">
				</div>
                <div class="row">
				<div class="form-group col-md-8">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Kullanıcı Adınız" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
				</div>
                <div  class="col-md-4"> <span id="helpBlock" class="help-block">Giriş yaparken kullanacağınız isim.</span>
                	
                </div>
               
              
				<div  class="form-group col-md-8" >
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Adresiniz" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2">
				</div>
                <div  class="col-md-4"> <span id="helpBlock" class="help-block">Üyeliğinizi aktifleştirmek için gereklidir.</span>
                	
                </div>
                <div  class="form-group col-md-8" >
					<input type="tel" name="telno" id="telno" class="form-control input-lg" placeholder="Telefon Numaranız" value="<?php if(isset($error)){ echo $_POST['telno']; } ?>" tabindex="2">
				</div>
                <div  class="col-md-4"> <span id="helpBlock" class="help-block">Sizinle iletişime geçebilmek için gereklidir.</span>
                	
                </div>
                 <div  class="form-group col-md-8" >
					<input type="date" name="birthdate" id="birthdate" class="form-control input-lg" value="<?php if(isset($error)){ echo $_POST['birthdate']; } ?>" tabindex="2">
				</div>
                <div  class="col-md-4"> <span id="helpBlock" class="help-block">Doğum Tarihiniz.</span>
                	
                </div>
                  </div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Şifre" tabindex="3">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Şifre Tekrarı" tabindex="4">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div  class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Kayıt Ol" class="btn btn-primary btn-block btn-lg" tabindex="5">
                    	</div>
                        <div style="vertical-align:middle;" class="col-xs-6 col-md-6">
                        Zaten üye misin? <a style="color:#09F;" href='login.php'>Giriş yap</a>
                        </div>
				</div>
                </div>
			</form>
		</div>
	</div>

</div>
    </div>
        </div>
<?php 
//include header template
require('layout/footer.php'); 
?>