<?php
	// session_start();
	// if(isset($_SESSION['User'])){
	// 	header('location:Dash.php?AuthTry');
	// }
	// if(isset($_SESSION['Admin'])){
	// 	header('location:Dash.php?AuthTry');
	// }
	// if(isset($_GET['error']))
    // {
    //     header('location:Dash.php?error');
    // }
		session_start();
		if(isset($_SESSION['Admin']) OR isset($_SESSION['User']))
		{
			header('location:Dash.php');
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="error.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<title>Login|Emsi</title>
</head>
<body>
	<form action="Dash.php" method="POST">
		<h1> LOG IN</h1>
		<input name="User" type="text" placeholder="Username">
		<input name="Pass" type="text" placeholder="Password">
		<input type="submit" value="S'authentifier">
		<a href="Signup.php"> Sign Up </a>
	</form>
	<?php if(isset($_GET['InvalidAccount']))
	{
		echo "<div class='error'>
		<div class='error_container'>
		<div class='times'>&times;</div>
		<img src='invalidUser.png' >
		<p>This User still invalid(Needs to Validate By an Admin)</p>
		</div>
	</div>";
	
	}

if(isset($_GET['CheckPass']))
	{
		echo "<div class='error'>
		<div class='error_container'>
		<div class='times'>&times;</div>
		<img src='wrong.png' >
		<p>Check Your Password Or Username</p>
		</div>
	</div>";
	
	}
	if(isset($_GET['UserDoesNotExist']))
	{
		echo "<div class='error'>
		<div class='error_container'>
		<div class='times'>&times;</div>
		<img src='uvide.png' >
		<p>This User Doesn't Exist</p>
		</div>
	</div>";
	
	}
	?>
	
</body>
<script src='main.js'></script>
</html>
