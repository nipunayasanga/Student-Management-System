<?php session_start(); ?>
<?php require_once('inc/connection.php');  ?>
<?php require_once('inc/functions.php');  ?>

 <!-- user serama filde input karalada kiyala cheak krnn one?? -->
 <!-- ============================================================================ -->

   <!-- user information gann code eka-->


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



  //AFTER THE PRESS SUBMIT BUTTON...>>>>>>>>>>>>

	 if(isset($_POST['submit'])){
	 $user_id = $_POST['user_id'];
	 $password = $_POST['password'];
	 
	 	//==================================================================
		// //cheking required fileds are entered =========>>>use this foreach for above if
		//===============================================================================
		$req_fields = array( 'user_id', 'password');

		//$errors kiyana arry ekta function eken retune karana arry ek da gann one...
        //arry dekama ekata da ganna function eka.. 
        //array_merge($errors, check_req_fields($req_fields))
		
		$errors = array_merge($errors, check_req_fields($req_fields)); 

	

			//cheking max length
			//===============================================

		$max_len_fields = array('password' =>40);

			//hadagatta $max_len_fields function eka call kranwa
			//===============================================
			$errors = array_merge($errors, check_max_len($max_len_fields)); 

		

	      //errors kiyana array eka empty da kiyala check krnwa
	        if (empty($errors)){
	        	//no errors found ...adding new recode
	        	//password serma sanitize  kara gann onii
	        	//===============================================

	        	$password = mysqli_real_escape_string($connection, $_POST['password']);
	        	$hashed_password = sha1($password);
	        		

	        	//qurey eken data insert kranawa database ekata
	        $query = "UPDATE user SET ";
	        $query .= "password = '{$hashed_password}' ";
	        $query .= "WHERE id = {$user_id} LIMIT 1";
	       
	        
				
				$result = mysqli_query($connection, $query);

				// result eka successfull da kiyala check kara gann one
				if($result){
					header('Location: users.php?user_modified=true');

				}else {
					$errors[] = 'Failed to update the password.';
				}


	        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/main.css">
	<title> Change Password  </title>
</head>
<body>
	<header>
	<div class="appname">User Management Sysytem</div>
	<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>  <a href="logout.php">Log Out</a> </div>	
	</header>
	
	<main>
		<h1>Change Password <span><a href="users.php"> < Back to User List </a></span></h1>

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









		<form action="change-password.php" method="post" class="userform">

		<!-- password eka edit krnn input ekk da gnnwa...meka penne apita vitarai -->
				<input type="hidden" name="user_id" value="<?php echo $user_id;  ?>">

			<p>
				<label for="">First Name:</label>      <!-- disable krama edit krann be -->
				<input type="text" name="first_name"<?php echo 'value="' .$first_name. '"'; ?> disabled> <!--maxlength="50" -->
			</p>
					<!-- =============================================== -->
					<!-- weradi fields ek vitarak ain karala anith ewwa tiya gann code ek-->
					<!-- <?php echo 'value="' .$first_name. '"'; ?> -->
					<!-- =============================================== -->





			<p>
				<label for="">Last Name:</label>
				<input type="text" name="last_name"<?php echo 'value="' .$last_name. '"'; ?> disabled>               
			</p>

			<p>
				<label for="">Email Address:</label>
				<input type="email" name="email"<?php echo 'value="' .$email . '"'; ?> disabled>                  
			</p>


			<p>
				<label for="">New Password:</label>
				<input type="password" name="password" id="password">
				                  
			</p> 


			<p>
				<label for="">Show Password:</label>
				<input type="checkbox" name="showpassword" id="showpassword"  style="width: 15px;height: 15px; float: left; " >
				                  
			</p> 

			
			<p>
				<label for="">&nbsp;</label>
				<button class="button" type="submit" name="submit" style="margin-left: 400px;">Update Password</button>
				
			</p>


		</form>

	</main>

	
	<!-- jqurey liby eka use karala show password butten eka hada gtta code eka -->
	<!-- =========================================================================== -->

	<script src="js/jquery.js"></script>
	<script>
		$(document).ready(function(){
			$('#showpassword').click(function(){
				if( $('#showpassword'). is(':checked') ){
					$('#password').attr('type','text');
				}else{
					$('#password').attr('type','password');
				}
			})
		});
	</script>

</body>
</html>