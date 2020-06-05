<?php 

	session_start();

	$_SESSON = array();

	if (isset($_COOKIE[session_name()])) {

		setcookie(session_name(), '', time()-86400, '/');
		# code...
	}

	session_destroy();

	header('Location:index.php?logout=yes');


 ?>