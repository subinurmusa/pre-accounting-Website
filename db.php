<?php
    try {
        $db = new PDO("mysql:host=localhost;dbname=accountingapp;", "root","");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e;
    }

    error_reporting(0);
     ini_set( 'display_errors', 1 );
?> 