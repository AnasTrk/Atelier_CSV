<?php
    session_start();
    if(isset($_SESSION['User']) )
    {
        header('location:Dash.php?fromInscriptions');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <title>Inscriptions</title>
</head>
<body>
    <?php
        if(isset($_SESSION['Admin']))
        {
            echo"<div class='dash'>
            <a href='Dash.php'>Back</a>
            <a href='logout.php'>Log out</a>
        </div>";
        }
    ?>
    <div class="container">
        
       <?php
                   /***********************************FUNCTION RETURNING THE NUMBER OF EXISTING ROWS IN CSV FILE*********************** */
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
                        /***********************************FUNCTION RETURNING ALL INVALID USERS*********************** */
            function GetInvalidUsers()
            {
                if(file_exists('users.csv'))
                {   $UsersInvalid;
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<GetTotalRows()-1;$i++)
                    {
                        $User=fgetcsv($Users,0,";");
                        if($User[3]==0)
                        {
                            $UsersInvalid[]=$User;
                        }
                    }
                    if(empty($UsersInvalid))
                        return false;
                    else
                        return $UsersInvalid;
                    fclose($Users);
                }
            }
            /***SURCHARGE */
            function GetInvalidUsers_()
            {
                if(file_exists('users.csv'))
                {   $UsersInvalid;
                    $Users=fopen('users.csv','r+');
                    for($i=0;$i<GetTotalRows()-1;$i++)
                    {
                        //print_r(fgetcsv($Users,0,";"));
                        $User=fgetcsv($Users,0,";");
                        if($User[3]==0)
                        {
                            $UsersInvalid[]=$User;
                        }
                    }
                    fclose($Users);
                    if(!empty($UsersInvalid))
                    {
                        for($i=0;$i<count($UsersInvalid);$i++)
                        {
                            $User=$UsersInvalid[$i];
                            echo "<div class='card'>
                            <img src='muser.png' alt=''>
                            <h3>".$User[1]."    ".$User[0]."</h3>
                            <p>Date D''inscription est : ".$User[4]."</p>
                            <p style='color:red'> Invalid Account</p>
                            <a href='Inscriptions.php?UserID=".$User[0]."'>Valid</a>
                            <a style='color:red' href='Inscriptions.php?UserIdToDelete=".$User[0]."'>Delete</a>
                            </div> ";
                        }
                        return $UsersInvalid;
                    }else
                    {
                        return false;
                    }
                }
            }
            /***********************************FUNCTION RETURNING ALL VALID USERS (INCLUDING THE ADMIN)*********************** */
            function GetValidUsers()
            {
                if(file_exists('users.csv'))
                {   $UsersValid;
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<GetTotalRows()-1;$i++)
                    {
                        $User=fgetcsv($Users,0,";");
                        if($User[3]==1)
                        {
                            $UsersValid[]=$User;
                        }
                    }
                    if(empty($UsersValid))
                        return false;
                    else
                        return $UsersValid;
                    fclose($Users);
                }
            }
            /***SURCHARGE */
            function GetValidUsers_()
            {
                if(file_exists('users.csv'))
                {   $UsersValid;
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<GetTotalRows()-1;$i++)
                    {
                        //print_r(fgetcsv($Users,0,";"));
                        $User=fgetcsv($Users,0,";");
                        if($User[3]==1 AND $User[0]!=$_SESSION['Admin'])
                        {
                            $UsersValid[]=$User;
                        }
                    }
                    fclose($Users);
                    if(!empty($UsersValid))
                    {
                        for($i=0;$i<count($UsersValid);$i++)
                        {
                            $User=$UsersValid[$i];
                            echo "<div class='card'>
                            <img src='muser.png' alt=''>
                            <h3>".$User[1]."    ".$User[0]."</h3>
                            <p>Date D''inscription est : ".$User[4]."</p>
                            <p style='color:Green'> valid Account</p>
                            <a href='Inscriptions.php?UserIdToInvalid=".$User[0]."'>Invalid</a>
                            <a style='color:red' href='Inscriptions.php?UserIdToDelete=".$User[0]."'>Delete</a>
                            </div> ";
                        }
                        return $UsersValid;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            /*******************FUNCTION FOR VALID A USER****/
            function ValidUser($Username)
            {   $ValidUsers=GetValidUsers();
                $InvalidUsers=GetInvalidUsers();
                $UpdatedUsers;
                if(file_exists('users.csv'))
                {
                    $Users=fopen('users.csv','w');
                    for($i=0;$i<count($InvalidUsers);$i++)
                    {   $User=$InvalidUsers[$i];
                        if($User[0]==$Username)
                        {
                            $User[3]=1;
                        }
                        fputcsv($Users,$User,";");
                    }
                    fclose($Users);
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<count($ValidUsers);$i++)
                    {   $User=$ValidUsers[$i];
                        // if($User[0]!=$Username)
                        // {
                            fputcsv($Users,$User,";");
                        // }
                    }
                    fclose($Users);
                }
            }
             /*******************FUNCTION FOR INVALID A USER****/
             function InvalidUser($Username)
            {   $ValidUsers=GetValidUsers();
                $InvalidUsers=GetInvalidUsers();
                $UpdatedUsers;
                if(file_exists('users.csv'))
                {
                    $Users=fopen('users.csv','w');
                    for($i=0;$i<count($ValidUsers);$i++)
                    {   $User=$ValidUsers[$i];
                        if($User[0]==$Username)
                        {
                            $User[3]=0;
                        }
                        fputcsv($Users,$User,";");
                    }
                    fclose($Users);
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<count($InvalidUsers);$i++)
                    {   $User=$InvalidUsers[$i];
                        // if($User[0]!=$Username)
                        // {
                            fputcsv($Users,$User,";");
                        // }
                    }
                    fclose($Users);
                }
            }
            /*********************************FUNCTION TO DELETE A USER******************************* */
            function DeleteUser($Username)
            {
                $ValidUsers=GetValidUsers();
                $InvalidUsers=GetInvalidUsers();
                $UpdatedUsers;
                if(file_exists('users.csv'))
                {
                    $Users=fopen('users.csv','w');
                    for($i=0;$i<count($ValidUsers);$i++)
                    {   $User=$ValidUsers[$i];
                        if($User[0]!=$Username)
                        {
                            fputcsv($Users,$User,";");
                        }
                    }
                    fclose($Users);
                    $Users=fopen('users.csv','a+');
                    for($i=0;$i<count($InvalidUsers);$i++)
                    {   $User=$InvalidUsers[$i];
                         if($User[0]!=$Username)
                         {
                            fputcsv($Users,$User,";");
                         }
                    }
                    fclose($Users);
                }
            }
            /****CHECK IF CSV EMPTY OR NOT*************************************** */
            function CheckUsers()
            {
                $ValidUsers= GetValidUsers_();
                $InvalidUsers=GetInvalidUsers_();
                if($ValidUsers==false AND $InvalidUsers==false)
                {
                    echo "<div class='vide'>
                          <img src='uvide.png' alt='ddd'>
                          <h5>No User is here</h5>
                    </div>";
                }
            }
            /***************************************************************** */
            if(!empty($_GET["UserID"]))
            {
                ValidUser($_GET['UserID']);
                header('location:Inscriptions.php');
            }else if(!empty($_GET["UserIdToInvalid"]))
            {
                InvalidUser($_GET["UserIdToInvalid"]);
                header('location:Inscriptions.php');
            }else if(!empty($_GET["UserIdToDelete"]))
            {
                DeleteUser($_GET["UserIdToDelete"]);
                header('location:Inscriptions.php');
            }else
            {
                // GetValidUsers_();
                // GetInvalidUsers_();
                CheckUsers();
            }
       ?>
    </div>
</body>
</html>