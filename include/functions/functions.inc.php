<!-- Convertit une date numérique en date texte 
Exemple : 12/31/2021 -> 31 December 2021
-->
<?php
	function getDateFormat($date){
		$months=array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July",
			"08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
		$date=explode('-',$date);
		$date[1]=$months[$date[1]];
		return $date[2].", ".$date[1]." ".$date[0];
	}
?>

<!-- Affiche les livres similaires à un livre donné (page book.php)
Si Aucun livre similaire existe on affiche les meilleurs livres de la catégorie de ce livre
-->
<?php
	function printSimilarBooks($similar_books,$category,$popular_books){
		/*Si aucun livre similaire existe on affiche les meilleurs livres de la catégorie*/
		if(count($similar_books['id'])<1){
			echo "<div class='suggestion-category-container'>
					<h3>Best books for the ".$category." genre</h3>
					<table style='margin:2.5em auto'>
						<tr>
							<td><a href='./book.php?id=".$popular_books['id'][0]."'><img src='".$popular_books['cover'][0]."' width='160' height='210' alt='cover-book'></a></td>";
						for ($i=1;$i<count($popular_books['id']);$i++){
							echo "<td style='padding-left:3em'><a href='./book.php?id=".$popular_books['id'][$i]."'><img src=".$popular_books['cover'][$i]." width='160' height='210' alt='cover-book'></a></td>";
						}
						echo "</tr>
							  <tr>
								<td style='padding-top:1em'>
									<div style='word-wrap: break-word;width:13em'>
										<p>".$popular_books['title'][0]."</p>
									</div>
								</td>";
								for ($i=1;$i<count($popular_books['id']);$i++){
									echo "<td style='padding-top:1em;padding-left:3em'>
											<div style='word-wrap: break-word;width:13em'>
												<p>".$popular_books['title'][$i]."</p>
											</div>
										</td>";
								}
						echo "</tr>
					</table>
				</div>";
		}
		/*Si des livres similaires existent alors on les affichent*/
		else{
			$total_books=count($similar_books['id']);
			$k=0;
			$kk=0;
			$books_by_page=10;
			$htmlString="<div class='suggestion-category-container'>
							<h3>Similar books in the ".$category." genre</h3>
								<table style='margin-top:1em;margin-right:23px'>
									<tr>";
		
			for($i=0;$i<$books_by_page+1;$i++){	
				if($k<$total_books){		
					if(($i%5==0) && ($i>0)){
						$htmlString.="</tr><tr>";
						$j=$i-5;
						for($j;$j<$i;$j++){
							$htmlString.="<td style='padding-top:1em;padding-left:3em'>
												<div style='word-wrap: break-word;width:13em'>
													<p>".$similar_books['title'][$kk]."</p>
												</div>
											</td>";
							$kk++;
						}
						$htmlString.="</tr><tr>";	
					}
					if($i<$books_by_page){
						$htmlString.="<td style='padding-left:3em;padding-top:1em'><a href='./book.php?id=".$similar_books['id'][$k]."'><img src=".$similar_books['cover'][$k]." width='160' height='210' alt='cover-book'></a></td>";
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
					$htmlString.="<td style='padding-top:1em;padding-left:3em'>
									<div style='word-wrap: break-word;width:13em'>
										<p>".$similar_books['title'][$j]."</p>
									</div>
								</td>";
				}
				$htmlString.="</tr>";
				$kk=0;
			}
			else{
				$htmlString.="</tr>";
			}
			$htmlString.="</table></div>";
			
			if($k>5){
				$htmlString.="<p style='height:55px'></p>";
			}	
	
			echo $htmlString;
		}
	}
?>

<!--On affiche le bloc contenant les filtrages par notes-->
<?php
	function printRatingStars(){
		$bool=0;
		if(isset($_POST['rating'])){
			$rating=$_POST['rating'];
		}
		else if(isset($_GET['rating'])){
			$rating=$_GET['rating'];
			$multiple_ratings = strpos($rating, ":");
			
			if($multiple_ratings!=false){
				$rating=explode(":",$rating);
			}
			$rating=array($rating);
		}
		else{
			$rating[0]="";
		}
		
		$htmlString="<h2 style='font-size:22px;margin-left:0.7em'>EVALUATIONS</h2><table><tr>";
		foreach($rating as $value){
			if($value==5){
				$bool=1;
			}
		}
		if($bool==1){
			$htmlString.="<td style='padding-left:1.5em'><input type='checkbox' onclick='ratingControl(0)' name='rating[]' checked value='5'/>";
			$bool=0;
		}
		else{
			$htmlString.="<td style='padding-left:1.5em'><input type='checkbox' onclick='ratingControl(0)' name='rating[]' value='5'/>";
		}
		
		$htmlString.="<td style='padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
					</tr>
					<tr>";
		
		foreach($rating as $value){
			if($value==4){
				$bool=1;
			}
		}
		
		if($bool==1){
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(1)' name='rating[]' checked value='4'/>";
			$bool=0;
		}
		else{
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(1)' name='rating[]' value='4'/>";
		}
	
		$htmlString.="<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
					</tr>
					<tr>";
						
		foreach($rating as $value){
			if($value==3){
				$bool=1;
			}
		}
		
		if($bool==1){
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(2)' name='rating[]' checked value='3'/>";
			$bool=0;
		}
		else{
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(2)' name='rating[]' value='3'/>";
		}	
		
		$htmlString.="<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
					</tr>
					<tr>";
		
		foreach($rating as $value){
			if($value==2){
				$bool=1;
			}
		}
		
		if($bool==1){
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(3)' name='rating[]' checked value='2'/>";
			$bool=0;
		}
		else{
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(3)' name='rating[]' value='2'/>";
		}	
		
		$htmlString.="<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
					</tr>
					<tr>";	

		foreach($rating as $value){
			if($value==1){
				$bool=1;
			}
		}					
		
		if($rating==1){
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(4)' name='rating[]' checked value='1'/>";
			$bool=0;
		}
		else{
			$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' onclick='ratingControl(4)' name='rating[]' value='1'/>";
		}				
						
		$htmlString.="<td style='padding-top:1em;padding-left:1em'><label style='color:#fd4;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
						<td style='padding-top:1em;padding-left:1em'><label style='color:grey;font-size:21px' class='fas fa-star'></label></td>
					</tr>
				</table>";
		
		echo $htmlString;				
	}
?>

<!--On affiche le bloc contenant le filtrage par date de parution-->
<?php
	function printDate(){
		$dates=array('all','t','2016-2020','2011-2015','2006-2010','2001-2005','1980-2000','b');
		
		if(isset($_POST['release'])){
			$release_date=$_POST['release'];
		}
		else if(isset($_POST['release'])){
			$release_date=$_POST['release'];
		}
		else{
			$release_date="all";
		}
		$htmlString="<h2 style='font-size:22px;margin-left:0.7em'>RELEASE DATE</h2>
					<select style='margin-left:1em;width:14em' class='form-control' name='release'>";
		
		foreach($dates as $value){
			if($value=='b'){
				$value_tmp="Before 1980";
			}
			else if($value=='all'){
				$value_tmp="All";
			}
			else if($value=='t'){
				$value_tmp="2021-today";
			}
			else{
				$value_tmp=$value;
			}
				
			if($release_date==$value){
				$htmlString.="<option value=".$value." selected>".$value_tmp."</option>";
			}
			else{
				$htmlString.="<option value=".$value.">".$value_tmp."</option>";
			}
		}
		$htmlString.="</select><p style='height:3em'></p>";
		
		echo $htmlString;
	}
?>

<!--On affiche le bloc contenant le filtrage par catégories-->
<?php
	function printGenres(){
		$categories_research=array();
		$bool=0;
		
		if(isset($_GET['category'])){
			$categories_research[0]=$_GET['category'];
		}
		else if(isset($_POST['category'])){
			$categories_research=$_POST['category'];
		}
		else{
			$categories_research[0]="";
		}
		
		$categories=array('Art','Biography','Business','Computers','Cooking','Fiction',
			'Health','History','Humor','Mathematics','Medical','Music','Nature','Philosophy',
			'Poetry','Psychology','Religion','Science');
		
		$htmlString="<h2 style='font-size:22px;margin-left:0.7em'>GENRES</h2><table>";
	
		foreach($categories as $value){
			$htmlString.="<tr>";
		
			if($value=='Art'){
				foreach($categories_research as $cat){
					if($value==$cat){
						$bool=1;
					}
				}
				if($bool==1){
					$htmlString.="<td style='padding-left:1.5em'><input type='checkbox' name='category[]' value='Art' checked/>";
					$bool=0;
				}
				else{
					$htmlString.="<td style='padding-left:1.5em'><input type='checkbox' name='category[]' value='Art'/>";
				}
				$htmlString.="<td style='padding-left:1em'><label>Art</label></td>";
			}
			else{
				foreach($categories_research as $cat){
					if($value==$cat){
						$bool=1;
					}
				}	
				if($bool==1){
					$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' name='category[]' value='$value' checked/></td>";
					$bool=0;
				}
				else{
					$htmlString.="<td style='padding-top:1em;padding-left:1.5em'><input type='checkbox' name='category[]' value='$value'/></td>";
				}
				$htmlString.="<td style='padding-top:1em;padding-left:1em'><label>".$value."</label></td>";
			}
			$htmlString.="</tr>";
		}
		$htmlString.="</table>";
		
		echo $htmlString;
	}
?>

<!--Recuperer le nombre total de pages de la recherche -->
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

<!-- Affiche les livres de la recherche -6>
<?php
	function printBooksResearch($array_books,$begin){
		$total_books=count($array_books['id']);		
		if($total_books<1){
			echo "<h3 style='padding-left:10px'>No books found</h3>";
		}
		$k=$begin;
		$kk=$begin;
		$books_by_page=16;
		
		$htmlString="<table style='margin:0 auto;margin-top:2em;margin-left:0.7em'>";
		$htmlString.="<tr>";
		
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

<!-- Affiche la pagination sur la page books.php -->
<?php
	function pagination($category,$current_page,$total_pages,$release_date,$rating,$search){
		if(is_array($category)){
			$string_category=$category[0];
			for($i=1;$i<count($category);$i++){
				$string_category.=":".$category[$i];
			}
			$category=$string_category;
			
		}
		if(is_array($rating)){
			$string_rating=$rating[0];
			for($i=1;$i<count($rating);$i++){
				$string_rating.=":".$rating[$i];
			}
			$rating=$string_rating;
			
		}
			
		$html_string="<nav style='text-align:center'><ul style ='justify-content:center' class='pagination'>";
		if ($current_page>1){
			$previous_page=$current_page-1;
			$html_string.="<li class='page-item'>";
			
			if($category=="none" AND $rating=="none" AND $release_date=="none"){
				$html_string.="<a class='page-link' href='./books.php?search=$search&amp;page=$previous_page' aria-label='Previous'>";
			}
			else if($category=="none" AND $rating=="none"){
				$html_string.="<a class='page-link' href='./books.php?release=$release_date&amp;page=$previous_page' aria-label='Previous'>";
			}
			else if($category=="none"){
				$html_string.="<a class='page-link' href='./books.php?release=$release_date&amp;rating=$rating&amp;page=$previous_page' aria-label='Previous'>";
			}
			else if($rating=="none"){
				$html_string.="<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;page=$previous_page' aria-label='Previous'>";
			}
			else{
				$html_string.="<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;rating=$rating&amp;page=$previous_page' aria-label='Previous'>";
			}
			$html_string.="<span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></a></li>";						
		}
		
		for($i=1;$i<=$total_pages;$i++){
			if($current_page==$i){
				$html_string.="<li class='page-item'><a class='page-link' href='#'>".$i."</a></li>";
			}
			else{
				if($category=="none" AND $rating=="none" AND $release_date=="none"){
					$html_string.="<li class='page-item'><a class='page-link' href='./books.php?search=$search&amp;page=$i'>".$i."</a></li>";
				}
				else if($category=="none" AND $rating=="none"){
					$html_string.="<li class='page-item'><a class='page-link' href='./books.php?release=$release_date&amp;page=$i'>".$i."</a></li>";
				}
				else if($category=="none"){
					$html_string.="<li class='page-item'><a class='page-link' href='./books.php?release=$release_date&amp;rating=$rating&amp;page=$i'>".$i."</a></li>";
				}
				else if($rating=="none"){
					$html_string.="<li class='page-item'><a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;page=$i'>".$i."</a></li>";
				}
				else{
					$html_string.="<li class='page-item'><a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;rating=$rating&amp;page=$i'>".$i."</a></li>";
				}	
			}
		}
		if ($current_page<$total_pages){
			$next_page=$current_page+1;	
			$html_string.="<li class='page-item'>";
			
			if($category=="none" AND $rating=="none" AND $release_date=="none"){
				$html_string.="<a class='page-link' href='./books.php?search=$search&amp;page=$next_page' aria-label='Next'>";
			}
			else if($category=="none" AND $rating=="none"){
				$html_string.="<a class='page-link' href='./books.php?release=$release_date&amp;page=$next_page' aria-label='Next'>";
			}
			else if($category=="none"){
				$html_string.="<a class='page-link' href='./books.php?release=$release_date&amp;rating=$rating&amp;page=$next_page' aria-label='Next'>";
			}
			else if($rating=="none"){
				$html_string.="<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;page=$next_page' aria-label='Next'>";
			}
			else{
				$html_string.="<a class='page-link' href='./books.php?category=$category&amp;release=$release_date&amp;rating=$rating&amp;page=$next_page' aria-label='Next'>";
			}
			$html_string.="<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a></li>";
		}

		$html_string.="</ul></nav>";
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
	function printBecauseYouLikeBlock($myPDO,$array_because_you_like){
		$title_book=getTitleBookById($myPDO,$array_because_you_like['id_book_user']);
		$i=count($array_because_you_like['id_similar_book']);
		$htmlString="<div class='because-of'>";
		$htmlString.="<table style='margin-top:1em'>
						<tr>
							<td style='padding-bottom:10px'colspan='4'><h2 style='padding-left:50px;font-size:25px;text-decoration:underline'>Because you saved <i><b>".$title_book."</b></i> book to your favorites</h2></td>
						</tr>
						<tr>";
		for($j=0;$j<$i;$j++){
			$id_book_similar=$array_because_you_like['id_similar_book'][$j];
			$cover=getCoverBookById($myPDO,$id_book_similar);
			$htmlString.="<td style='padding-left:55px'><a href='./research/book.php?id=".$id_book_similar."'><img  src='".$cover."' width='170' height='220' alt='cover-book'></a></td>";
		}			
		$htmlString.="</tr><tr>";
		for($j=0;$j<$i;$j++){
			$id_book_similar=$array_because_you_like['id_similar_book'][$j];
			$title_book=getTitleBookById($myPDO,$id_book_similar);
			$htmlString.="<td style='padding-top:1em;padding-left:55px;padding-bottom:20px'>
							<div style='word-wrap: break-word;width:13em'>
								<p>".$title_book."</p>
							</div>
						</td>";
			}
			$htmlString.="</tr>
						</table>
					</div>";
		echo $htmlString;
	}
?>		

<?php
	function printGeneraleSuggestions($books_suggestions){
		$total_books=count($books_suggestions['id_book']);
		$k=0;
		$kk=0;
		$htmlString="<div class='books-love'>
						<table style='margin-top:1em'>
							<tr>
								<td style='padding-bottom:10px'colspan='4'><h2 style='padding-left:50px;font-size:25px;text-decoration:underline'>Books that you may like</h2></td>
							</tr>
							<tr>";
		for($i=0;$i<$total_books+1;$i++){
			if($k<$total_books){	
				if(($i%5==0) && ($i>0)){
					$htmlString.="</tr><tr>";
					$j=$i-5;
					for($j;$j<$i;$j++){
						$htmlString.="<td style='padding-top:1em;padding-left:55px;padding-bottom:20px'><div style='word-wrap: break-word;width:13em'>
										<p>".$books_suggestions['title'][$kk]."</p>
									</div></td>";
						$kk++;
					}
					$htmlString.="</tr><tr>";	
				}
				if($i<$total_books){
					$htmlString.="<td style='padding-left:55px'><a href='./research/book.php?id=".$books_suggestions['id_book'][$k]."'><img src=".$books_suggestions['cover'][$k]." width='160' height='210' alt='cover-book'></a></td>";
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
				$htmlString.="<td style='padding-top:1em;padding-left:55px'>
								<div style='word-wrap: break-word;width:13em'>
									<p>".$books_suggestions['title'][$j]."</p>
								</div>
							</td>";
				}
				$htmlString.="</tr>";
				$kk=0;
		}
		else{
			$htmlString.="</tr>";
		}
		$htmlString.="</table></div>";
	
		echo $htmlString;
	}
?>