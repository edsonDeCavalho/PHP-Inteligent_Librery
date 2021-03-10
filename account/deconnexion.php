<?php
    session_start();
	
	if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email'])))){
		session_unset();
		session_write_close();
		session_destroy();
	}
	
	header('location:../index');
?>