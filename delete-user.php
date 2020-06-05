<?php session_start(); ?>
<?php require_once('inc/connection.php');  ?>
<?php require_once('inc/functions.php');  ?>

 <!-- user serama filde input karalada kiyala cheak krnn one?? -->
 <!-- ============================================================================ -->
<?php 
	//cheking if a user logged in...nattan ..index.php file ekata redirect krnn oni
	if(!isset($_SESSION['user_id'])){
	   header('Location:index.php');
	
	}


	

	 if(isset($_GET['user_id'])){
	 	//getting the user information
	 	$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);

	 	
	 	//mage account eken log wela ..mage account ekm delete krnn be
	 	if($user_id == $_SESSION['user_id'] ){
	 		header('Location:users.php?err=cannot_delete_current_user');
	 	}else{
	 		//current userge newenn delete krann
	 		$quary = "UPDATE user SET is_deleted = 1 WHERE id = {$user_id} LIMIT 1";

	 		$result = mysqli_query($connection, $quary);

	 		if($result){
	 			//user deleted...quary successful......
	 			header('Location:users.php?msg=user_deleted');
	 		}else{
	 			//user NOT deleted...quary UNsuccessful......
	 			header('Location:users.php?err=delete_failed');
	    	}
	 
	 }


  } else {
 	     header('Location:users.php');
  }

?>