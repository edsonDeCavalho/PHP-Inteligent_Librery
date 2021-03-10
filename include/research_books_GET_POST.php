<?php
	if((isset($_GET['page'])) AND ((!empty($_GET['page'])))){
		$current_page=$_GET['page'];
	}
	else{
		$current_page=1;
	}
	
	$begin=($current_page-1)*16; //16 ->Books_by_page
	$total_pages;
	
	if(((isset($_POST['category'])) AND ((!empty($_POST['category'])))) AND ((isset($_POST['release'])) AND ((!empty($_POST['release']))))){
		$category=$_POST['category'];
		$release_date=$_POST['release'];
		
		$number=0;
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number++;
			}
		}
		$research_books=getBooksFromResearch($myPDO,$category,$number,$release_date);
	}
	
	else if(((isset($_GET['category'])) AND ((!empty($_GET['category'])))) AND ((isset($_GET['release'])) AND ((!empty($_GET['release']))))){
		$category=$_GET['category'];
		$release_date=$_POST['release'];
		$number=0;
		$multiple_categories = strpos($category, ":");
		
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number++;
			}
		}
		$research_books=getBooksFromResearch($myPDO,$category,$number,$release_date);
	}
	
	else if((isset($_POST['category'])) AND (!empty($_POST['category']))){
		$category=$_POST['category'];		
		$number=0;
		$release_date="";
		if(is_array($_POST['category'])){
			for($i=0;$i<count($category);$i++){
				$category[$i]=str_replace("+"," ",$category[$i]);
				$number++;
			}
		}
		$research_books=getBooksFromResearch($myPDO,$category,$number);
	}
	
	else if((isset($_GET['category'])) AND (!empty($_GET['category']))){
		$category=$_GET['category'];
		$number=0;
		$release_date="";
		$multiple_categories = strpos($category, ":");
		
		if($multiple_categories!=false){
			$category=explode(":",$category);
			
			for($i=0;$i<count($category);$i++){
				$number++;
			}
		}
		$research_books=getBooksFromResearch($myPDO,$category,$number,"none");
	}
	
	else if((isset($_POST['release'])) AND (!empty($_POST['release']))){
		$release_date=$_POST['release'];	
		$number=0;
		$category="";
		$research_books=getBooksFromResearch($myPDO,"none",$number,$release_date);
	}
	
	else if((isset($_GET['release'])) AND (!empty($_GET['release']))){
		$release_date=$_GET['release'];
		$number=0;
		$category="";
		$research_books=getBooksFromResearch($myPDO,"none",$number,$release_date);
	}
	
	else{
		$research_books=getRandomBooks($myPDO,200);
		$category="";
	}
	$total_pages=getTotalPages($research_books);
?>