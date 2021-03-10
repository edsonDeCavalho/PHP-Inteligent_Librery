<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');	
?>

<?php
	if((isset($_POST['pseudo']) AND (!empty($_POST['pseudo']))) AND (isset($_POST['email']) AND (!empty($_POST['email']))) 
		AND (isset($_POST['civility']) AND (!empty($_POST['civility'])))){
		$pseudo=$_POST['pseudo'];
		$email=$_POST['email'];
		$civility=$_POST['civility'];
		
		if((isset($_POST['firstname'])) AND (isset($_POST['lastname'])) AND (isset($_POST['pref_category1'])) 
			AND (isset($_POST['pref_category2'])) AND (isset($_POST['pref_category3'])) AND (isset($_POST['pref_category4']))){	
			
			$last_name=$_POST['lastname'];
			$first_name=$_POST['firstname'];
			$pref_category=array(0=>$_POST['pref_category1'],1=>$_POST['pref_category2'],2=>$_POST['pref_category3'],3=>$_POST['pref_category4']);
			
			if((isset($_POST['password']) AND (!empty($_POST['password']))) AND (isset($_POST['password_confirm']) AND (!empty($_POST['password_confirm'])))){
				$password=$_POST['password'];
				$password_confirm=$_POST['password_confirm'];
			}
			else{
				if((isset($_SESSION['password'])) AND (!empty($_SESSION['password']))){
					$password=$_SESSION['password'];
					$password_confirm=$_SESSION['password'];
				}
			}
			registerDataModification($civility,$pseudo,$email,$password,$password_confirm,$last_name,$first_name,$pref_category,$myPDO);	
		}
	}
	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['password'])) AND (!empty($_SESSION['password'])))){
		$email=$_SESSION['email'];
		$password=$_SESSION['password'];
		$array_data=DataProfil($myPDO,$email,$password);
	}
	else{
		header('location:./inscription.php?error=Please register or login to view your profile');
	}		
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Profil-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_profil.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&amp;display=swap" rel="stylesheet"> 
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>	
<div class="content" style="padding-bottom:6.5em">
	<?php include("../include/headers/header_account.php")?>
	
	<div id="main-container">
		<img src="../img/logo-account2-black.png" style="padding-top:1em" width="60" height="70" alt="logo-account">
		<p style="font-family: 'Montserrat', sans-serif;font-size:16px;padding-top:0.5em">MY PROFIL</p>
		<hr style="margin-right:7.5em;margin-left:7.5em;margin-top:0.5em">
		
		<?php
			if((isset($_GET['error'])) AND (!empty($_GET['error']))){
				$error=$_GET['error'];
				echo "<p id='error-sentence' style='padding-top:0.4em;color:red'>".$error."</p>";
			}
		?>
		
		<form id="profil-form" action="./profil.php" method="POST">
			<label for="pseudo">Pseudo*</label>
			<input type="text" id="pseudo" name="pseudo" maxlength="30" value="<?php echo $array_data['pseudo'];?>"/>
						
			<label id="civility-label" for="civility-select">Civility* </label>
			<select id="civility-select" name="civility" >
			
				<?php
					if ($array_data['civility']==1){
						echo "<option value='Mme' selected='selected'>Mme</option>";
						echo "<option value='Mr'>Mr</option>";					
					}
					else{
						echo "<option value='Mme'>Mme</option>";
						echo "<option value='Mr' selected='selected'>Mr</option>";
					}
				?>
			</select>
			
			<label id="first_name-label" for="first_name_user">First name</label>
			<input type="text" id="first_name_user" name="firstname" maxlength="30" value="<?php echo $array_data['first_name'];?>"/>
			
			<label id="last_name-label" for="last_name_user">Last name</label>
			<input type="text" id="last_name_user" name="lastname" maxlength="30" value="<?php echo $array_data['last_name'];?>"  />
		
			<table>
				<tr>
					<td><label id="email-label" for="email">E-mail adress*</label></td>
					<td><p id="error-email" style="color:red;font-size:15px;padding-left:2em"></p></td>
				</tr>
			</table>
			
			<input type="email" id="email"  style="color:grey" name="email" maxlength="50" value="<?php echo $array_data['email'];?>" onfocus="enterElement('error-email')" onblur="mailValidation()" readonly />
			
			
			<table style="margin-top:2em">
				<tr>
					<td><label>Password</label></td>
					<td style="padding-left:2em"><label style="color:blue" id="modified-password-link" onclick="passwordBlock()">edit</label></td>
				</tr>
			</table>
			
			<div id="change-password-block">
				<label id="password-label" for="password">New Password</label>
				<input type="password" id="password" name="password" pattern=".{8,}" maxlength="50" value="<?php echo $array_data['password'];?>" required disabled/>
				
				<table>
					<tr>
						<td><label id="password-confirm-label" for="password-confirm"> Password confirmation*</label></td>
						<td style="padding-top:1em"><label id="error-password-confirm" style="color:red;font-size:15px;padding-left:2em;margin-top:1em"></label></td>
					</tr>
				</table>
				<input type="password" name="password_confirm" id="password-confirm" pattern=".{8,}" maxlength="50"  placeholder="Confirmer votre mot de passe"
								onfocus="enterElement('error-password-confirm')" onblur="passwordValidation()"  required disabled/>	
			</div>
			
			<label id="category-label">Preferences by categories</label>
			<table>
				<tr>
					<td>		
						<select class="preferences-select"  name="pref_category1">
							<option value="<?php echo $array_data['pref_category4'];?>" selected>Pref 1: <?php echo $array_data['pref_category1'];?></option>
							<option value="Art">Art</option>
							<option value="Biography">Biography</option>
							<option value="Business">Business</option>
							<option value="Computers">Computers</option>
							<option value="Cooking">Cooking</option>
							<option value="Fiction">Fiction</option>
							<option value="Health">Health</option>
							<option value="History">History</option>
							<option value="Humor">Humor</option>
							<option value="Mathematics">Mathematics</option>
							<option value="Medical">Medical</option>
							<option value="Music">Music</option>
							<option value="Nature">Nature</option>
							<option value="Philosophy">Philosophy</option>
							<option value="Poetry">Poetry</option>
							<option value="Psychology">Psychology</option>
							<option value="Religion">Religion</option>
							<option value="Science">Science</option>
							<option value="None">None</option>
						</select>
					
					</td>
					<td style="padding-left:2em">
						<select class="preferences-select" name="pref_category2">
							<option value="<?php echo $array_data['pref_category4'];?>" selected>Pref 2 : <?php echo $array_data['pref_category2'];?></option>
							<option value="Art">Art</option>
							<option value="Biography">Biography</option>
							<option value="Business">Business</option>
							<option value="Computers">Computers</option>
							<option value="Cooking">Cooking</option>
							<option value="Fiction">Fiction</option>
							<option value="Health">Health</option>
							<option value="History">History</option>
							<option value="Humor">Humor</option>
							<option value="Mathematics">Mathematics</option>
							<option value="Medical">Medical</option>
							<option value="Music">Music</option>
							<option value="Nature">Nature</option>
							<option value="Philosophy">Philosophy</option>
							<option value="Poetry">Poetry</option>
							<option value="Psychology">Psychology</option>
							<option value="Religion">Religion</option>
							<option value="Science">Science</option>
							<option value="None">None</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<select class="preferences-select" name="pref_category3">
							<option value="<?php echo $array_data['pref_category3'];?>" selected>Pref 3 : <?php echo $array_data['pref_category3'];?></option>
							<option value="Art">Art</option>
							<option value="Biography">Biography</option>
							<option value="Business">Business</option>
							<option value="Computers">Computers</option>
							<option value="Cooking">Cooking</option>
							<option value="Fiction">Fiction</option>
							<option value="Health">Health</option>
							<option value="History">History</option>
							<option value="Humor">Humor</option>
							<option value="Mathematics">Mathematics</option>
							<option value="Medical">Medical</option>
							<option value="Music">Music</option>
							<option value="Nature">Nature</option>
							<option value="Philosophy">Philosophy</option>
							<option value="Poetry">Poetry</option>
							<option value="Psychology">Psychology</option>
							<option value="Religion">Religion</option>
							<option value="Science">Science</option>
							<option value="None">None</option>
						</select>
					</td>
					<td style="padding-left:2em">
						<select class="preferences-select" name="pref_category4">
							<option value="<?php echo $array_data['pref_category4'];?>" selected>Pref 4 : <?php echo $array_data['pref_category4'];?></option>
							<option value="Art">Art</option>
							<option value="Biography">Biography</option>
							<option value="Business">Business</option>
							<option value="Computers">Computers</option>
							<option value="Cooking">Cooking</option>
							<option value="Fiction">Fiction</option>
							<option value="Health">Health</option>
							<option value="History">History</option>
							<option value="Humor">Humor</option>
							<option value="Mathematics">Mathematics</option>
							<option value="Medical">Medical</option>
							<option value="Music">Music</option>
							<option value="Nature">Nature</option>
							<option value="Philosophy">Philosophy</option>
							<option value="Poetry">Poetry</option>
							<option value="Psychology">Psychology</option>
							<option value="Religion">Religion</option>
							<option value="Science">Science</option>
							<option value="None">None</option>
						</select>
					</td>
				</tr>
			</table>
			<table style="margin:1em auto">
				<tr>
					<td style="padding-top:2em;padding-right:3.5em"><button id="save-button" type="submit">UPDATE</button></td>
				</tr>
			</table>
			<p style="height:2em"></p>
		</form>
	</div>
	<p style="height:19em"></p>
</div>
	<?php include("../include/footers/footer_index.html") ?>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="../img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="./connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../account/profil.php">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="./inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
<script src="../js/script_header.js"></script>
<script src="../js/changeProfil.js"></script>
</body>
</html>