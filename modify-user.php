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


	//errors serma me array ekata ynne
	// $errors =array(); 

	//weradi fields ek vitarak ain karala anith ewwa tiya gann	
	//==================================================================
     $errors = array(); 
     $user_id = '';
	 $first_name = '';
	 $last_name = '';
	 $email = '';
	 $password = '';

	 if(isset($_GET['user_id'])){
	 	//getting the user information
	 	$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);

	 	//modify krnn oni user wa query ekkin select gnnw data base eken...
	 	$query = "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";

	 	$result_set = mysqli_query($connection, $query);

	 	if($result_set){
	 		if(mysqli_num_rows($result_set) == 1){
	 			//user found eka da gannwa $result kiyana vareible ekata
	 			$result = mysqli_fetch_assoc($result_set);

	 					$first_name = $result['first_name'];
	 					$last_name = $result['last_name'];
						$email = $result['email'];
	                     		

	 		}else{
	 			//user not found
	 			header('Location: users.php?err=user_not_found');
	 	}
	 		
	 } else {
	 		//redirect to main page.....qurey unsuccessful......
	 		header('Location: users.php?err=qurey_failed_fucked');
    }

}





	 if(isset($_POST['submit'])){
	 $user_id = $_POST['user_id'];
	 $first_name = $_POST['first_name'];
	 $last_name = $_POST['last_name'];
	 $email = $_POST['email'];
	 
	 //==================================================================

	 

		// //cheking required fileds are entered =========>>>use this foreach for above if
		//===============================================================================
		$req_fields = array( 'user_id', 'first_name','last_name','email');

		//$errors kiyana arry ekta function eken retune karana arry ek da gann one...
        //arry dekama ekata da ganna function eka.. 
        //array_merge($errors, check_req_fields($req_fields))
		
		$errors = array_merge($errors, check_req_fields($req_fields)); 

	

//function ekak hadagta nisa meka ain kre
//==============================================
	// 	foreach ($req_fields as $field) {
	// 		if (empty(trim($_POST[ $field ]))) {
	// 		$errors[] = $field . 'is required';
	// 	}
	// }
			

		//===================>>>>>>>>>> IF <<<<<<<<<<<<<<<< =========================
		// //cheking req_fileds are entered
		//===========================================================================
		// if (empty(trim($_POST['first_name']))) {
		// 	$errors[] = 'First Name is required';
		// }

		// if (empty(trim($_POST['last_name']))) {
		// 	$errors[] = 'Last Name is required';
		// }

		// if (empty(trim($_POST['email']))) {
		// 	$errors[] = 'Email Name is required';
		// }

		// if (empty(trim($_POST['password']))) {
		// 	$errors[] = 'password is required';
		// }
	

			//cheking max length
			//===============================================

		$max_len_fields = array('first_name' =>50,'last_name'=>100,'email'=>100,);

			//hadagatta $max_len_fields function eka call kranwa
			//===============================================
			$errors = array_merge($errors, check_max_len($max_len_fields)); 

			//kalin use karapu function eka
			//===============================================
			// foreach ($max_len_fields as $field => $max_len ) {
			// 	if (strlen(trim($_POST[$field])) > $max_len ){
			// 		$errors[] = $field . ' must be less than ' . $max_len . 'characters';
			//     } 

	  //       }
			
    
	        //cheking  email is valide using [function.php]
	        //===============================================
	        if (!is_email($_POST['email'])) {
	        	$errors[] = 'Email address is invalide';
	        	
	        }


	        //cheking if email address alredy exist
	        //email eka sanitize krnn one..SQL injection avoide krnn oni
	        $email = mysqli_real_escape_string($connection, $_POST['email']);

	        //prepeade a query 					 email eki aluth pass ekai samana wenn be
	        $query = "SELECT * FROM user WHERE email = '{$email}' AND id !={$user_id} LIMIT 1";


	        //result_set kiyana variable ekata da gannawa eka
	        $result_set = mysqli_query($connection, $query);


	        //qurey eka successfull nan...eke recode tiyeda kiyala check kra gannwa
	        if ($result_set){
	        	if(mysqli_num_rows($result_set) == 1){ //recode ekk tiyenwa kiynne eka error ekk...e kiyanne api denatama enter krana email eka database eke tiyenwa

	        		$errors[] = 'email address is alredy exists';
	        	}
	        }


	        //errors kiyana array eka empty da kiyala check krnwa
	        if (empty($errors)){
	        	//no errors found ...adding new recode
	        	//fildes serma sanitize  kara gann onii
	        	//===============================================

	        	$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
	        	$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
	        	
	        	//email address is alredy sanitize....

	        	$hashed_password = sha1($password);

	        	//qurey eken data insert kranawa database ekata
	        $query = "UPDATE user SET ";
	        $query .= "first_name = '{$first_name}', ";
	        $query .= "last_name = '{$last_name}',";
	        $query .= "email = ' {$email}'";
	        $query .= "WHERE id = {$user_id} LIMIT 1";
	       
	        
				
				$result = mysqli_query($connection, $query);

				// result eka successfull da kiyala check kara gann one
				if($result){
					header('Location: users.php?user_modified=true');

				}else {
					$errors[] = 'Failed to modify the recode.';
				}


	        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/main.css">
	<title>View / Modify User </title>
</head>
<body>
	<header>
	<div class="appname">User Management Sysytem</div>
	<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>  <a href="logout.php">Log Out</a> </div>	
	</header>
	
	<main>
		<h1>View / Modify User <span><a href="users.php"> < Back to User List </a></span></h1>

		<!-- display the errors ...array eka empty nattan disply wenwa -->
<!--//=========================================================================== -->
		<?php 

			if (!empty($errors)) {
				//passe hada gatta function
				display_errors($errors);
			
			}

		//kalin use karapu function eka....
			// 	echo '<div class="errmsg">';
			// 	echo '<b>There were error(s) in your form.</b><br>';
			// 	foreach ($errors as  $error) {
			// 		echo $error. '<br>';
			// 	}
			// }
			// echo '</div>';


		 ?>









		<form action="modify-user.php" method="post" class="userform">

		<!-- password eka edit krnn input ekk da gnnwa...meka penne apita vitarai -->
				<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>">

			<p>
				<label for="">First Name:</label>
				<input type="text" name="first_name"<?php echo 'value="' .$first_name. '"'; ?>> <!--maxlength="50" -->
			</p>
					<!-- =============================================== -->
					<!-- weradi fields ek vitarak ain karala anith ewwa tiya gann code ek-->
					<!-- <?php echo 'value="' .$first_name. '"'; ?> -->
					<!-- =============================================== -->





			<p>
				<label for="">Last Name:</label>
				<input type="text" name="last_name"<?php echo 'value="' .$last_name. '"'; ?>>               
			</p>

			<p>
				<label for="">Email Address:</label>
				<input type="email" name="email"<?php echo 'value="' .$email . '"'; ?>>                  
			</p>


			<p>
				<label for="">New Password:</label>
									<!-- methana user_id eka pass krnwa change pass page ekt -->
				<span>********</span> | <a href="change-password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
				                  
			</p>

			<p>
				<label for="">&nbsp;</label>
				<button class="button" type="submit" name="submit">Save</button>
				
			</p>


		</form>

	</main>

</body>
</html>