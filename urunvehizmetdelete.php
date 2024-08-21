<?php
require "db.php";
$del=isset($_GET["id"])? $_GET["id"]: null;
if(!empty($del)){
    $sql= $db-> prepare("DELETE FROM products WHERE id = ?");
    $sql -> execute([$del]);
    header("Location: urunvehizmet.php");
}
?>