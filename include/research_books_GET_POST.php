<?php
	if((isset($_GET['page'])) AND ((!empty($_GET['page'])))){
		$current_page=$_GET['page'];
	}
	else{
		$current_page=1;
	}
	
	$begin=($current_page-1)*16; //16 ->Books_by_page
	$total_pages;
	$search="";
	
	if((isset($_POST['search'])) AND ((!empty($_POST['search'])))){
		$search=$_POST['search'];
		$research_books=getBooksInputSearch($myPDO,$search);
		$category="none";
		$rating="none";
		$release_date="none";
	}
	
	else if((isset($_GET['search'])) AND ((!empty($_GET['search'])))){
		$search=$_GET['search'];
		$research_books=getBooksInputSearch($myPDO,$search);
		$category="none";
		$rating="none";
		$release_date="none";
	}
	
	
	else if(((isset($_POST['category'])) AND ((!empty($_POST['category'])))) 
		AND ((isset($_POST['release'])) AND ((!empty($_POST['release']))))
		AND ((isset($_POST['rating'])) AND ((!empty($_POST['rating']))))){
		$category=$_POST['category'];
		$rating=$_POST['rating'];
		$release_date=$_POST['release'];
		$number_categories=1;
		$number_ratings=1;
		
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number_categories++;
			}
		}
		if(is_array($_POST['rating'])){
			for($i=0;$i<count($rating);$i++){
				$rating[$i]=str_replace("+"," ",$rating[$i]);
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,$number_ratings);
	}
	
	else if(((isset($_GET['category'])) AND ((!empty($_GET['category'])))) 
		AND ((isset($_GET['release'])) AND ((!empty($_GET['release']))))
		AND ((isset($_GET['rating'])) AND ((!empty($_GET['rating']))))){
		$category=$_GET['category'];
		$rating=$_GET['rating'];
		$release_date=$_GET['release'];
		$number_categories=1;
		$number_ratings=1;
		
		$multiple_categories = strpos($category, ":");
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number_categories++;
			}
		}
		
		$multiple_ratings = strpos($rating, ":");
		if($multiple_ratings!=false){
			$rating=explode(":",$rating);
			
			for($i=0;$i<count($rating);$i++){
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,$number_ratings);
	}
	
	else if(((isset($_POST['category'])) AND ((!empty($_POST['category'])))) AND ((isset($_POST['rating'])) AND ((!empty($_POST['rating']))))){
		$category=$_POST['category'];
		$rating=$_POST['rating'];
		$release_date="none";
		$number_categories=1;
		$number_ratings=1;
		
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number_categories++;
			}
		}
		if(is_array($_POST['rating'])){
			for($i=0;$i<count($rating);$i++){
				$rating[$i]=str_replace("+"," ",$rating[$i]);
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,$number_ratings);
	}
	
	else if(((isset($_GET['category'])) AND ((!empty($_GET['category'])))) AND ((isset($_GET['rating'])) AND ((!empty($_GET['rating']))))){
		$category=$_GET['category'];
		$rating=$_GET['rating'];
		$release_date="none";
		$number_categories=1;
		$number_ratings=1;
		
		$multiple_categories = strpos($category, ":");
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number_categories++;
			}
		}
		
		$multiple_ratings = strpos($rating, ":");
		if($multiple_ratings!=false){
			$rating=explode(":",$rating);
			
			for($i=0;$i<count($rating);$i++){
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,$number_ratings);
	}
	
	else if(((isset($_POST['category'])) AND ((!empty($_POST['category'])))) AND ((isset($_POST['release'])) AND ((!empty($_POST['release']))))){
		$category=$_POST['category'];
		$rating="none";		
		$release_date=$_POST['release'];	
		$number_categories=1;
		
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number_categories++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,0);
	}
	
	else if(((isset($_GET['category'])) AND ((!empty($_GET['category'])))) AND ((isset($_GET['release'])) AND ((!empty($_GET['release']))))){
		$category=$_GET['category'];
		$rating="none";
		$release_date=$_GET['release'];
		$number_categories=1;
		$multiple_categories = strpos($category, ":");
		
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number_categories++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,0);
	}
	
	else if(((isset($_POST['rating'])) AND ((!empty($_POST['rating'])))) AND ((isset($_POST['release'])) AND ((!empty($_POST['release']))))){
		$category="none";
		$rating=$_POST['rating'];
		$release_date=$_POST['release'];
		$number_ratings=1;
		
		if(is_array($_POST['rating'])){
			for($i=0;$i<count($rating);$i++){
				$rating[$i]=str_replace("+"," ",$rating[$i]);
				$number_ratings++;
			}
		}

		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,$number_ratings);
	}
	
	else if(((isset($_GET['rating'])) AND ((!empty($_GET['rating'])))) AND ((isset($_GET['release'])) AND ((!empty($_GET['release']))))){
		$category="none";
		$rating=$_GET['rating'];
		$release_date=$_GET['release'];
		$number_ratings=1;
		
		$multiple_ratings = strpos($rating, ":");
		if($multiple_ratings!=false){
			$rating=explode(":",$rating);
			
			for($i=0;$i<count($rating);$i++){
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,$number_ratings);
	}
	
	else if((isset($_POST['category'])) AND (!empty($_POST['category']))){
		$category=$_POST['category'];	
		$rating="none";		
		$release_date="none";
		$number_categories=1;
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number_categories++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,0);
	}
	
	else if((isset($_GET['category'])) AND (!empty($_GET['category']))){
		$category=$_GET['category'];
		$rating="none";
		$release_date="none";
		$number_categories=1;
		
		$multiple_categories = strpos($category, ":");
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number_categories++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,$number_categories,$release_date,$rating,0);
	}
	
	else if((isset($_POST['release'])) AND (!empty($_POST['release']))){
		$category="none";
		$rating="none";
		$release_date=$_POST['release'];	
		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,0);
	}
	
	else if((isset($_GET['release'])) AND (!empty($_GET['release']))){
		$category="none";
		$rating="none";
		$release_date=$_GET['release'];
		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,0);
	}
	
	else if((isset($_POST['rating'])) AND (!empty($_POST['rating']))){
		$category="none";
		$rating=$_POST['rating'];	
		$release_date="none";
		$number_ratings=1;
		if(is_array($_POST['rating'])){
			for($i=0;$i<count($rating);$i++){
				$rating[$i]=str_replace("+"," ",$rating[$i]);
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,$number_ratings);
	}
	
	else if((isset($_GET['rating'])) AND (!empty($_GET['rating']))){
		$category="none";
		$release_date="none";
		$release_date=$_GET['rating'];
		$number_ratings=1;
		
		$multiple_ratings = strpos($rating, ":");
		if($multiple_ratings!=false){
			$rating=explode(":",$rating);
			
			for($i=0;$i<count($rating);$i++){
				$number_ratings++;
			}
		}
		$research_books=getBooksFromResearchFilter($myPDO,$category,0,$release_date,$rating,$number_ratings);
	}
	
	else{
		$research_books=getRandomBooks($myPDO,200);
		$category="none";
	}
	$total_pages=getTotalPages($research_books);
?>