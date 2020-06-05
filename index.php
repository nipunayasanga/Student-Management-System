<?php session_start(); ?>

<!-- ============================================================================ -->
<!-- start the connection and link the connection file & function file -->
<!-- ============================================================================ -->
<?php require_once('inc/connection.php');  ?>
<?php require_once('inc/functions.php');  ?>
<?php 

	//===============================================================================
	//check karann onii user submit kralada kiyala??
    //===============================================================================
	if(isset($_POST['submit'])){

		$errors = array();
	
		//===============================================================================	
		//username & password enter kralada kiyla check krann oni??
		//===============================================================================
		if(!isset($_POST['email'])|| strlen(trim($_POST['email'])) < 1 ){
			$errors[] = 'Username is Missing / Invalid'; //meka da gnne ape informaton walta.
		}

		if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1 ){
			$errors[] = 'Password is Missing / Invalid'; //meka da gnne ape informaton walta.
		}

	
		//===============================================================================
		//form eke errors tiyeda kiyala check krnn oni...>>>>>>>>>>>>>>>>
		//===============================================================================
		if(empty($errors)){
      //enter krapu username&pswd Variable ekaka save krnn(SQL injection avoide krnn oni)
		//===============================================================================
			$email    = mysqli_real_escape_string($connection, $_POST['email']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

	//===============================================================================
	//enter karapu username&pswrd database eke ewath ekk match wenwd kiyla blnn create Query
	//===============================================================================	
		$query = "SELECT * FROM user 
						WHERE email = '{$email}' 
						AND password = '{$hashed_password}' 
						LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		//===============================================================================
		// query succesfful da kiyal function eken check kranwa
		//===============================================================================
		verify_query($result_set);
			
			if(mysqli_num_rows($result_set) == 1 ){
				
				//=============================================
				//valide user found
				//============================================= 
				$user = mysqli_fetch_assoc($result_set);
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['first_name'] = $user['first_name'];

				//=============================================
				//updating last login
				//=============================================
				$query = "UPDATE user SET last_login = NOW()";
				$query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

				$result_set = mysqli_query($connection, $query);

				//============================================================================
				// query succesfful da kiyal function eken check kranwa
				//==============================================================================
				verify_query($result_set);


				//============================================================================
				//valide user kenek nn User.php page ekata redirect krnn oni
				//============================================================================
				header('Location: users.php');
				

				
			}else{
				//=========================================
				//user name and pass invalide
				//=========================================
				$errors[] = 'Invalide Username / Password';

			}
		
	
		
	}

		
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/main.css">
	<title>Log In - User Management System</title>
</head>
<body>

	<div class="login">

		<form action="index.php" method="post">
			
			<fieldset>
				
				<legend><h1>Log In</h1></legend>

				<!-- ========================================= -->
					
				<!-- ========================================= -->
				<?php 
					if (isset($errors) && !empty($errors)) {
						echo '<p class="error">Invalid Username / Password</p>';
					}
				?>

				 <?php 
				 	if(isset($_GET['logout'])){
				 		echo '<p class="infor">Succesffuly Log Out</p>';
				 	}

				  ?>

				<p>
					<label for="">Username:</label>
					<input type="text" name="email" id="" placeholder="Email Address">

				</p>

				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="Password">
				</p>

				<p>
					<button type="submit" name="submit">Log In</button>
				</p>
				

			</fieldset>

		</form>
		

	</div> <!-- class="login" -->

	
</body>
</html>
<!-- ========================================= -->
<!-- close the connection -->
<!-- ========================================= -->
<?php mysqli_close($connection); ?> 