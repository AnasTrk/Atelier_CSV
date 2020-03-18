<?php
        session_start();
		if(isset($_SESSION['Admin']) OR isset($_SESSION['User']))
		{
			header('location:Dash.php');
        }else
        {
            if(!empty($_POST['User']) AND !empty($_POST['Pass']))
            {
                if(file_exists('users.csv'))
                {
                    function GetTotalRows()
                    {
                        $Users=fopen('users.csv','a+');
                        $counter=0;
                        while(!feof($Users))
                        {
                            $User=fgetcsv($Users,0,";");
                            $counter++;
                        }
                        fclose($Users);
                        return $counter;
                    }
                    $Username=$_POST['User'];
                    $Password=$_POST['Pass'];
                    $Check=false;
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<GetTotalRows();$i++)
                    {
                        $User=fgetcsv($Users,0,";");
                        if($User[0]==$Username)
                        {
                            $Check=true;
                        }
                    }
                    if($Check)
                    {
                        header('location:Signup.php?UsernameExist');
                    }else
                    {
                        $User = array($Username,$Password,0,0,date('d-m-yy'));
                        fputcsv($Users,$User,';');
                        fclose($Users);
                        // $_SESSION['User']=$_POST['User'];
                        header('location:index.php?NewUser');
                    }
                }
            }
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
	<title>Sign Up|Emsi</title>
</head>
<body>
	<form action="Signup.php" method="POST">
		<h1> Sign up</h1>
		<input name="User" type="text" placeholder="Username">
		<input name="Pass" type="text" placeholder="Password">
		<input type="submit" value="Sign Up">
		<a href="index.php">Go log in</a>
	</form>
</body>
</html>