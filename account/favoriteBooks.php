<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');

	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['password'])) AND (!empty($_SESSION['password'])))){
		$email=$_SESSION['email'];
		$password=$_SESSION['password'];
		
		if((isset($_GET['page'])) AND (!empty($_GET['page'])) AND $_GET['page']>0 ){
			$_GET['page']=intval($_GET['page']);
			$current_page=$_GET['page'];
		}		
		else{
			$current_page=1;
		}
		
		$per_pages=5;
		$nbr_books=getNbrRegister($myPDO,$email,$password);
		$pages = ceil($nbr_books / $per_pages);
		$first = ($current_page * $per_pages) - $per_pages;
			
		if((isset($_GET['idBook'])) AND (!empty($_GET['idBook']))){
			$id_book=$_GET['idBook'];
			deleteBook($myPDO,$email,$password,$id_book);
		}
	}
	else{
		header('location:./inscription.php?error=Please register or login');
	}		
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Favorite-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_favorite_books.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>
<div class="content" style="padding-bottom:23em">
	<?php include("../include/headers/header_account.php")?>	
	
	<div id="favorite-container">
		<p style="text-align:center"><img src="../img/book_logo.png" style="padding-top:1em" width="60" height="60" alt="logo-favorite-book"></p>
		
		<?php 
			$number_books=getNumberBooksRecording($myPDO,$email,$password);
			if($number_books==0 || $number_books==1){
				$books_string="book";
			}
			else{
				$books_string="books";
			}
			echo "<p class='number-books'>My favorite books : ".$number_books." ".$books_string."</p>";
		?>
		
		<hr style="margin-right:7.5em;margin-left:7.5em;margin-top:0.5em'">
		
		<div class='books-container'>
			<?php
				printBooks($myPDO,$email,$password,$pages,$current_page,$per_pages,$first);
			?>
			<p class="space"></p>
		</div>	
		
		<?php 
			pagination_favorite($current_page,$pages);
		?>
	</div>
</div>
	<?php 
		include("../include/footers/footer_books.html");
	?>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="../img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="../account/connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../research/index.php">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="./inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
	
	<script src="../js/script_header.js"></script>
	<script src="../js/script_opinion.js"></script>
</body>
</html>
	