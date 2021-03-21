<?php
	function getGlobalSuggestions($myPDO,$email,$password){
		$id_client=getIdClient($myPDO,$email,$password);
		$favorites_history_books_user=getArrayFavoritesHistoricBooks($myPDO,$id_client);
		$rated_books_user=getArrayRatedBooks($myPDO,$id_client);
		$visualization_books_user=getArrayVisualizationBooks();
		$three_best_categories=getBestCombinationCategoriesGlobalSuggestions($myPDO,$id_client,$favorites_history_books_user,$rated_books_user,$visualization_books_user);
		$books_user=getAllBooksOfThreeBestCategories($myPDO,$id_client,$three_best_categories,$visualization_books_user);
		$books_similar=array('id_book'=>array(),'category_book'=>array(),'cpt_similarity'=>array(),'title'=>array(),'cover'=>array());
		$books_suggestions=array('id_book'=>array(),'category_book'=>array(),'title'=>array(),'cover'=>array());
		$j=0;
		$bool=0;
		for($i=0;$i<count($books_user['id_book']);$i++){
			$query="SELECT book.title,book.id_book,book.category, cover,COUNT(book.id_book) AS cpt_similarity FROM book
					INNER JOIN contain ON contain.id_book=book.id_book
					INNER JOIN keyword ON keyword.id_keyword=contain.id_keyword
					WHERE book.category='".$books_user['category_book'][$i]."'
						AND keyword.id_keyword IN (
							SELECT keyword.id_keyword FROM keyword
							INNER JOIN contain ON contain.id_keyword=keyword.id_keyword
							INNER JOIN book ON book.id_book=contain.id_book
							WHERE book.id_book='".$books_user['id_book'][$i]."'
						)
						AND book.id_book NOT IN(
							SELECT book.id_book FROM book WHERE book.id_book='".$books_user['id_book'][$i]."'
						)
					GROUP BY(book.id_book) ORDER BY cpt_similarity DESC";	
			$query=$myPDO->query($query);
			while($data=$query->fetch()){
				$books_similar['category_book'][$j]=$data['category'];
				$books_similar['id_book'][$j]=$data['id_book'];
				$books_similar['cpt_similarity'][$j]=$data['cpt_similarity'];
				$books_similar['title'][$j]=$data['title'];
				$books_similar['cover'][$j]=$data['cover'];
				$j++;
			}
		}
		$myPDO=null;
		
		if(count($books_similar['id_book'])<10){
			$k=count($books_similar['id_book']);
		}
		else{
			$k=10;
		}
		
		$i=0;
		$m=0;
		if(count($books_similar['id_book'])<10){
			$k=count($books_similar['id_book']);
		}
		else{
			$k=10;
		}
		while($i<$k){
			$index_similarity=0;
			for($j=0;$j<count($books_similar['id_book']);$j++){
				if($index_similarity<$books_similar['cpt_similarity'][$j]){
					$index_similarity=$j;
				}
			}
			for($l=0;$l<count($books_suggestions['id_book']);$l++){
				if ($books_suggestions['id_book'][$l]==$books_similar['id_book'][$index_similarity]){
					$books_similar['cpt_similarity'][$index_similarity]=-1;
					$bool=1;
					break;
				}
			}
			if($bool==0){
				$books_suggestions['id_book'][$i]=$books_similar['id_book'][$index_similarity];
				$books_suggestions['category_book'][$i]=$books_similar['category_book'][$index_similarity];
				$books_suggestions['title'][$i]=$books_similar['title'][$index_similarity];
				$books_suggestions['cover'][$i]=$books_similar['cover'][$index_similarity];
				$books_similar['cpt_similarity'][$index_similarity]=-1;
				$i++;
			}
				
			$bool=0;
		}
		return $books_suggestions;
	}
?>
<?php
	function getAllBooksOfThreeBestCategories($myPDO,$id_client,$three_best_categories,$visualization_books_user){
		$books_user=array('id_book'=>array(),'category_book'=>array());
		$array=array('assess','record','visualization');
		$i=0;
		foreach($array as $table){
			if($table=='visualization'){
				for($j=0;$j<count($visualization_books_user['id_book']);$j++){
					for($k=0;$k<3;$k++){
						if($visualization_books_user['category_book'][$j]== $three_best_categories['category'][$k]){
							$books_user['id_book'][$i]=$visualization_books_user['id_book'][$j];
							$books_user['category_book'][$i]=$visualization_books_user['category_book'][$j];
							break;
						}
					}
				}
			}
			else{
				$query="SELECT book.id_book,book.category FROM book
						INNER JOIN ".$table." ON ".$table.".id_book=book.id_book
						WHERE ".$table.".id_client='".$id_client."' AND (book.category='".$three_best_categories['category'][0]."' 
							OR book.category='".$three_best_categories['category'][1]."' OR book.category='".$three_best_categories['category'][2]."')
						ORDER BY (book.category)";
				$query=$myPDO->query($query);
				while($data=$query->fetch()){
					$books_user['id_book'][$i]=$data['id_book'];
					$books_user['category_book'][$i]=$data['category'];
					$i++;
				}
			}
			$myPDO=null;
			return $books_user;;
		}
	}
?>

<?php
	function getBestCombinationCategoriesGlobalSuggestions($myPDO,$id_client,$favorites_history_books_user,$rated_books_user,$visualization_books_user){
		$combinations=array('coeff'=>array(),'categories'=>array(array('category'=>array())));
		$personal_preferences=getAllPreferencesCategories($myPDO,$id_client);
		$coeff=0;
		$m=0;

		if(count($favorites_history_books_user['categories'])<1){
			$favorites_history_books_user=getThreeRandomPersonal_preferences_categories($personal_preferences);
		}
		if(count($rated_books_user['categories'])<1){
			$rated_books_user=getThreeRandomPersonal_preferences_categories($personal_preferences);
		}
		if(count($visualization_books_user['categories'])<1){
			$visualization_books_user=getThreeRandomPersonal_preferences_categories($personal_preferences);
		}
		
		$total_categories=getTotalCategoriesWithSpawnCounter($favorites_history_books_user,$rated_books_user,$visualization_books_user);
		
		for($i=0;$i<count($favorites_history_books_user['categories']);$i++){
			$priority_fh=$favorites_history_books_user['priority'][$i];
			$category_fh=$favorites_history_books_user['categories'][$i];
			for($j=0;$j<count($total_categories['category']);$j++){
				if($category_fh==$total_categories['category'][$j]){
					$counter_category_fh=$total_categories['counter'][$j];
					break;
				}
			}
			for($j=0;$j<count($rated_books_user['categories']);$j++){
				$priority_r=$rated_books_user['priority'][$j];
				$category_r=$rated_books_user['categories'][$j];
				for($k=0;$k<count($total_categories['category']);$k++){
					if($category_r==$total_categories['category'][$k]){
						$counter_category_r=$total_categories['counter'][$k];
						break;
					}
				}	
				for($k=0;$k<count($visualization_books_user['categories']);$k++){
					$priority_v=$visualization_books_user['priority'][$k];
					$category_v=$visualization_books_user['categories'][$k];
					for($l=0;$l<count($total_categories['category']);$l++){
						if($category_v==$total_categories['category'][$l]){
							$counter_category_v=$total_categories['counter'][$l];
							break;
						}
					}	
					$coeff=($priority_fh*$counter_category_fh)+($priority_r*$counter_category_r)+($priority_v*$counter_category_v);
					$combinations['coeff'][$m]=$coeff;
					$combinations['categories'][$m]['category'][0]=$category_fh;
					$combinations['categories'][$m]['category'][1]=$category_r;
					$combinations['categories'][$m]['category'][2]=$category_v;
					$m++;
				}
			}
		}
		$m=0;
		for($i=0;$i<count($combinations['coeff']);$i++){
			$category1=$combinations['categories'][$m]['category'][0];
			$category2=$combinations['categories'][$m]['category'][1];
			$category3=$combinations['categories'][$m]['category'][2];
			if(($category1==$category2) OR ($category1==$category3) OR ($category2==$category3)){
				$combinations['coeff'][$i]=-1;
			}
		}

		$coeff_max=$combinations['coeff'][0];
		$index_coeff_max=0;
		for($i=1;$i<count($combinations['coeff']);$i++){
			$coeff=$combinations['coeff'][$i];
			if($coeff>$coeff_max){
				$coeff_max=$combinations['coeff'][$i];
				$index_coeff_max=$i;
			}
			else if($coeff==$coeff_max){
				$counter_personal_categories_coeff_max=0;
				$counter_personal_categories_coeff=0;
				for($j=0;$j<count($personal_preferences);$j++){
					for($j=0;$j<3;$j++){
						if($combinations['categories'][$i]==$personal_preferences[$j]){
							$counter_personal_categories_coeff+=1;
						}
						if($combinations['categories'][$index_coeff_max]==$personal_preferences[$j]){
							$counter_personal_categories_coeff_max+=1;
						}
					}
				}
				if($counter_personal_categories_coeff>$counter_personal_categories_coeff_max){
					$coeff_max=$combinations['coeff'][$i];
					$index_coeff_max=$i;
				}
				else if($counter_personal_categories_coeff==$counter_personal_categories_coeff_max){
					$rand=rand(0,1);
					if($rand==0){
						$coeff_max=$combinations['coeff'][$i];
						$index_coeff_max=$i;
					}
				}
			} 
		}
		return $combinations['categories'][$index_coeff_max];
	}
?>
<?php
	function getAllPreferencesCategories($myPDO,$id_client){
		$personal_preferences=array('category');
		$j=0;
		$query="SELECT preferences_category1, preferences_category2, preferences_category3, preferences_category4 FROM `client_account` 
				WHERE id_client='".$id_client."'";
		$query=$myPDO->query($query);
		
		while($data=$query->fetch()){
			$personal_preferences[0]=$data['preferences_category1'];
			$personal_preferences[1]=$data['preferences_category2'];
			$personal_preferences[2]=$data['preferences_category3'];
			$personal_preferences[3]=$data['preferences_category4'];
		}
		$myPDO=null;
		return $personal_preferences;
	}
?>

<?php
	function getThreeRandomPersonal_preferences_categories($personal_preferences){
		$random_personal_preferences=array('categories'=>array(),'priority'=>array());
		$rand_keys = array_rand($personal_preferences, 3);
		for($i=0;$i<3;$i++){
			$random_personal_preferences['categories'][$i]=$personal_preferences[$i];
			$random_personal_preferences['priority'][$i]=1;
		}
		return $random_personal_preferences;
	}
?>
<?php
	function getTotalCategoriesWithSpawnCounter($favorites_history_books_user,$rated_books_user,$visualization_books_user){
		$j=0;
		$total_categories=array();
		$total_categories_counter=array('category'=>array(),'counter'=>array());

		for($i=0;$i<count($favorites_history_books_user['categories']);$i++){
			$total_categories[$j]=$favorites_history_books_user['categories'][$i];
			$j++;
		}
		
		for($i=0;$i<count($rated_books_user['categories']);$i++){
			$total_categories[$j]=$rated_books_user['categories'][$i];
			$j++;
		}
		
		for($i=0;$i<count($visualization_books_user['categories']);$i++){
			$total_categories[$j]=$visualization_books_user['categories'][$i];
			$j++;
		}
		
		$total_categories_counter['category'][0]=$total_categories[0];
		$total_categories_counter['counter'][0]=1;
		$bool=0;

		for($i=1;$i<count($total_categories);$i++){
			for($j=0;$j<count($total_categories_counter['category']);$j++){
				if($total_categories_counter['category'][$j]==$total_categories[$i]){
					$total_categories_counter['counter'][$j]+=1;
					$bool=1;
				}
			}
			if($bool==0){
				$total_categories_counter['category'][$j]=$total_categories[$i];
				$total_categories_counter['counter'][$j]=1;
			}
			$bool=0;
		}
		return $total_categories_counter;
	}
?>

<?php
	function getArrayVisualizationBooks(){
		$visualization_books_user=array('id_book'=>array(),'category_book'=>array(),'priority'=>array(),'categories'=>array());
		$visualization=$_SESSION['visualization'];
		
		if(count($visualization['id_book'])>0){
			$categories=array('category'=>array(),'counter'=>array());
			$categories_tmp=array('category'=>array(),'priority'=>array());
			
			$categories['category'][0]=$visualization['category_book'][0];
			$categories['counter'][0]=1;
			$bool=0;
			$k=3;
			$l=0;
			$m=1;
		
			for($i=1;$i<count($visualization['category_book']);$i++){
				for($j=0;$j<count($categories['category']);$j++){
					if($categories['category'][$j]==$visualization['category_book'][$i]){
						$categories['counter'][$j]+=1;
						$bool=1;
					}
				}
				if($bool==0){
					$categories['category'][$j]=$visualization['category_book'][$i];
					$categories['counter'][$j]=1;
					$m++;
				}
				$bool=0;
			}
	
			for($i=0;$i<$m;$i++){
				$indice_max_counter_category=0;
				for($j=0;$j<count($categories['category']);$j++){
					if($categories['counter'][$j]>$categories['counter'][$indice_max_counter_category]){
						$indice_max_counter_category=$j;
					}
				}
				$categories_tmp['category'][$i]=$categories['category'][$indice_max_counter_category];
				$categories_tmp['priority'][$i]=$k;
				$k--;
				$categories['counter'][$indice_max_counter_category]=-1;
			}

			$k=3;
			for($i=0;$i<$m;$i++){
				$category=$categories_tmp['category'][$i];
				$priority=$categories_tmp['priority'][$i];
				for($j=0;$j<count($visualization['category_book']);$j++){
					if($visualization['category_book'][$j]==$category){
						$visualization_books_user['id_book'][$l]=$visualization['id_book'][$j];
						$visualization_books_user['category_book'][$l]=$category;
						$l++;
					}
				}
				$visualization_books_user['priority'][$i]=$k;
				$visualization_books_user['categories'][$i]=$category;
				$k--;
			}
		}
		return $visualization_books_user;
	}
?>

<?php 
	function getArrayFavoritesHistoricBooks($myPDO,$id_client){
		/*Recupere la catégorie la plus importante dans les livres mis en favoris par l'utilisateur*/
		$favorites_history_categories=getBestCategoriesFavoritesHistory($myPDO,$id_client,3);
		/*Recupere les livres mis en favorie avec comme catégorie celle la plus elevé*/
		$favorites_history_books_user=getFavoritesHistoryBooks($myPDO,$id_client,$favorites_history_categories['category']);
		return $favorites_history_books_user;
	}	
?>

<?php 
	function getArrayRatedBooks($myPDO,$id_client){
		$rated_categories=getBestRatedCategories($myPDO,$id_client);
		$rated_books_user=getRatedBooks($myPDO,$id_client,$rated_categories['category']);
		return $rated_books_user;
	}	
?>

<?php
	function getBestRatedCategories($myPDO,$id_client){
		/*getIdClient --> fonction_mysql.inc.php*/
		$rated_categories=array('category'=>array());
		$i=0;
		
		$query="SELECT category,AVG(mark) FROM assess
				INNER JOIN book ON book.id_book=assess.id_book
				WHERE assess.id_client='".$id_client."'
				GROUP BY (category)ORDER BY AVG(mark) DESC LIMIT 3";
				
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$rated_categories['category'][$i]=$data['category'];
			$i++;
		}
		$myPDO=null;
		return $rated_categories;
	}
?>

<?php 
	function getRatedBooks($myPDO,$id_client,$best_categories){
		$rated_books=array('id_book'=>array(),'category_book'=>array(),'priority'=>array(),'categories'=>array());
		$j=0;
		$k=3;
		
		for($i=0;$i<count($best_categories);$i++){
			$category=$best_categories[$i];
			$query="SELECT assess.id_book, book.category FROM book
					INNER JOIN assess ON assess.id_book=book.id_book
					WHERE assess.id_client='".$id_client."' AND book.category='".$category."' 
					ORDER BY book.category";
	
			$query=$myPDO->query($query);
		
			while($data=$query->fetch()){
				$rated_books['id_book'][$j]=$data['id_book'];
				$rated_books['category_book'][$j]=$data['category'];
				$j++;
			}
			$rated_books['priority'][$i]=$k;
			$rated_books['categories'][$i]=$category;
			$k--;
		}
		return $rated_books;		
	}
?>