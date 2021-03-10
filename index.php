<?php
	session_start();
	require_once('./include/functions/mysql.conf.php');
	include('./include/functions/functions_mysql.inc.php');	
	$currently_books=getRandomBooks($myPDO,5);
	$may_like_books=getRandomBooks($myPDO,10);
	$because_books=getRandomBooks($myPDO,5);
	$favorite_books=getRandomBooks($myPDO,5);
	
	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email']))) AND ((isset($_SESSION['password'])) AND (!empty($_SESSION['password'])))){
		$email=$_SESSION['email'];	
		$password=$_SESSION['password'];
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Index-Books_And_Cie</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="google-site-verification" content="WYkpNtPBJPzYKIXRwoMjJE0Oy6zd6kuh7VGF7UVmsK8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="./css/style_index.css"/>
	<link rel="stylesheet" type ="text/css" href="./css/style_header.css"/>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>	
<div class="content" style="padding-bottom:6.5em">
	<?php include("./include/headers/header_index.php")?>
	
	<img src="./img/slide1.jpg" class="bg-header" alt='background image'/>	

	<h2 style="position:relative;margin-top:-5.5em;text-align:center;font-size:45px;z-index:1;color:white"><b>The ultimate guide to finding your book! </b></h2>
	<p style="text-align:center;z-index:1;font-size:30px;color:white">You don't know what to read ? Explore our book catalog.</p>
	<p style="text-align:center;z-index:1;font-size:30px;color:white">You may find some real gems.</p>
	
	<div class="main-container">
		<p style="height:8em"></p>
		<div class="right-now">
			<h2 style="font-size:25px;text-decoration:underline">CURRENTLY</h2>
			<table style="margin:2.5em auto">
				<tr>
					<td><a href="./research/book.php?id=<?php echo $currently_books['id'][0];?>"><img src=<?php echo $currently_books['cover'][0];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $currently_books['id'][1];?>"><img  src=<?php echo $currently_books['cover'][1];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $currently_books['id'][2];?>"><img  src=<?php echo $currently_books['cover'][2];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $currently_books['id'][3];?>"><img src=<?php echo $currently_books['cover'][3];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $currently_books['id'][4];?>"><img src=<?php echo $currently_books['cover'][4];?> width="170" height="220" alt="cover-book"></a></td>
				</tr>
				<tr>
					<td style="padding-top:1em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $currently_books['title'][0];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $currently_books['title'][1];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $currently_books['title'][2];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $currently_books['title'][3];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $currently_books['title'][4];?></p>
						</div>
					</td>
				</tr>
			</table>	
		</div>
		
		<div class="books-love">
			<h2 style="font-size:25px;text-decoration:underline">BOOKS THAT YOU MAY LIKE</h2>
			<table style="margin:2.5em auto">
				<tr>
					<td><a href="./research/book.php?id=<?php echo $may_like_books['id'][0];?>"><img src=<?php echo $may_like_books['cover'][0];?> width="170" height="220" alt="logo-site"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][1];?>"><img  src=<?php echo $may_like_books['cover'][1];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][2];?>"><img  src=<?php echo $may_like_books['cover'][2];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][3];?>"><img src=<?php echo $may_like_books['cover'][3];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][4];?>"><img src=<?php echo $may_like_books['cover'][4];?> width="170" height="220" alt="cover-book"></a></td>
				</tr>
				<tr>
					<td style="padding-top:1em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][0];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13m'>
							<p><?php echo $may_like_books['title'][1];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][2];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][3];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][4];?></p>
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding-top:3em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][0];?>"><a href="./research/book?id=<?php echo $may_like_books['id'][0];?>"><img src=<?php echo $may_like_books['cover'][5];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][1];?>"><img  src=<?php echo $may_like_books['cover'][6];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][2];?>"><img  src=<?php echo $may_like_books['cover'][7];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][3];?>"><img src=<?php echo $may_like_books['cover'][8];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $may_like_books['id'][4];?>"><img src=<?php echo $may_like_books['cover'][9];?> width="170" height="220" alt="cover-book"></a></td>
				</tr>
				<tr>
					<td style="padding-top:1em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][5];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][6];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][7];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13m'>
							<p><?php echo $may_like_books['title'][8];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $may_like_books['title'][9];?></p>
						</div>
					</td>
				</tr>
			</table>	
		</div>
	
		<div class="because-of">
			<h2 style="font-size:25px;text-decoration:underline">BECAUSE YOU BOUGHT [...]</h2>
			<table style="margin:0.5em auto">
				<tr>
					<td style="padding-top:3em"><a href="./research/book.php?id=<?php echo $because_books['id'][0];?>"><img src=<?php echo $because_books['cover'][0];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $because_books['id'][1];?>"><img  src=<?php echo $because_books['cover'][1];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $because_books['id'][2];?>"><img  src=<?php echo $because_books['cover'][2];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $because_books['id'][3];?>"><img src=<?php echo $because_books['cover'][3];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $because_books['id'][4];?>"><img src=<?php echo $because_books['cover'][4];?> width="170" height="220" alt="cover-book"></a></td>
				<tr>
					<td style="padding-top:1em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $because_books['title'][0];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $because_books['title'][1];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $because_books['title'][2];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13m'>
							<p><?php echo $because_books['title'][3];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $because_books['title'][4];?></p>
						</div>
					</td>
				</tr>
			</table>	
		</div>
	
		<div class="readers-fav">
			<h2 style="font-size:25px;text-decoration:underline">READERS FAVORITES</h2>
			<table style="margin:0.5em auto">
				<tr>
					<td style="padding-top:3em"><a href="./research/book.php?id=<?php echo $favorite_books['id'][0];?>"><img src=<?php echo $favorite_books['cover'][0];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $favorite_books['id'][1];?>"><img src=<?php echo $favorite_books['cover'][1];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $favorite_books['id'][2];?>"><img src=<?php echo $favorite_books['cover'][2];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $favorite_books['id'][3];?>"><img src=<?php echo $favorite_books['cover'][3];?> width="170" height="220" alt="cover-book"></a></td>
					<td style="padding-top:3em;padding-left:5em"><a href="./research/book.php?id=<?php echo $favorite_books['id'][4];?>"><img src=<?php echo $favorite_books['cover'][4];?> width="170" height="220" alt="cover-book"></a></td>
				<tr>
				<tr>
					<td style="padding-top:1em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $favorite_books['title'][0];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $favorite_books['title'][1];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $favorite_books['title'][2];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13m'>
							<p><?php echo $favorite_books['title'][3];?></p>
						</div>
					</td>
					<td style="padding-top:1em;padding-left:5em">
						<div style='word-wrap: break-word;width:13em'>
							<p><?php echo $favorite_books['title'][4];?></p>
						</div>
					</td>
				</tr>
			</table>	
		</div>	
	</div>
	<p style="height:5em"></p>
</div>

	<?php include("./include/footers/footer_index.html") ?>
	
	<div class="connexion-modal">
		<div class="connexion-container">
			<div class="close">+</div>
			<img src="./img/logo-account2-black.png" width="60" height="60" alt="logo-account">	
			<form action="./account/connexion.php" method="POST">
				<input class="input-connexion" name="email" type="email" placeholder="Email" required >
				<input class="input-connexion" name="password" type="password" placeholder="Password" required >
				<input type="hidden" name="page" value="../index.php">
				<input type="submit" class="button-connexion" value="Log in" >
				<a class="inscription-link" href="./account/inscription.php"><p style="text-align:center;margin-top:1em;color:#023f76;">Create an account</p></a>
			</form>
		</div>
	</div>
	<script src="./js/script_header.js"></script>
</body>
</html>