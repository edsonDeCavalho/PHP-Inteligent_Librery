<?php
	function getTitleBookById($myPDO,$id_book){
		$query="SELECT title FROM book WHERE id_book='".$id_book."'";
		$result=$myPDO->query($query);
		
		while($data=$result->fetch()){
			$title=$data['title'];
		}
		$myPDO=null;
		return $title;
	}
?>

<?php
	function getCoverBookById($myPDO,$id_book){
		$query="SELECT cover FROM book WHERE id_book='".$id_book."'";
		$result=$myPDO->query($query);
		
		while($data=$result->fetch()){
			$cover=$data['cover'];
		}
		$myPDO=null;
		return $cover;
	}
?>

<!--Récuperer un nombre aléatoire de livre random-->
<?php
	function getRandomBooks($myPDO,$number=4){
		$array_books=array('id'=>array(),'cover'=>array(),'title'=>array());
		$i=0;
		
		$query="SELECT id_book,cover,title FROM book ORDER BY RAND() LIMIT ".$number."";
		$result=$myPDO->query($query);
		
		while($data=$result->fetch()){
			$array_books['id'][$i]=$data['id_book'];
			$array_books['cover'][$i]=$data['cover'];
			$array_books['title'][$i]=$data['title'];
			$i++;
		}
		$myPDO=null;
		return $array_books;
	}
?>

<!--Recuperer les livres de la recherchefiltrée (i.e avec les filtrages)-->
<?php
	function getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,$number_ratings){
		$array_books=array('id'=>array(),'cover'=>array(),'title'=>array());
		
		if($category!="none"){
			$i=0;	
			if($number_categories>1){
				if($rating!="none"){
					$query="SELECT book.id_book,title,cover,AVG(assess.mark) FROM book
							INNER JOIN assess ON book.id_book=assess.id_book
							WHERE category='".$category[0]."'";					
				}
				else{
					$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category[0]."'";
				}
				for($i=1;$i<$number_categories-1;$i++){
					$query.=" OR category='".$category[$i]."' ";
				}
			}
			else{
				if($rating!="none"){
					$query="SELECT book.id_book,title,cover,AVG(assess.mark) FROM book
							INNER JOIN assess ON book.id_book=assess.id_book
							WHERE category='".$category."'";					
				}
				else{
					$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category."'";
				}
			}
			if($release_date!="none"){
				switch($release_date){
					case "t":
						$release_date=date('Y');
						$query.=" AND (LEFT(date_publication, 4) IN ('".$release_date."'))";	
						break;
					case "b":
						$query.=" AND (LEFT(date_publication, 4)<1980)";	
						break;
					case "all":
						break;
					default:
						$release_date=explode('-',$release_date);
						$query.=" AND (LEFT(date_publication, 4) BETWEEN ".$release_date[0]." AND ".$release_date[1].")";	
				}
			}
			if($rating!="none"){
				if($number_ratings>1){
					$value_incr=$rating[0]+1;
					$rating_tmp=array();
					$query.=" GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating[0]." AND ".$value_incr.")";
				
					for($i=1;$i<$number_ratings-1;$i++){
						$rating_tmp[$i-1]=$rating[$i];
					}
				
					foreach($rating_tmp as $value){
						$value_incr=$value+1;
						$query.=" OR (AVG(assess.mark) BETWEEN ".$value." AND ".$value_incr.")";
					}
					$query.=" ORDER BY(category)";
				}
				else{
					$value_incr=$rating+1;
					$query.=" GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating." AND ".$value_incr.") ORDER BY(category)";
				}
			}
			else{
				$query.=" ORDER BY(category)";
			}
		}
		else{
			if($release_date=="none"){
				if($number_ratings>1){
					$value_incr=$rating[0]+1;
					$rating_tmp=array();
					$query="SELECT book.id_book,title,cover,AVG(assess.mark) FROM book
							INNER JOIN assess ON book.id_book=assess.id_book
							GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating[0]." AND ".$value_incr.")";
						
					for($i=1;$i<$number_ratings-1;$i++){
						$rating_tmp[$i-1]=$rating[$i];
					}
				
					foreach($rating_tmp as $value){
						$value_incr=$value+1;
						$query.=" OR (AVG(assess.mark) BETWEEN ".$value." AND ".$value_incr.")";
					}	
					$query.=" ORDER BY(category)";
				}
				else{
					$value_incr=$rating+1;
					$query="SELECT book.id_book,title,cover,AVG(assess.mark) FROM book
							INNER JOIN assess ON book.id_book=assess.id_book
							GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating." AND ".$value_incr.") ORDER BY(category)";
				}
			}
			else if($rating=="none"){
				switch($release_date){
					case "t":
						$release_date=date('Y');
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4) IN ('".$release_date."'))";
						break;
					case "b":
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4)<1980)";
						break;
					case "all":
						$query="SELECT id_book,cover,title FROM book ORDER BY RAND() LIMIT 200";
						break;
					default:
						$release_date=explode('-',$release_date);
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4) 
							BETWEEN ".$release_date[0]." AND ".$release_date[1].")";
				}
			}
			else{
				$query="SELECT book.id_book,title,cover,AVG(assess.mark) FROM book
						INNER JOIN assess ON book.id_book=assess.id_book";
						
				switch($release_date){
					case "t":
						$release_date=date('Y');
						$query.=" AND (LEFT(date_publication, 4) IN ('".$release_date."'))";
						break;
					case "b":
						$query.=" AND (LEFT(date_publication, 4)<1980)";
						break;
					case "all":
						break;
					default:
						$release_date=explode('-',$release_date);
						$query.=" AND (LEFT(date_publication, 4) BETWEEN ".$release_date[0]." AND ".$release_date[1].")";
				}
				if($number_ratings>1){
					$rating_tmp=array();
					$value_incr=$rating[0]+1;
					$query.=" GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating[0]." AND ".$value_incr.")";

					for($i=1;$i<$number_ratings-1;$i++){
						$rating_tmp[$i-1]=$rating[$i];
					}
							
					foreach($rating_tmp as $value){
						$value_incr=$value+1;
						$query.=" OR (AVG(assess.mark) BETWEEN ".$value." AND ".$value_incr.")";
					}
				}
				else{
					$value_incr=$rating+1;
					$query.=" GROUP BY (book.id_book) HAVING (AVG(assess.mark) BETWEEN ".$rating." AND ".$value_incr.")";
				}
				$query.=" ORDER BY(category)";
			}
		}
		
		$i=0;
		$result=$myPDO->query($query);
				
		while($data=$result->fetch()){
			$array_books['id'][$i]=$data['id_book'];
			$array_books['cover'][$i]=$data['cover'];
			$array_books['title'][$i]=$data['title'];
			$i++;
		}
		$myPDO=null;

		return $array_books;
	}
?>

<!-- Récuperer les détails d'un livre -->
<?php 
	function getDetailBook($myPDO,$id_book){
		$array_detail=array('title','synopsis','cover','date_publication','link_read_online','name_author'=>array(),'category');
		
		$query="SELECT title,synopsis,cover,date_publication,link_read_online,category FROM book
				WHERE book.id_book='".$id_book."'";
				
		$result=$myPDO->query($query);
		
		while($data=$result->fetch()){
			$array_detail['title']=$data['title'];
			$array_detail['synopsis']=$data['synopsis'];
			$array_detail['cover']=$data['cover'];
			$array_detail['date_publication']=getDateFormat($data['date_publication']);
			$array_detail['link_read_online']=$data['link_read_online'];
			$array_detail['category']=$data['category'];
		}
		
		$query="SELECT name_author FROM author 
				INNER JOIN writing ON writing.id_author=author.id_author 
				INNER JOIN book ON book.id_book=writing.id_book 
				WHERE book.id_book='".$id_book."'";
				
		$result=$myPDO->query($query);
		$i=0;
		
		while($data=$result->fetch()){
			$array_detail['author'][$i]=$data['name_author'];
			$i++;
		}
		
		return $array_detail;
		
		$myPDO=null;

	}
?>

<!--Enregistrer l'inscription d'un utilisateur dans la base de donnée (création d'un compte client-->
<?php
	function registerDataInscription($pseudo,$civility,$email,$password,$password_confirm,$last_name,$first_name,$pref_category,$myPDO){
		if($civility!="0"){
			if((strlen($pseudo)!=0) AND (strlen($email)!=0)){
				if((strlen($password)>7) AND (strlen($password)<51)){
					if($password_confirm==$password){
						$query="SELECT COUNT(id_client) AS does_is_exist FROM client_account WHERE email='$email' AND password='$password'";
						$query=$myPDO->query($query);
			
						while($data=$query->fetch()){
							if($data['does_is_exist']!=0){
								$query="SELECT pseudo FROM client_account WHERE email='$email' AND password='$password'";
								$query=$myPDO->query($query);
						
								while($data=$query->fetch()){
									$pseudoBis=$data['pseudo'];
								}
								
								$myPDO=null;
								header('location:./inscription.php?error=The account containing the nickname "'.$pseudoBis.'" has this e-mail address and this password. Please choose another email address.');
								return;
							}
						}
							
						$query="SELECT COUNT(id_client) AS does_is_exist FROM client_account WHERE email='$email'";
						$query=$myPDO->query($query);
		
						while($data=$query->fetch()){
							if($data['does_is_exist']!=0){
								$myPDO=null;
								header('location:./inscription.php?error=This e-mail address is already in use. Please choose another one.');
								return;
							}								
						}
					
						$query="SELECT COUNT(id_client) AS does_is_exist FROM client_account WHERE pseudo='$pseudo'";
					
						$query=$myPDO->query($query);
		
						while($data=$query->fetch()){
							if($data['does_is_exist']!=0){
								$myPDO=null;
								header('location:./inscription.php?error=This username is already in use. Please choose another one.');
								return;
							}
						}
				
						$query="SELECT id_client FROM client_account ORDER BY id_client DESC";
						$query=$myPDO->query($query);
						$next_id_client=0;
					
						while($donnees=$query->fetch()){
							$next_id_client=$donnees['id_client']+1;
							break;
						}

							
					
						$query = "INSERT INTO client_account (id_client,last_name,first_name,email,password,civility,pseudo,preferences_category1,
									preferences_category2,preferences_category3,preferences_category4)
									VALUES ('".$next_id_client."','".$last_name."','".$first_name."','".$email."','".$password.
									"','".$civility."','".$pseudo."','".$pref_category[0]."','".$pref_category[1]."','".$pref_category[2].
									"','".$pref_category[3]."')";
									
	
						$result=$myPDO->prepare($query);
						$result->execute();
					
						if ($result) {
							$myPDO=null;
							$_SESSION['pseudo']=$pseudo;
							$_SESSION['email']=$email;
							$_SESSION['password']=$password;
							$_SESSION['visualization']=array('id_book'=>array(),'category_book'=>array());
						
							header('location:./profil.php');
						}
						else {
							$myPDO=null;
							header('location:./inscription.php?error=There was a problem with the database, please start your registration again');
							return;
						}	
					}
					else{
						$myPDO=null;
						header('location:./inscription.php?error=Please enter the same password in both fields');
						return;
					}
				}
				else{
					$myPDO=null;
					header('location:./inscription.php?error=The password must contain between 8 and 50 characters.');
					return;
				}
			}
			else{
				$myPDO=null;
				header('location:./inscription.php?error=The email and the username must not be empty.');
				return;
			}
		}
		else{
			$myPDO=null;
			header('location:./inscription.php?error=Please enter your civility.');
			return;
		}	
	}
?>

<!--Afficher les détails d'un compte utilisateur -->
<?php
	function dataProfil($myPDO,$email,$password){
		$data_array=array();
		$query="SELECT id_client,last_name,first_name,email,password,civility,pseudo,preferences_category1,
					preferences_category2,preferences_category3,preferences_category4
					FROM client_account WHERE email='".$email."' AND password='".$password."'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$data_array['pseudo']=$data['pseudo'];
			$data_array['first_name']=$data['first_name'];
			$data_array['last_name']=$data['last_name'];
			$data_array['email']=$data['email'];
			$data_array['password']=$data['password'];
			$data_array['pref_category1']=$data['preferences_category1'];
			$data_array['pref_category2']=$data['preferences_category2'];
			$data_array['pref_category3']=$data['preferences_category3'];
			$data_array['pref_category4']=$data['preferences_category4'];
		}
		$myPDO=null;
		return $data_array;	
	}
?> 

<!--Enregister les modifications d'un compte utilisateur (exemple: l'utilisateur veut changer son pseudo, mot de passe etc.. -->
<?php
	function registerDataModification($civility,$pseudo,$email,$password,$password_confirm,$last_name,$first_name,$pref_category,$myPDO){		
		if((strlen($pseudo)!=0) AND (strlen($email)!=0)){
			if((strlen($password)>7) AND (strlen($password)<51)){
				if($password_confirm==$password){
					$query="UPDATE client_account SET civility='".$civility."',first_name='".$first_name."',last_name='".$last_name."',pseudo='".$pseudo."'
							,preferences_category1='".$pref_category[0]."',preferences_category2='".$pref_category[1]."',preferences_category3='".$pref_category[2]."',
							preferences_category4='".$pref_category[3]."',password='".$password."' WHERE email='".$email."'";
					$result=$myPDO->prepare($query);
					$result->execute();
					
					if(result){
						$myPDO=null;
						$_SESSION['email']=$email;
						$_SESSION['password']=$password;
						header('location:./profil.php');
						return;
					}
					else{
						$myPDO=null;
						header('location:./profil.php?error=There was a problem with the database, please restart your changes.');
						return;
					}
				}		
				else{
					$myPDO=null;
					header('location:./profil.php?error=Please enter the same password in both fields.');
					return;
				}
			}
			else{
				$myPDO=null;
				header('location:./profil.php?error=The password must contain between 8 and 50 characters.');
				return;
			}
		}
		else{
			$myPDO=null;
			header('location:./inscription.php?error=The username and the email must not be empty');
			return;
		}
	}
?>

<!--Connexion au compte d'un utilisateur-->
<?php
	function connexionToProfil($myPDO,$locationHeaderIf,$locationHeaderElse,$email,$password){
		$query="SELECT COUNT(id_client) AS is_client_exist FROM client_account WHERE email='".$email."' AND password='".$password."'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			if($data['is_client_exist']==0){
				$query="SELECT COUNT(id_client) AS is_email_exist FROM client_account WHERE email='".$email."'";
				$query=$myPDO->query($query);
				while($data=$query->fetch()){
					if($data['is_email_exist']==0){
						$myPDO=null;
						header($locationHeaderElse."?error=ERROR: Invalid mail.");
						return;
					}
					else{
						$myPDO=null;
						return;
					}
				}
			}
			else if($data['is_client_exist']==1){
				$_SESSION['email']=$email;
				$_SESSION['password']=$password;
				$_SESSION['visualization']=array('id_book'=>array(),'category_book'=>array());
				$myPDO=null;
				header($locationHeaderIf);
				return;	
			}
		}	
	}
?>

<!--Enregister le livre en favoris pour le compte de l'utisateur-->

<?php
	function registerBook($myPDO,$id_book,$email,$password){
		$bool=0;
		$query="SELECT id_client FROM client_account WHERE email='$email' AND password='$password'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$id_client=$data['id_client'];
		}
		
		$query="SELECT COUNT(id_client) AS has_already_register_book FROM record WHERE id_book='$id_book' AND id_client='$id_client'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			if($data['has_already_register_book']!=0){
				header('location:./book.php?id='.$id_book.'&error=register');
				return;
			}
		}
		
		$query="INSERT INTO record(id_client,id_book) VALUES ('".$id_client."','".$id_book."')";
		$result=$myPDO->prepare($query);
		$result->execute();	
		
		$myPDO=NULL;
	}
?>

<!-- Enregistrer la note de l'utilisateur pour un livre donné-->
<?php
	function registerRatingUser($myPDO,$id_book,$rating,$email,$password){
		$bool=0;
		$nbr_reviews=0;
		$query="SELECT id_client FROM client_account WHERE email='$email' AND password='$password'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$id_client=$data['id_client'];
		}
		
		$query="SELECT COUNT(id_book) AS has_already_rating_recipe FROM assess WHERE id_book='$id_book' AND id_client='$id_client'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			if($data['has_already_rating_recipe']!=0){
				header('location:./book.php?id='.$id_book.'&error=rating');
				return;
			}
		}
		
		$query="INSERT INTO assess(id_book,id_client,mark) VALUES ('".$id_book."','".$id_client."','".$rating."')";		
		$result=$myPDO->prepare($query);
		$result->execute();	
	
		$myPDO=NULL;
	}
?>

<!--Afficher la moyenne des notes d'un livre -->
<?php
	function printRating($myPDO,$id_book){
		$query ="SELECT AVG(mark) AS score,COUNT(mark) AS nbr_reviews FROM assess WHERE id_book='$id_book'";
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$score=$data['score'];
			$nbr_reviews=$data['nbr_reviews'];
		}
		if($nbr_reviews>0){
			echo "<td style='padding-left:3em;padding-top:0.5em'>";
				$score=round($score,1);
				$score= sprintf("%.1f",$score);
				$score_tab=explode(".",$score);
				$score_before_comma=$score_tab[0];
				$tmp=0;
				$score_after_comma=$score_tab[1];
			
				echo "<p style='text-align:center;color:#FFE4BA'>";
					while($score_before_comma>0){
						echo "<label style='font-size:26px;color:#fd4' class='fas fa-star'></label>";
						$score_before_comma--;
						$tmp++;
					}
					if($score_after_comma>=5){
						echo "<label style='font-size:26px;color:#fd4' for='rate-5' class='fas fa-star-half'></label>";
						$tmp++;
					}
					while($tmp<5){
						echo "<label style='font-size:20px;color:grey' class='fas fa-star'></label>";
						$tmp++;
					}
				echo "</p>";
			echo "</td>";
			echo "<td style='padding-left:1em;padding-top:0.5em''><p>".$score." / 5 </p></td>";
			if($nbr_reviews==1){
				echo "<td style='padding-left:1em;padding-top:0.5em''><p style='font-size:18px'>".$nbr_reviews." rating</p></td>";
			}
			else{
				echo "<td style='padding-left:1em;padding-top:0.5em''><p style='font-size:18px'>".$nbr_reviews." ratings</p></td>";
			}
			$myPDO=null;
		}
	}
?>

<!--Afficher le nombre de notes d'un livre
<?php
	function getNumberBooksRecording($myPDO,$email,$password){
		$id_client=getIDclient($myPDO,$email,$password);
		$select="SELECT COUNT(id_book) AS total_books FROM record WHERE id_client='$id_client'";
		$query=$myPDO->query($select);
		while($data=$query->fetch()){
			$total_books=$data['total_books'];
		}
		return $total_books;
		
	}
?>

<!--Recuperer le nombre de livres mis en favoris de l'utilisateur-->
<?php
	function getNbrRegister($myPDO,$email,$password){
		$id_client=getIDclient($myPDO,$email,$password);
		$select= "SELECT COUNT(record.id_book) AS total_books FROM record INNER JOIN book ON record.id_book=book.id_book WHERE record.id_client='$id_client'" ;
		$query=$myPDO->query($select);
		while($data=$query->fetch()){
			$total_books_record=$data['total_books'];
		}
		return $total_books_record;
	}
?>

<!-- Recuperer l'id de l'utilisateur-->
<?php
	function getIDclient($myPDO,$email,$password){	
		$select="SELECT id_client FROM client_account WHERE email='$email' AND password='$password'";
		$query=$myPDO->query($select);
		while($data=$query->fetch()){
			$id_client=$data['id_client'];
		}
		return $id_client;
	}
?>

<!--Afficher les livres d'une page (books.php)-->
<?php
	function printBooks($myPDO,$email,$password,$pages,$current_page,$per_pages,$first){
		$id_client=getIDclient($myPDO,$email,$password);
		$select="SELECT book.id_book AS id_book,title,cover FROM book INNER JOIN record ON record.id_book=book.id_book
			WHERE record.id_client='$id_client' LIMIT $per_pages OFFSET $first";
		$query=$myPDO->query($select);
		while($data=$query->fetch()){
			$id_book =$data['id_book'];
			$title =$data['title'];
			$cover =$data['cover'];

			echo "<div class='books-block'>";
				echo "<img  class='img-books' src='$cover' alt='logo-book'>";
				echo "<div class='book-link-block'>";
					echo "<a class='link-book-a' href='../research/book.php?id=$id_book'>".$title."</a>";
					echo "<p><a class='delete-book-a' href='./favoriteBooks.php?idBook=$id_book'>Delete</a></p>";
				echo "</div>";
			echo "</div>";	
		}
	}
?>

<!--Supprimer un livre mis en favoris par un utilisateur-->
<?php 
	function deleteBook($myPDO,$email,$password,$id_book){
		$id_client=getIdClient($myPDO,$email,$password);
		$delete="DELETE FROM record WHERE id_client='".$id_client."' AND id_book='".$id_book."'";	
		$query=$myPDO->prepare($delete);
		$query->execute();
		header('location:./favoriteBooks.php');
	}
?>

<!-- Recuperer les livres similaires d'un livre visualisé (book.php) -->
<?php
	function getSimilarBooks($myPDO,$id_book,$category){
		$similar_book_tmp=array('id'=>array(),'title'=>array(),'cover'=>array(),'cpt_similarity'=>array());
		$similar_book=array('id'=>array(),'title'=>array(),'cover'=>array());
		$i=0;
		
		$query="SELECT book.id_book,book.title,book.cover,COUNT(book.id_book) AS cpt_similarity FROM book 
				INNER JOIN contain ON contain.id_book=book.id_book 
				INNER JOIN keyword ON keyword.id_keyword=contain.id_keyword AND keyword.id_keyword IN( 
					SELECT keyword.id_keyword FROM keyword 
					INNER JOIN contain ON contain.id_keyword=keyword.id_keyword 
					INNER JOIN book ON contain.id_book=book.id_book 
					WHERE book.id_book='".$id_book."'
				) 
				WHERE book.category='".$category."' AND book.id_book <> '".$id_book."'
				GROUP BY(book.id_book) ORDER BY cpt_similarity DESC";
		
		$query=$myPDO->query($query);
		
		while($data=$query->fetch()){
			$similar_book_tmp['id'][$i]=$data['id_book'];
			$similar_book_tmp['cpt_similarity'][$i]=$data['cpt_similarity'];
			$similar_book_tmp['title'][$i]=$data['title'];
			$similar_book_tmp['cover'][$i]=$data['cover'];
			$i++;
		}
		$myPDO=null;
		return $similar_book_tmp;
	}
?>

<?php
	function getBooksInputSearch($myPDO,$search){
		$research_books=array('id'=>array(),'cover'=>array(),'title'=>array());
		$search=explode(' ',$search);
		$j=0;

		if(count($search)<1){
			return $research_books;
		}
		$query="SELECT id_book,cover,title FROM book WHERE title LIKE '%".$search[0]."%'";
		
		for($i=1;$i<count($search);$i++){
			$query.=" AND title LIKE '%".$search[$i]."%'";
		}
		$query=$myPDO->query($query);
		
		while($data=$query->fetch()){
			$research_books['id'][$j]=$data['id_book'];
			$research_books['title'][$j]=$data['title'];
			$research_books['cover'][$j]=$data['cover'];
			$j++;
		}
		$myPDO=null;
		return $research_books;
	}
?>
