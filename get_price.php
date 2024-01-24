<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productid = $_POST["productid"];

    $sql = $db->prepare("SELECT `price`, `alSatBirim` FROM `products` WHERE id=?");
    $sql->execute([$productid]);

    if (!$sql) {
        die("Query failed: " . $db->errorInfo()[2]);
    }

    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // Output specific fields
    /* echo $row['price'];
    echo $row['alSatBirim']; */
    $responseArray = array(
        'price' => $row['price'],
        'alSatBirim' => $row['alSatBirim']
    );

    // Convert the array to a JSON string
    $jsonResponse = json_encode($responseArray);

    // Echo the JSON string
    echo $jsonResponse;
}
?>