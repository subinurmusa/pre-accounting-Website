<?php
$formData = $_POST; // Get the form data

// Extract form data into variables
$vendorName = isset($formData['vendorName']) ? $formData['vendorName'] : null;
$category = isset($formData['category']) ? $formData['category'] : null;
$email = isset($formData['email']) ? $formData['email'] : null;
$TCKNorVKN = isset($formData['VKNorTCKN']) ? $formData['VKNorTCKN'] : 0;
$phoneNum = isset($formData['phoneNum']) ? $formData['phoneNum'] : 0;
$FaxNum = isset($formData['FaxNum']) ? $formData['FaxNum'] : null;
$ibanNum = isset($formData['ibanNum']) ? $formData['ibanNum'] : null;
$address = isset($formData['address']) ? $formData['address'] : null;
$postakodu = isset($formData['postakodu']) ? $formData['postakodu'] : 0;
$notes = isset($formData['notes']) ? $formData['notes'] : null;
$id = isset($formData['id']) ? $formData['id'] : 0;

if (empty($vendorName) || $phoneNum==0) {
    echo "<div class='alert alert-danger'> Tadarikçi Adı ve İletişim numarası Gereklidir </div>";
} else {
    require "db.php"; // Prepare and execute the SQL statement
    $sql = $db->prepare("UPDATE `vendors` SET `vendorName`=?, `category`=?, `email`=?, `phoneNum`=?, `FaxNum`=?, `ibanNum`=?, `address`=?, `postakodu`=?, `notes`=?, `TCKNorVKN`=?
    where id=?");

    $sql->execute([$vendorName, $category, $email, $phoneNum, $FaxNum, $ibanNum,$address,$postakodu,$notes,$TCKNorVKN,$id]);

    if ($sql) {
        // Redirect to vendors.php
        echo "<script>window.location.href = 'vendors.php';</script>";
        exit(); // Make sure to exit after redirection
    } else {
       echo $error = "<div class='alert alert-danger'>An error occurred while saving data.</div>";
       
    }
}
?>
