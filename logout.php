<?php
    
    // if(isset($_SESSION['Admin']) ){
    //    session_destroy();
    //    echo "gfdqs";
    //     header('location:index.php');
    // }
    // if(isset($_SESSION['User']) ){
    //     session_destroy();
    //     echo "gfdqs";
    //      header('location:index.php');
    //  }
   session_start();
   if(isset($_SESSION['Admin']) ){
        session_destroy();
        header('location:index.php');
    }
    if(isset($_SESSION['User']) ){
        session_destroy();
        header('location:index.php');
    }
?>