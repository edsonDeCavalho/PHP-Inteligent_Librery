<!--Recuperer le nombre de catégories différents des livres livres mis en favoris par l'utilisateur -->
<?php
	function getNumberCategories($favorites_history_categories){
		return count($favorites_history_categories['category']);
	}
?>

<!--Récuperer la catégorie mis en favoris par l'utilisateur avec le compteur d'apparition le plus elevé
Exemple : Harry Potter : Fiction
		  Nomad Soul : Biography
		  Soul suspect : Fiction
		  => La fonction renvoie : Fiction 2 (2=nombre d'apparition de la catégorie fiction=
NOTES: Il se peut que deux catégories ont le même compteur d'apparition le plus elevé: Problème à résoudre-->
							
<?php
	function getBestCategoriesFavoritesHistory($myPDO,$id_client,$number_categories){		
		$favorites_history_categories=array('category'=>array(),'cpt_category'=>array());
		$i=0;
		
		/*DESC LIMIT 1 permet de récuperer la première ligne qui est la catégorie avec le compteur d'apparition le plus elevé*/
		$query="SELECT category,COUNT(book.id_book) AS cpt_category FROM record
				INNER JOIN book ON book.id_book=record.id_book
				WHERE id_client='".$id_client."'
				GROUP BY(category) 
				HAVING COUNT(book.id_book)>0 
				ORDER BY cpt_category DESC LIMIT ".$number_categories."";
				
		$query=$myPDO->query($query);
		while($data=$query->fetch()){
			$favorites_history_categories['category'][$i]=$data['category'];
			$favorites_history_categories['cpt_category'][$i]=$data['cpt_category'];
			$i++;
		}
		$myPDO=null;
		return $favorites_history_categories;
	}
?>

<!--Récuperer les livres mis en favoris qui ont la catégorie la plus elevé ($categories)-->
<?php 
	function getFavoritesHistoryBooks($myPDO,$id_client,$best_categories){
		$favorites_history_books=array('id_book'=>array(),'category_book'=>array(),'priority'=>array(),'categories'=>array());
		$j=0;
		$k=3;

		for($i=0;$i<count($best_categories);$i++){
			$category=$best_categories[$i];
			$query="SELECT record.id_book, book.category FROM book
					INNER JOIN record ON record.id_book=book.id_book
					WHERE record.id_client='".$id_client."' AND book.category='".$category."'
					ORDER BY book.category";
			$query=$myPDO->query($query);
		
			while($data=$query->fetch()){
				$favorites_history_books['id_book'][$j]=$data['id_book'];
				$favorites_history_books['category_book'][$j]=$data['category'];
				$j++;
			}
			$favorites_history_books['priority'][$i]=$k;
			$favorites_history_books['categories'][$i]=$category;
			$k--;
		}
		return $favorites_history_books;		
	}
?>

<!--Fonction principal (main)
Renvoie une liste de 4 livres similaire à un livre mis en favoris-->
<?php 
	function getSuggestionBecauseYouLike($myPDO,$email,$password){
		/*getIdClient --> fonction_mysql.inc.php*/
		$id_client=getIdClient($myPDO,$email,$password);
		/*Recupere la catégorie la plus importante dans les livres mis en favoris par l'utilisateur*/
		$favorites_history_categories=getBestCategoriesFavoritesHistory($myPDO,$id_client,1);
		if(count($favorites_history_categories['category'])<1){
			return null;
		}
		/*Recupere les livres mis en favorie avec comme catégorie celle la plus elevé*/
		$favorites_history_books_user=getFavoritesHistoryBooks($myPDO,$id_client,$favorites_history_categories['category']);

			
		/*Tableau qui va contenir l'ensemble des livres similaire à un livre donné.
		
		Exemple Structure du tableau pour le livre Harry Potter (livre en favoris):
		Tableau=>{{id du livre Harry Potter (livre mis en favoris) =>{"GYnyubbFRRT"}
				 {moyenne des livres de la même catégorie (de la base de donnée) similaire à Harry Potter"} => {6.4}
				 {Categorie du livre "Harry Potter"} =>{"Fiction"}
			     {Livres similaires (livres de la bd) à "Harry Potter"} => {Id du livre similaire}=>{"HhyhjikiHYBDj"}
																		=> {Categorie du livre similaire} => {"Fiction"}
																		=> {compteur similarité avec "Harry Potter"} => {4
		}
		*/
		$similar_books=array('id_book_user'=>array(),'mean_similar_books'=>array(),'category_book_user'=>array(),
			'similar_books'=>array(array('id_similar_book'=>array(),'category_similar_book'=>array(),'cpt_similarity'=>array())));
		
		/*Tableau final qui va contenir le livre mis en favoris et 4 livres similaires*/
		$suggestions_books=array('id_similar_book'=>array(),'category'=>array(),'id_book_user'=>'');
		$mean=0;
		$j=0;
		$k=0;
		
		/*Ici le nombre de catégories favoris ($number) est égal à 1 donc la première boucle for ne sert a rien pour l'instant*/
		/*$number =1 car on recupère LA catégorie la plus importante dans les livres mis en favoris*/
		$number=getNumberCategories($favorites_history_categories);
		for($i=0;$i<$number;$i++){
			/*On recupere la category*/
			$category=$favorites_history_categories['category'][$i];
			/*$favorite_books_user['category'][$j] ==$category ---> Tout les livres mis en favoris qui ont comme categorie celle dans la variable $category*/
			while($favorites_history_books_user['category_book'][$j]==$category){
				/*On recupere l'id du livre similaire*/
				$id_book_user=$favorites_history_books_user['id_book'][$j];
				/*Requete qui permet d'extraire tout les livres de la bd qui ont la même categorie et qui sont similaires au livre favoris
				de l'utilisateur ($id_book_user). Pour chaque livre extrait on crée une colonne qui contient son degré de similarité avec
				le livre favoris de l'utilisateur. 
				La similarité se calcule avec les mots clé en concordance
				Exemple : "Harry Potter"(livre favoris) => "Harry Potter 3" -  5 (= nombre de mots clés en concordance)
				=>Donc "Harry Potter 3" a le degré de similarité 5 avec le livre "Harry Potter"
				*/
					
				$query="SELECT book.id_book,book.category, COUNT(book.id_book) AS cpt_similarity FROM book
						INNER JOIN contain ON contain.id_book=book.id_book
						INNER JOIN keyword ON keyword.id_keyword=contain.id_keyword
						WHERE book.category='".$category."'
							AND keyword.id_keyword IN (
								SELECT keyword.id_keyword FROM keyword
								INNER JOIN contain ON contain.id_keyword=keyword.id_keyword
								INNER JOIN book ON book.id_book=contain.id_book
								WHERE book.id_book='".$id_book_user."'
							)
							AND book.id_book NOT IN(
								SELECT book.id_book FROM book WHERE book.id_book='".$id_book_user."'
							)
						GROUP BY(book.id_book) ORDER BY cpt_similarity DESC";	
				
				$query=$myPDO->query($query);
		
				while($data=$query->fetch()){
					/*On calcule la moyenne de similarite du livre en favoris.
					Pour calculer la moyenne ont additionne le compteur de similarité de tout les livres similaires au livre favoris puis
					on divise au nombre total de livres similaire.
					Notes: le compteur de similarité est décroissant donc la première ligne est le livre le plus similaire
					Exemple :
					"Harry Potter"(livre favoris)	=> "Harry Potter 3" 5 
													=> "Harry Potter 6" 4 
													=> "Stranger guy" 4 
													=> "The lost" 2
													=> "Labyrinth" 1
					moyenne pour "Harry Potter" = 3.2
					*/
					$mean+=$data['cpt_similarity'];
					
					/*On stocke le livre similaire pour le livre favoris stocké dans l'indice $j du tableau*/
					$similar_books['similar_books'][$j]['id_similar_book'][$k]=$data['id_book'];
					$similar_books['similar_books'][$j]['category_similar_book'][$k]=$data['category'];
					$similar_books['similar_books'][$j]['cpt_similarity'][$k]=$data['cpt_similarity'];
					$k++;
				}
				if($k==0){
					$mean=0;
				}
				else{
					$mean/=$k;
				}

				$similar_books['id_book_user'][$j]=$id_book_user;
				$similar_books['mean'][$j]=$mean;
				$similar_books['category'][$j]=$category;
				$k=0;
				$mean=0;
				$j++;	
				if($j>=count($favorites_history_books_user['category_book'])){
					break;
				}
			}
		}
		/* On calcule lequel des livres favoris à la plus grande moyenne de livre similaires*/
		$mean_max_book=['id_book_user'=>$similar_books['id_book_user'][0],'mean'=>$similar_books['mean'][0]];
		
		for($i=1;$i<count($similar_books['id_book_user']);$i++){
			$mean=$similar_books['mean'][$i];
			$id_book_user=$similar_books['id_book_user'][$i];
			if($mean>$mean_max_book['mean']){
				$mean_max_book['id_book_user']=$id_book_user;
				$mean_max_book['mean']=$mean;
			}
		}
		
		/*A ce stade, nous avons l'id livre favoris qui posséde les meilleurs livres similaires
		On recupere donc tout les livres similaires de ce livre favoris et on extrait les 4 plus important livres similaires
		(ceux qui ont le plus grand nombre de mots clefs)
		
		$similar_books = Tableau contenant tout les livres similaires
		$suggestion_books = Tableau final contenant les 5 livres à suggerer
		*/
		for($i=0;$i<count($similar_books['id_book_user']);$i++){
			if($similar_books['id_book_user'][$i]==$mean_max_book['id_book_user']){
				$suggestions_books['id_book_user']=$similar_books['id_book_user'][$i];
				$suggestions_books['id_similar_book'][0]=$similar_books['similar_books'][$i]['id_similar_book'][0];
				$suggestions_books['category'][0]=$similar_books['similar_books'][$i]['category_similar_book'][0];
				
				for($j=1;$j<count($similar_books['similar_books'][$i]['id_similar_book']);$j++){
					if($similar_books['similar_books'][$i]['cpt_similarity'][$j-1]==$similar_books['similar_books'][$i]['cpt_similarity'][$j]){
						$suggestions_books['id_similar_book'][$j]=$similar_books['similar_books'][$i]['id_similar_book'][$j];
						$suggestions_books['category'][$j]=$similar_books['similar_books'][$i]['category_similar_book'][$j];
					}
					else{
						break;
					}
				}
				
				if(count($suggestions_books['id_similar_book'])<5){
					while(($j<5) AND ($j<count($similar_books['similar_books'][$i]['id_similar_book']))){
						$suggestions_books['id_similar_book'][$j]=$similar_books['similar_books'][$i]['id_similar_book'][$j];
						$suggestions_books['category'][$j]=$similar_books['similar_books'][$i]['category_similar_book'][$j];
						$j++;
					}
				}
				else if(count($suggestions_books['id_similar_book'])>5){
					$suggestions_books_tmp=array('id_similar_book'=>array(),'category'=>array(),'id_book_user'=>'');
					$rand_keys = array_rand($suggestions_books['id_similar_book'],5);
					$j=0;
					$suggestions_books_tmp['id_book_user']=similar_books['id_book_user'][$i];
					while($j<5){
						$suggestions_books_tmp['id_similar_book'][$j]=$suggestions_books['id_similar_book'][$rand_keys[$j]];
						$suggestions_books_tmp['category'][$j]=$suggestions_books['category'][$rand_keys[$j]];
						$j++;
					}
					return $suggestions_books_tmp;
				}
			}
		}
		return $suggestions_books;
	}	
?>