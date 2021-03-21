<!--Connexion à la base de donnée-->
<?php
	try{
		$myPDO = new PDO('mysql:host=localhost;dbname=intelligent_library;charset=utf8', 'root', 'A123456*');
		return $myPDO;
	}catch(PDOException $e){
		echo $e->getMessage();
	}
?>
	