<?php
    session_start();
    if(!isset($_SESSION['Admin']))
    {
        if(!isset($_SESSION['User']))
        header('location:index.php?fromProducts');
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
       if(isset($_SESSION['Admin']))
       {
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
                return $AllProducts;
           }
           function ShowAllProducts()
           {
            echo "<div class='card'>
            <img src='add.png' alt='AddProduct'>
            <h3>Add a new product</h3>
            <a href='Products.php?AddProduct'>Add Product</a>
        </div>";
                $Products=fopen('products.csv','a+');
                for($i=0;$i<GetProductsRows()-1;$i++)
                {
                    $Product=fgetcsv($Products,0,";");
                    echo "<div class='card'>
                    <img src='".$Product[2]."' alt='Product'>
                    <h3>".$Product[0]."</h3>
                    <p>Date Last Update : ".$Product[3]."</p>
                    <p>Description: ".$Product[1]."</p>
                    <p style='color:Green'> Quantity :".$Product[4]."</p>
                    <a href='Products.php?ProductIdToUpdate=".$Product[0]."'>Update</a>
                    <a style='color:red' href='Products.php?ProductIdToDelete=".$Product[0]."'>Delete</a>
                    </div>";
                }
           }
           if(!empty($_GET['ProductIdToDelete']))
            {
                if(file_exists('products.csv'))
                {   $IdProduct=$_GET['ProductIdToDelete'];
                    $AllProducts=GetAllProducts();
                    $Products=fopen('products.csv','w');
                    for($i=0;$i<count($AllProducts);$i++)
                    {
                        $Product=($AllProducts[$i]);
                        if($Product[0]!=$IdProduct)
                        {
                            fputcsv($Products,$Product,";");
                        }
                    }
                    header('location:Products.php?DeletedSuccessfully');
                }
            } else if(!empty($_GET['ProductIdToUpdate']))
            {
                if(file_exists('products.csv'))
                {
                    $IdProduct=$_GET['ProductIdToUpdate'];
                    $Products=fopen('products.csv','a+');
                    for($i=0;$i<GetProductsRows()-1;$i++)
                    {
                        $Product=fgetcsv($Products,0,';');
                        if($Product[0]==$IdProduct)
                        {
                                echo "<form class='vide' action='Products.php?ToUpdate' method='POST'>
                                <img src='".$Product[2]."' alt=''>
                                <h2>".$Product[0]."</h2>
                                <input  name='IdProduct' type='hidden' placeholder='ProductID(Product Name)' value='".$Product[0]."'>
                                <input name='ProductDesc' type='text' required placeholder='ProductDescription(Product)' value='".$Product[1]."'>
                                <input name='DateLastUpdate' type='date' required value='".$Product[3]."'>
                                <input name='ProductQuantity' type='text' required placeholder='ProductQuantity(Product)' value='".$Product[4]."'>
                                <input type='submit' value='Update'>
                            </form>";
                        }
                    }
                }
            }else if(isset($_GET['AddProduct']))
            {   if(isset($_GET['ProductNotExist']))
                    echo "<script> alert('This Product is already exist !!! ?'); </script>";
                echo "<form class='vide' action='Products.php?ToAdd' method='POST'>
                <h2>Add New Product</h2>
                <input  name='IdProduct' type='text' required placeholder='ProductID(Product Name)'>
                <input name='ProductDesc' type='text' required placeholder='ProductDescription(Product)'>
                <input name='DateLastUpdate' type='date' required>
                <input name='ProductQuantity' type='text' required placeholder='ProductQuantity(Product)'>
                <input type='submit' value='Add'>
            </form>";
            }else if(!empty($_POST['IdProduct']) AND !empty($_POST['ProductDesc']) AND !empty($_POST['DateLastUpdate']) AND !empty($_POST['ProductQuantity']) AND isset($_GET['ToUpdate']))
            {
                if(file_exists('products.csv'))
                {   $IdProduct=$_POST['IdProduct'];
                    $ProductDesc=$_POST['ProductDesc'];
                    $DateLastUpdate=$_POST['DateLastUpdate'];
                    $ProductQuantity=$_POST['ProductQuantity'];
                    $AllProducts=GetAllProducts();
                    print_r($AllProducts);
                    $Products=fopen('products.csv','w');
                    for($i=0;$i<count($AllProducts);$i++)
                    {
                        $Product=($AllProducts[$i]);
                        if($Product[0]==$IdProduct)
                        {
                            $Product[1]=$ProductDesc;
                            $Product[3]=$DateLastUpdate;
                            $Product[4]=$ProductQuantity;
                        }
                        fputcsv($Products,$Product,";");
                    }
                    header('location:Products.php?UpdatedSuccessfully');
                }
            }else if(!empty($_POST['IdProduct']) AND !empty($_POST['ProductDesc']) AND !empty($_POST['DateLastUpdate']) AND !empty($_POST['ProductQuantity']) AND isset($_GET['ToAdd']))
            {
                if(file_exists('products.csv'))
                {
                    $IdProduct=$_POST['IdProduct'];
                    $ProductDesc=$_POST['ProductDesc'];
                    $DateLastUpdate=$_POST['DateLastUpdate'];
                    $ProductQuantity=$_POST['ProductQuantity'];
                    $Check=true;
                    $Products=fopen('products.csv','a+');
                    for($i=0;$i<GetProductsRows()-1;$i++)
                    {
                        $Product=fgetcsv($Products,0,';');
                        if($Product[0]==$IdProduct)
                        {
                            $Check=false;
                            header('location:Products.php?AddProduct&ProductNotExist');
                        }
                    }
                    if($Check){
                            fputcsv($Products,array($IdProduct,$ProductDesc,'product.png',$DateLastUpdate,$ProductQuantity),';');
                            header('location:Products.php?AddedSuccessfully');
                    }
                }
            }else
            {
                ShowAllProducts();
            }
       }
       if(isset($_SESSION['User']))
       {
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
             return $AllProducts;
        }
        function ShowAllProductsForUser()
        {
             $Products=fopen('products.csv','a+');
             for($i=0;$i<GetProductsRows()-1;$i++)
             {
                 $Product=fgetcsv($Products,0,";");
                 if($Product[4]>0)
                 {
                    echo "<div class='card'>
                    <img src='".$Product[2]."' alt='Product'>
                    <h3>".$Product[0]."</h3>
                    <p>Date Last Update : ".$Product[3]."</p>
                    <p>Description: ".$Product[1]."</p>
                    <p style='color:Green'> Quantity :".$Product[4]."</p>
                    <a href='Products.php?IdProductToBuy=".$Product[0]."'>Buy</a>
                    </div>";
                 }else
                 {
                    echo "<div class='card'>
                    <img src='".$Product[2]."' alt='Product'>
                    <h3>".$Product[0]."</h3>
                    <p>Date Last Update : ".$Product[3]."</p>
                    <p>Description: ".$Product[1]."</p>
                    <p style='color:Green'> Quantity :".$Product[4]."</p>
                    <a disabled style='color:red' href='#'>Out Of Stock</a>
                    </div>";
                 }
             }
        }
        if(!empty($_GET['IdProductToBuy']))
        {
            $AllProducts=GetAllProducts();
            $IdProduct=$_GET['IdProductToBuy'];
            $Check=true;
            for($i=0;$i<count($AllProducts);$i++)
            {
                if($AllProducts[$i][0]==$IdProduct)
                {
                    echo "<form class='vide' action='Commands.php?ToCommand' method='POST'>
                    <img src=".$AllProducts[$i][2].">
                <h2>Buy :".$AllProducts[$i][0]."</h2>
                <p>Description".$AllProducts[$i][1]."</p>
                <input  name='IdProduct' type='hidden' value='".$AllProducts[$i][0]."' placeholder='ProductID(Product Name)'>
                <p style='color:Green'> Quantity :".$AllProducts[$i][4]."</p>
                <select name='CommandQuantity' required>";
                for($j=1;$j<($AllProducts[$i][4])+1;$j++)
                    echo "<option>".$j."</option>";
                echo"</select>
                <input type='submit' value='Commander'>
            </form>";
                    $Check=false;
                }
            }
            if($Check)
                header('location:Products.php');
        }else
        {
            ShowAllProductsForUser();
        }
       }
       ?>
    </div>
</body>
</html>