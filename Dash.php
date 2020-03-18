<?php
// session_start();
//     if(!isset($_SESSION['User']) OR !isset($_SESSION['Admin']) ){
//         echo "not set";
//         // if(isset($_POST['User']) AND isset($_POST['Pass'])){
//             $User=$_POST['User'];
//             $Pass=$_POST['Pass'];
//             echo "kayn wsst post";
//             if(file_exists('users.csv'))
//             {
//                 $check=false;
//                 $Users=fopen('users.csv','r+');
//                 while(!feof($Users)){
//                     $user=fgetcsv($Users,0,";");
//                     if($User==$user[0] AND $Pass==$user[1] AND $user[2]==1)
//                     {
//                                 $_SESSION['Admin']=$user;
//                               //  print_r($user);
//                                 $check=true;
//                     }
//                     if($User==$user[0] AND $Pass==$user[1] AND $user[2]==0)
//                     {
//                                 $_SESSION['User']=$user;
//                                 $check=true;
//                     }
//                 }
//                 if($check==false)
//                     {
//                                 header('location:index.php?error');
//                                 session_destroy();
//                     }
//                 fclose($Users);
//             }else
//             {
//                 $file=fopen('users.csv',"a");
//                 $array=array("admin","admin",'1','1');
//                 fputcsv($file,$array,";");
//                 fclose($file);
//                 header('location:index.php?Admin');
//             }
//         // }
//         // else
//         // {
//         //     header('location:index.php?AttributeNotFound');
//         // }
//     }
//     else{
        
//             header('loaction:Dash.php?'+$_SESSION['User']);
        
//     }
    /********************************************************************** */
    session_start();
    if(isset($_SESSION['User'])){
      
    }else
    {
        if(isset($_SESSION['Admin']))
        {
            // header('location:Dash.php?'.$_SESSION['Admin']);
        }else
        {
            if(!empty($_POST['User']) AND !empty($_POST['Pass']))
            {
                $Username=$_POST['User'];
                $Password=$_POST['Pass'];
                echo $_POST['User'];
                if(file_exists('users.csv'))
                {   $check=false;
                    $validation=true;
                    $Users=fopen('users.csv','r+');
                    while(!feof($Users))
                    {
                        $User=fgetcsv($Users,0,';');
                        if($User[0]==$Username AND $User[1]==$Password AND $User[2]==1)
                        {
                            $check=true;
                            $_SESSION['Admin']=$Username;
                        }
                        if($User[0]==$Username AND $User[1]==$Password AND $User[2]==0)
                        {
                            if($User[3]==1)
                            {
                                $check=true;
                                $_SESSION['User']=$Username;
                            }else
                            {
                                $validation=false;
                            }
                        }
                    }
                }
                /*******************FOR CHECKING AUTHENTICATION******************* */
                if($check==false)
                {
                    if($validation==false)
                    header('location:index.php?InvalidAccount');
                    else
                    header('location:index.php?UserDoesNotExist');
                }
            }else{
                header('location:index.php?CheckPass');
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <title>DashBoard</title>
</head>
<body>
    <?php
        if(isset($_SESSION['User']))
        {
            echo"<div class='dash'>
            <a href='Products.php'>List Products</a>
            <a href='Commands.php'>My Commands</a>
            <a href='logout.php'>Log out</a>
        </div>
        <div class='container'>
        <div class='card'>
            <img src='muser.png' alt=''>
            <h3>".$_SESSION['User']."</h3>
            <p>Welcome,Again :p!!</p>
            <a href='Commands.php'>Check out Commands</a>
        </div>";
        }else{
            echo "<div class='dash'>
            <a href='Products.php'>Products</a>
            <a href='Commands.php'>Commands</a>
            <a href='Inscriptions.php'>Inscriptions</a>
            <a href='logout.php'>Log out</a>
        </div>
        <div class='container'>
        <div class='card'>
            <img src='muser.png' alt=''>
            <h3>".$_SESSION['Admin']."</h3>
            <p>Welcome,Again Chef :p!!</p>
            <a href='Commands.php'>Check out Commands</a>
        </div>";
        }
    ?>
    <div class="container">
        <div class="card">
            <img src="product.png" alt="">
            <h3>New Offers</h3>
            <p>Check Out The Latest Products :D</p>
            <a href="Products.php">Check</a>
        </div>
    </div>
</body>
</html>