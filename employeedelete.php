<?php
require "db.php";
$del=isset($_GET["id"])? $_GET["id"]: null;
if(!empty($del)){
    $sql= $db-> prepare("DELETE FROM employees WHERE id = ?");
    $sql -> execute([$del]);
    header("location:employees.php");
}
?>