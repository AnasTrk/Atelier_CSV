<?php
    session_start();
    if(!isset($_SESSION['User']) AND !isset($_SESSION['Admin']))
    {
        header('location:index.php?fromCommands');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(!empty($_GET['ProductIdToUpdate']) OR isset($_GET['AddProduct']))
            echo " <link rel='stylesheet' href='style2.css'>";
            else
            echo " <link rel='stylesheet' href='style2.css'>";
    ?>
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <title>Products</title>
</head>
<body>
<?php
            echo"<div class='dash'>
            <a href='Dash.php'>Back</a>
            <a href='logout.php'>Log out</a>
        </div>";
    ?>
<div class="container">
    <?php
        function GetProductsRows()
        {    $counter=0;
            $Products=fopen('products.csv','a+');
            while(!feof($Products))
            {
                $Product=fgetcsv($Products,0,';');
                $counter++;
            }
            return $counter;
        }
        function GetAllProducts()
           {
               $AllProducts;
                $Products=fopen('products.csv','r+');
                for($i=0;$i<GetProductsRows()-1;$i++)
                {
                    $Product=fgetcsv($Products,0,';');
                    $AllProducts[]=$Product;
                }
                if(!empty($AllProducts))
                return $AllProducts;
                else
                return false;
           }
           function GetProductById($IdProduct)
           {
                if(file_exists('products.csv'))
                {
                    $AllProducts=GetAllProducts();
                    $Check=true;
                    if($AllProducts==false){
                        return false;
                    }else
                    {
                                for($i=0;$i<count($AllProducts);$i++)
                            {
                                if($IdProduct===$AllProducts[$i][0])
                                {
                                    return $AllProducts[$i];
                                    $Check=false;
                                }
                            }
                            if($Check){
                                return false;
                            }
                    }
                }
           }
           /****************************************************************** */
           function GetCommandsRows()
           {
            $counter=0;
            $Commands=fopen('commands.csv','a+');
            while(!feof($Commands))
            {
                $Command=fgetcsv($Commands,0,';');
                $counter++;
            }
            return $counter;
           }
           /**************************************************************** */
           function GetAllCommands()
           {
            $AllCommands;
            $Commands=fopen('commands.csv','r+');
            for($i=0;$i<GetCommandsRows()-1;$i++)
            {
                $Command=fgetcsv($Commands,0,';');
                $AllCommands[]=$Command;
            }
            return $AllCommands;

           }
           /****************************************************************** */
           function GetCommandsByUserId($UserId)
           {
                if(file_exists('commands.csv'))
                    {
                        $AllCommands=GetAllCommands();
                        $CommandsByUser;
                        for($i=0;$i<count($AllCommands);$i++)
                        {
                            if($UserId===$AllCommands[$i][1])
                            {
                                $CommandsByUser[]= $AllCommands[$i];
                            }
                        }
                        return $CommandsByUser;;
                    }
           }
           /******************************************************************* */
        if(isset($_SESSION['User']))
        {
            $User=$_SESSION['User'];
            if(!empty($_POST['IdProduct']) AND !empty($_POST['CommandQuantity']) AND isset($_GET['ToCommand']))
            {
                $IdProduct=$_POST['IdProduct'];
                $CommandQuantity=$_POST['CommandQuantity'];
                $AllProducts=GetAllProducts();
                $Check=true;
                $Commands=GetCommandsByUserId($User);
                for($i=0;$i<count($Commands);$i++)
                {
                    if($Commands[$i][0]==$IdProduct)
                    {
                        $Check=false;
                    }
                }
                if($Check){
                    $Products=fopen('products.csv','w');
                    for($i=0;$i<count($AllProducts);$i++)
                    {
                        if($IdProduct===$AllProducts[$i][0])
                        {
                            $AllProducts[$i][4]=$AllProducts[$i][4]-$CommandQuantity;
                        }
                        fputcsv($Products,$AllProducts[$i],";");
                    }
                    fclose($Products);
                    if(file_exists('commands.csv'))
                    {
                        $Commands=fopen('commands.csv','a+');
                        fputcsv($Commands,array(GetProductById($IdProduct)[0],$User,$CommandQuantity,date('d-m-yy')),';');
                        fclose($Commands);
                    }
                }else
                {
                            $Product=GetProductById($IdProduct);
                            for($i=0;$i<count($Commands);$i++)
                            {
                                if($Commands[$i][0]==$IdProduct)
                                echo "<div class='card'>
                                <img src='".$Product[2]."' alt='Product'>
                                <h3> This (".$Product[0].")</h3>
                                <p  style='color:Green' >Product is already commanded</p>
                                <p>Date Last Update : ".$Commands[$i][3]."</p>
                                <p>Description: ".$Product[1]."</p>
                                <p style='color:Green'> Existing Quantity :".$Product[4]."</p>
                                <p style='color:Green'> Commanded Quantity :".$Commands[$i][2]."</p>
                                <a style='color:red' href='Commands.php?=".$Product[0]."'>Check Your Command</a>
                                </div>";
                            }
                }
            }else{
                $Commands=GetCommandsByUserId($User);
                for($i=0;$i<count($Commands);$i++)
                {   $Command=$Commands[$i];
                    $Product=GetProductById($Command[0]);
                    if($Product==false)
                    {
                        echo "<div class='card'>
                        <img src='product.png' alt='Product'>
                        <h3>".$Command[0]."</h3>
                        <p>Date Creation: ".$Command[3]."</p>
                        <p style='color:red'>Description: This Product has been deleted by administration :/</p>
                        <p style='color:Green'> Commanded Quantity :".$Command[2]."</p>
                        <a style='color:red' href='Commands.php?CommandToReport=".$Command[0]."'>Report</a>
                        </div>";
                    }else{
                        echo "<div class='card'>
                        <img src='".$Product[2]."' alt='Product'>
                        <h3>".$Product[0]."</h3>
                        <p>Date Last Update : ".$Product[3]."</p>
                        <p>Description: ".$Product[1]."</p>
                        <p style='color:Green'> Existing Quantity :".$Product[4]."</p>
                        <p style='color:Green'> Commanded Quantity :".$Command[2]."</p>
                        <a href='Commands.php?ProductIdToUpdate=".$Product[0]."'>Update</a>
                        <a style='color:red' href='Commands.php?CommandIdToDelete=".$Product[0]."'>Decline</a>
                        </div>";
                    }
                }
            }
        }
    ?>
  </div>
</body>
</html>