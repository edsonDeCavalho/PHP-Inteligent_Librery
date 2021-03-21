<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');
	include('../include/functions/functions.inc.php');
	require_once('../include/research_books_GET_POST.php');	
	

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>catalogue-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_books.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
	
</head>

<body>
<div class="content" style="padding-bottom:6.5em">
	<?php include("../include/headers/header_books.php")?>	

	<div class="main-container">
		<div class="menu-research-container">
			<form action="./books.php" method="POST">
			<div class="rating-star-container">
				<?php printRatingStars();?>
			</div>
			<div class="date-container">
				<?php printDate(); ?>
			</div>
			<div class="genres-container">
				<?php printGenres(); ?>
			</div>
			<table style="margin:2em auto">
				<tr>
					<td><input type="submit" class="button-menu" value="APPLIQUER"></td>
				</tr>
			</table>
			</form>
		</div>
		
		<div class="research-result-container">
			<h2 style="font-size:22px;margin-left:0.4em;margin-top:-1px">RESULT OF THE RESEARCH</h2>
			<?php 
				printBooksResearch($research_books,$begin);
				pagination($category,$current_page,$total_pages,$release_date,$rating,$search);
			?>
			
		</div>
	</div>
	<p style="height:145px"></p>
</div>

	<?php include("../include/footers/footer_books.html") ?>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="../img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="../account/connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../index.php">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="../account/inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
	
<script>
	function ratingControl(j) {
		var total=0;
		for(var i=0; i < document.form1.rating.length; i++){
			if(document.form1.rating[i].checked){
				total =total +1;
			}
			if(total > 3){
				alert("Please select a maximum of three ratings") ;
				document.form1.rating[j].checked = false ;
				return false;
			}
		}
	}
</script>
<script src="../js/script_header.js"></script>
</body>
</html>