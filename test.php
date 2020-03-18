<?php
        $file=fopen('user.csv',"w+");
        $arr;
        $arr[]="admin";
        $arr[]="admin";
        $arr[]="0";
        $arr[]="1";
        fputcsv($file,$arr,";");
        $arr1;
        $arr1[]="user";
        $arr1[]="user";
        $arr1[]="0";
        $arr1[]="1";
        fputcsv($file,$arr1,";");
        fclose($file);
        // $file_read=fopen('user.csv',"r+");
        // while(!feof($file_read))
        // {
        //     $user=fgetcsv($file_read,0,";");
        //     echo $user[0];
        // }
    ?>