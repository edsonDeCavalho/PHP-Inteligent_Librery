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

<?php
	function getBooksFromResearch($myPDO,$category,$number_category,$release_date){
		if($category!="none"){
			$array_books=array('id'=>array(),'cover'=>array(),'title'=>array());
			$i=0;
		
			if($number_category>0){
				$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category[$i]."'";

				for($i=1;$i<$number_category;$i++){
					$query.=" OR category='".$category[$i]."' ";
				}
				
				switch($release_date){
					case "t":
						$release_date=date('Y');
						$query.=" AND (LEFT(date_publication, 4) IN ('".$release_date."')) ORDER BY(category)";	
						break;
					case "b":
						$query.=" AND (LEFT(date_publication, 4)<1980) ORDER BY(category)";	
						break;
					case "all":
						break;
					default:
						$release_date=explode('-',$release_date);
						$query.=" AND (LEFT(date_publication, 4) BETWEEN ".$release_date[0]." AND ".$release_date[1].") ORDER BY(category)";	
				}
			}
			else{
				if($release_date!="none"){
					switch($release_date){
						case "t":
							$release_date=date('Y');
							$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category."' 
								AND (LEFT(date_publication, 4) IN ('".$release_date."'))";	
							break;
						case "b":
							$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category."' 
								AND (LEFT(date_publication, 4)<1980)";	
							break;
						case "all":
							break;
						default:
							$release_date=explode('-',$release_date);
							$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category."' 
								AND (LEFT(date_publication, 4) BETWEEN ".$release_date[0]." AND ".$release_date[1].")";	
					}
				}
				else{
					$query="SELECT book.id_book,title,cover FROM book WHERE category='".$category."'";	
				}
			}
		}
		else{
			switch($release_date){
					case "t":
						$release_date=date('Y');
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4) IN ('".$release_date."'))";
						break;
					case "b":
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4)<1980)";
						break;
					case "all":
						break;
					default:
						$release_date=explode('-',$release_date);
						$query="SELECT book.id_book,title,cover FROM book WHERE (LEFT(date_publication, 4) 
							BETWEEN ".$release_date[0]." AND ".$release_date[1].")";
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

<?php
	function getTotalPages($array_books){
		$total_books=count($array_books['id']);
		
		if($total_books<=0){
			$pages=0;
		}
		else{
			$pages=($total_books/16);
			$pages=round($pages, 0, PHP_ROUND_HALF_DOWN);
		}
		return $pages;
	}
?>

<?php
	function printBooksResearch($array_books,$begin){
		$htmlString="<table style='margin:0 auto;margin-top:2em;margin-left:0.7em'>";
		$htmlString.="<tr>";
		
		$total_books=count($array_books['id']);
		$k=$begin;
		$kk=$begin;
		$books_by_page=16;
		
		for($i=0;$i<$books_by_page+1;$i++){	
			if($k<$total_books){		
				if(($i%4==0) && ($i>0)){
					$htmlString.="</tr><tr>";
					$j=$i-4;
					for($j;$j<$i;$j++){
						$title=$array_books['title'][$kk];
						$htmlString.="<td style='padding-right:4em'>
										<div style='word-wrap: break-word;width:13em'>
											<p style='margin-top:0.5em'>".$title."</p>
										</div>
									</td>";
						$kk++;
					}
					$htmlString.="</tr><tr>";	
				}
				if($i<$books_by_page){
					$id_book=$array_books['id'][$k];
					$cover=$array_books['cover'][$k];
					
					$htmlString.="<td style='padding-top:2em'>
									<a href='./book.php?id=".$id_book."'><img src='".$cover."' width='170' height='220' alt='Image-book'></a>
								</td>";
					$k++;	
				}
			}
			else{
				break;
			}
		}		
		if($kk!=$k){
			$htmlString.="<tr>";
			for($j=$kk;$j<$k;$j++){
				$title=$array_books['title'][$j];
				$htmlString.="<td style='padding-right:4em'>
								<div style='word-wrap: break-word;width:13em'>
									<p style='margin-top:0.5em'>".$title;"</p>
								</div>
							</td>";
			}
			$htmlString.="</tr>";
			$kk=0;
		}
		else{
			$htmlString.="</tr>";
		}
		$htmlString.="</table>";
	
		echo $htmlString;
	}
?>

<?php
	function pagination($category,$current_page,$total_pages,$release_date){
		if(is_array($category)){
			$string_category=$category[0];
			for($i=1;$i<count($category);$i++){
				$string_category.=":".$category[$i];
			}
			$category=$string_category;
			
		}
			
		$html_string="<nav style='text-align:center'><ul style ='justify-content:center' class='pagination'>";
		if ($current_page>1){
			$previous_page=$current_page-1;
			$html_string.="<li class='page-item'>
							<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;page=$previous_page' aria-label='Previous'>
								<span aria-hidden='true'>&laquo;</span>
								<span class='sr-only'>Previous</span>
							</a>
						</li>";						
		}
		
		for($i=1;$i<=$total_pages;$i++){
			if($current_page==$i){
				$html_string.="<li class='page-item'><a class='page-link' href='#'>".$i."</a></li>";
			}
			else{
				$html_string.="<li class='page-item'><a class='page-link' href='./books?category=$category&amp;release=$release_date&amp;page=$i'>".$i."</a></li>";
			}
		}
		if ($current_page<$total_pages){
			$next_page=$current_page+1;	
			$html_string.="<li class='page-item'>
								<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;page=$next_page' aria-label='Next'>
									<span aria-hidden='true'>&raquo;</span>
									<span class='sr-only'>Next</span>
								</a>
							</li>";
		}

		$html_string.="</ul>";
		$html_string.="</nav>";
		echo $html_string;
	}
?>

<?php
	function pagination_favorite($current_page,$total_pages){
		$html_string="<nav style='text-align:center'><ul style ='justify-content:center' class='pagination'>";
		if ($current_page>1){
			$previous_page=$current_page-1;
			$html_string.="<li class='page-item'>
							<a class='page-link' href='./favoriteBooks.php?page=$previous_page' aria-label='Previous'>
								<span aria-hidden='true'>&laquo;</span>
								<span class='sr-only'>Previous</span>
							</a>
						</li>";						
		}
		
		for($i=1;$i<=$total_pages;$i++){
			if($current_page==$i){
				$html_string.="<li class='page-item'><a class='page-link' href='#'>".$i."</a></li>";
			}
			else{
				$html_string.="<li class='page-item'><a class='page-link' href='./favoriteBooks.php?page=$i'>".$i."</a></li>";
			}
		}
		if ($current_page<$total_pages){
			$next_page=$current_page+1;	
			$html_string.="<li class='page-item'>
								<a class='page-link' href='./favoriteBooks.php?page=$next_page' aria-label='Next'>
									<span aria-hidden='true'>&raquo;</span>
									<span class='sr-only'>Next</span>
								</a>
							</li>";
		}

		$html_string.="</ul>";
		$html_string.="</nav>";
		echo $html_string;
	}
?>

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
				$myPDO=null;
				header($locationHeaderIf);
				return;	
			}
		}	
	}
?>

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
				$score_after_comma=$score_tab[1];
			
				echo "<p style='text-align:center;color:#FFE4BA'>";
					while($score_before_comma>0){
						echo "<label style='font-size:26px;color:#fd4' class='fas fa-star'></label>";
						$score_before_comma--;
					}
					if($score_after_comma>=5){
						echo "<label style='font-size:26px;color:#fd4' for='rate-5' class='fas fa-star-half'></label>";
					}
					$rest =5-$score;
					$rest= sprintf("%.1f",$rest);
					$rest=explode(".",$rest);
					$score_before_comma=$rest[0];
					while($score_before_comma>0){
						echo "<label style='font-size:20px;color:grey' class='fas fa-star'></label>";
						$score_before_comma--;
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

<?php 
	function deleteBook($myPDO,$email,$password,$id_book){
		$id_client=getIdClient($myPDO,$email,$password);
		$delete="DELETE FROM record WHERE id_client='".$id_client."' AND id_book='".$id_book."'";	
		$query=$myPDO->prepare($delete);
		$query->execute();
		header('location:./favoriteBooks.php');
	}
?>