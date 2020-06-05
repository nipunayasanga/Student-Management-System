<?php session_start(); ?>
<?php require_once('inc/connection.php');  ?>
<?php require_once('inc/functions.php');  ?>
<?php 
	//cheking if a user logged in
	if(!isset($_SESSION['user_id'])){
	   header('Location:index.php');
	
	}

	

	$user_list = '';

	//getting the list of users
	$query = "SELECT * FROM user WHERE is_deleted=0 OrDER BY first_name ";
	$users = mysqli_query($connection, $query );


//function eka call krala tiyenne....functions.php file eken
 verify_query($users); 

	while ($user = mysqli_fetch_assoc($users)){
		$user_list .= "<tr>";
		
		$user_list .= "<td>{$user['first_name']}</td>";
		$user_list .= "<td>{$user['last_name']}</td>";
		$user_list .= "<td>{$user['last_login']}</td>";
		$user_list .= "<td><a href=\"modify-user.php?user_id={$user['id']}\">Edit</a></td>";
		$user_list .= "<td><a href=\"delete-user.php?user_id={$user['id']}\"onclick=\"return confirm('Are you sure?')\">Delete</a></td>";
		
		$user_list .= "</tr>";




		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/main.css">
	<title>Users</title>
</head>
<body>
	<header>
	<div class="appname">User Management Sysytem</div>
	<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>  <a href="logout.php">Log Out</a> </div>	
	</header>
	
	<main>
		<h1>Users <span><a href="add-user.php">+ Add New</a></span></h1>

		<table class="masterlist">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Last Login</th>
				<th>Edit <a href=""></a></th>
				<th>Delete <a href=""></a></th>
			</tr>

			<?php echo $user_list; ?>


		</table>
	</main>

</body>
</html>