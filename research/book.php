<?php
	session_start();
	require_once('../include/functions/mysql.conf.php');
	include('../include/functions/functions_mysql.inc.php');
	include('../include/functions/functions.inc.php');
	

	if((isset($_GET['id'])) AND ((!empty($_GET['id'])))){
		$id_book=$_GET['id'];
		$array_books_detail=getDetailBook($myPDO,$id_book);
		$popular_books=getRandomBooks($myPDO,5);
		$similar_books=getSimilarBooks($myPDO,$id_book,$array_books_detail['category']);
		
		if(isset($_SESSION['visualization'])){
			$visualization_books=$_SESSION['visualization'];
			array_push($visualization_books['id_book'],$id_book);
			array_push($visualization_books['category_book'],$array_books_detail['category']);
			$_SESSION['visualization']=	$visualization_books;
			
		}
	}
	else{
		header("../index.php'");
	}
	
	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['password'])) AND (!empty($_SESSION['password'])))){
		$email=$_SESSION['email'];	
		$password=$_SESSION['password'];
	}
	
	if(((isset($_POST['rate'])) AND (!empty($_POST['rate'])))){
		$rate=$_POST['rate'];
		registerRatingUser($myPDO,$id_book,$rate,$email,$password);
		unset($_POST['rate']);
	}
	
	if(((isset($_GET['register'])) AND (!empty($_GET['register'])))){
		$register=$_GET['register'];
		if($register=='yes'){
			registerBook($myPDO,$id_book,$email,$password);
		}
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>book-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_book.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>
<div class="content" style="padding-bottom:6.5em">
	<?php include("../include/headers/header_books.php")?>	
	
	<div class="main-container">
		<div class="detail-container">
			<div class="cover-container">
				<img src=<?php echo $array_books_detail['cover'];?> width='200' height='300' alt='Image-Book'>
				<table style="margin-top:1em">
					<tr>
						<td><img src="../img/heart.png" width="20" height="20" alt="logo-account"></td>
						<?php
							if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['email'])) AND (!empty($_SESSION['email'])))){
								echo "<td style='padding-left:0.3em'><a  href='./book.php?id=$id_book&amp;register=yes' style='color:red;font-size:16px'>Add to my favorites</a></td>";
							}
							else{
								echo "<td style='padding-left:0.3em'><a href='#' id='button-register-need-connexion' style='color:red;font-size:16px'>Add to my favorites</a></td>";
							}
						?>	
					</tr>
				</table>
			</div>

			<div class="resume-container">
				<div>
					<h3 style="margin-left:1em"><?php echo $array_books_detail['title'];?></h3>
					<?php
						if(isset($array_books_detail['author'])){
							if(count($array_books_detail['author'])>1){
								$i=0;
								echo "<h4 style='margin-left:1.3em'>By ".$array_books_detail['author'][0];
								for ($i=1;$i<count($array_books_detail['author']);$i++){
									if($i==(count($array_books_detail['author'])-1)){
										echo " et ".$array_books_detail['author'][$i]."</h4>";
									}
									else{
										echo ", ".$array_books_detail['author'][$i];
									}
								}
							}
							else{
								echo "<h4 style='margin-left:1.3em'>By ".$array_books_detail['author'][0]."</h4>";
							}
						}
						echo "<h4 style='margin-left:1.3em'>Published on ".$array_books_detail['date_publication']."</h4>";
					?>
				</div>
				
				<form method="post" action=<?php echo $array_books_detail['link_read_online'];?> TARGET="_BLANK">
				<table style="margin-top:3em">
					<tr>
						<td style="padding-left:1.7em"> <button class="button-opinion" type="submit">READ ONLINE</button>
				</form>
					<?php
						if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['email'])) AND (!empty($_SESSION['email'])))){
							echo "<td style='padding-left:5em'><button type='button' class='button-opinion' id='button-opinion'>RATE THE BOOK</a></td>";
						}
						else{
							echo "<td style='padding-left:5em'><button type='button' class='button-opinion' id='button-opinion-need-connexion'>RATE THE BOOK</a></td>";
						}
					?>	
						
						<td><?php printRating($myPDO,$id_book);?></td>
					</tr>
				</table>
				
				<div>
					<h4 style="margin-left:1.4em;margin-top:3em">Synopsis</h4>
					<p style="width:70%;margin-left:1.6em;text-align:justify;text-justify: inter-word;font-size:16px"><?php echo $array_books_detail['synopsis'];?></p>
					<?php 
						if(strlen($array_books_detail['synopsis'])<200){
							echo "<p style='height:6em'></p>";
						}
						else if(strlen($array_books_detail['synopsis'])<500){
							echo "<p style='height:3em'></p>";
						}
					
					?>
				</div>
			</div>
		</div>		
		<?php printSimilarBooks($similar_books,$array_books_detail['category'],$popular_books); ?>	
	</div>
	
	<p style="height:200px"></p>
</div>
	
	<?php 
		include("../include/footers/footer_books.html");
		
		if((isset($_GET['error'])) AND (!empty($_GET['error']))){
			$error=$_GET['error'];
			if($error=="register"){
				$error="You have already record this book.";
			}
			else{
				$error="You have already rated this book.";
			}

			echo "<div class='error-modal'>";
				echo "<div class='connexion-container'>";
				echo" <div class='close-error'><a href='./book.php?id=$id_book' style='color:black;text-decoration:none;font-size:42px'>+</a></div>";
				echo "<p style='color:black;margin-top:2em;'>".$error."</p>";
				echo "<img src='../img/book_logo.png' height='150' width='150' alt='Error-image'/>";
			echo "</div>";
		}
		
		if((isset($_GET['register'])) AND (!empty($_GET['register']))){
			$register=$_GET['register'];
			if($register=="yes"){
				$error="You have already record this book.";
				echo "<div class='register-modal'>";
					echo "<div class='connexion-container'>";
					echo" <div class='close-error'><a href='./book.php?id=$id_book' style='color:black;text-decoration:none;font-size:42px'>+</a></div>";
					echo "<p style='color:black;margin-top:2em;'>The book has been saved to your favorites history</p>";
					echo "<img src='../img/book_logo.png' height='150' width='150' alt='Error-image'/>";
				echo "</div>";
			}
		}
	?>
	
	<div class="opinion-modal">
		<div class="opinion-container">
			<div class="opinion-content">
				<div class="close-opinion">+</div>
				<div class="text"><h3 style="color:white">I give my opinion</h3></div>
				<div class="star-widget">
					<form action='./book.php?id=<?php echo $id_book;?>' method='POST'>
						<input class="input-star" type="radio" name="rate" id="rate-5" value="5">
						<label for="rate-5" class="fas fa-star"></label>
						<input class="input-star" type="radio" name="rate" id="rate-4" value="4">
						<label for="rate-4" class="fas fa-star"></label>
						<input class="input-star" type="radio" name="rate" id="rate-3" value="3">
						<label for="rate-3" class="fas fa-star"></label>
						<input class="input-star" type="radio" name="rate" id="rate-2" value="2">
						<label for="rate-2" class="fas fa-star"></label>
						<input class="input-star" type="radio" name="rate" id="rate-1" value="1">
						<label for="rate-1" class="fas fa-star"></label>
				
						<div class="header-star"></div>						
						<div class="btn-opinion">
							<input type="submit" id="button-opinion-submit" value="Publish">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="../img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="../account/connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../research/book.php?id=<?php echo $id_book;?>">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="../account/inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
	
	<script src="../js/script_header.js"></script>
	<script src="../js/script_opinion.js"></script>
</body>
</html>