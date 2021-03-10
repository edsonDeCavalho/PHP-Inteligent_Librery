<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');
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
			<form action="./books.php" name="form1" method="POST">
			<div class="rating-star-container">
				<h2 style="font-size:22px;margin-left:0.7em">EVALUATIONS</h2>
				<table>
					<tr>
						<td style="padding-left:1.5em"><input type="checkbox" onclick='ratingControl(0)' name="rating" value="5"/>
						<td style="padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" onclick='ratingControl(1)' name="rating" value="4"/></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" onclick='ratingControl(2)' name="rating" value="3"/></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" onclick='ratingControl(3)' name="rating" value="2"/></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" onclick='ratingControl(4)' name="rating" value="1"/></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:#fd4;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
						<td style="padding-top:1em;padding-left:1em"><label style="color:grey;font-size:21px" class="fas fa-star"></label></td>
					</tr>
				</table>
			</div>
			<div class="date-container">
				<h2 style="font-size:22px;margin-left:0.7em">RELEASE DATE</h2>
				<select style="margin-left:1em;width:14em" class="form-control" name="release">
					<option value="all">All</option>
					<option value="t">2021-today</option>
					<option value="2016-2020">2016-2020</option>
					<option value="2011-2015">2011-2015</option>
					<option value="2006-2010">2006-2010</option>
					<option value="2001-2005">2001-2005</option>
					<option value="1980-2000">1980-2000</option>
					<option value="b">Before 1980</option>
				</select>
				<p style="height:3em"></p>
			</div>
			<div class="genres-container">
				<h2 style="font-size:22px;margin-left:0.7em">GENRES</h2>
				<table>
					<tr>
						<td style="padding-left:1.5em"><input type="checkbox" name="category[]" value="Art"/>
						<td style="padding-left:1em"><label>Art</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Biography"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Biography</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Business"/>
						<td style="padding-top:1em;padding-left:1em"><label>Business</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Computers"/>
						<td style="padding-top:1em;padding-left:1em"><label>Computers</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Cooking"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Cooking</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Fiction"/>
						<td style="padding-top:1em;padding-left:1em"><label>Fiction</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Health"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Health</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="History"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>History</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Humor"/>
						<td style="padding-top:1em;padding-left:1em"><label>Humor</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Mathematics"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Mathematics</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Medical"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Medical</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Music"/>
						<td style="padding-top:1em;padding-left:1em"><label>Music</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Nature"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Nature</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Philosophy"/>
						<td style="padding-top:1em;padding-left:1em"><label>Philosophy</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Poetry"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Poetry</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Psychology"/>
						<td style="padding-top:1em;padding-left:1em"><label>Psychology</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Religion"/></td>
						<td style="padding-top:1em;padding-left:1em"><label>Religion</label></td>
					</tr>
					<tr>
						<td style="padding-top:1em;padding-left:1.5em"><input type="checkbox" name="category[]" value="Science"/>
						<td style="padding-top:1em;padding-left:1em"><label>Science</label></td>
					</tr>
				</table>
			</div>
			<table style="margin:2em auto">
				<tr>
					<td><input type="submit" class="button-menu" value="APPLIQUER"></td>
				</tr>
			</table>
			</form>
		</div>
		
		<div class="research-result-container">
			<h2 style="font-size:22px;margin-left:0.4em;margin-top:-1px">RESULTAT DE LA RECHERCHE</h2>
			<?php 
				printBooksResearch($research_books,$begin);
				pagination($category,$current_page,$total_pages,$release_date);
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