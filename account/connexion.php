<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');	
?>

<?php
	if(((isset($_POST['email'])) AND (!empty($_POST['email']))) AND ((isset($_POST['password'])) AND (!empty($_POST['password'])))){
		if(((isset($_POST['page'])) AND (!empty($_POST['page'])))){
			$page=$_POST['page'];
			$email=$_POST['email'];
			$password=$_POST['password'];
			connexionToProfil($myPDO,'location:'.$page,'location:../account/connexion',$email,$password);
		}
	}
	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['password'])) AND (!empty($_SESSION['password'])))){
		$email=$_SESSION['email'];	
		$password=$_SESSION['password'];
	}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Connexion-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_connexion.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&amp;display=swap" rel="stylesheet"> 
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>	
<div class="content" style="padding-bottom:6.5em">
	<?php include("../include/headers/header_account.php")?>

	<div id="connexion-container">
		<img src="../img/logo-account2-black.png" style="padding-top:1em" width="60" height="70" alt="logo-account">
		<p style="font-family: 'Montserrat', sans-serif;font-size:16px;padding-top:0.5em">CONNEXION</p>
		<hr style="margin-right:7.5em;margin-left:7.5em;margin-top:0.5em">
		<a class="inscription-link" href="./inscription.php" style="color:#023f76"><p style="text-align:center;margin-top:1em;font-size:16px">I do not have an account</p></a>	
		
		<?php
			if((isset($_GET['error'])) AND (!empty($_GET['error']))){
				$error=$_GET['error'];
				echo "<p id='error' style='padding-top:0.3em;color:red;text-align:center'>".$error."</p>";
			}
		?>

		<form id="connexion-form" action="./connexion.php" method="POST">
			<label id="email-label" for="email">Email adress*</label>
			<input type="email" name="email" id="email" placeholder="john.doe@gmail.com" required />	
			<label id="password-label" for="password">Password*</label>
			<input type="password" name="password" id="password" pattern=".{8,}" maxlength="50" required />
			<input type="hidden" name="page" value="../account/profil.php">
			<table style="margin:0 auto">
				<tr>
					<td style="padding-top:2em;padding-right:3em"><button id="save-button-connexion" type="submit">Log in</button></td>
				</tr>
			</table>
		</form>
	</div>
	<p style="height:13em"></p>
</div>
	<?php include("../include/footers/footer_index.html") ?>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="../img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="./connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../index.php">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="./inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
	
<script src="../js/script_header.js"></script>
<script src="../js/registration.js"></script>
</body>
</html>