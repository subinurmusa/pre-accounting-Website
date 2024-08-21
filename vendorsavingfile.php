<?php
$formData = $_POST; // Get the form data

// Extract form data into variables
$vendorName = isset($formData['vendorName']) ? $formData['vendorName'] : null;
$category = isset($formData['category']) ? $formData['category'] : null;
$email = isset($formData['email']) ? $formData['email'] : null;
$TCKNorVKN = isset($formData['VKNorTCKN']) ? $formData['VKNorTCKN'] : null;
$phoneNum = isset($formData['phoneNum']) ? $formData['phoneNum'] : null;
$FaxNum = isset($formData['FaxNum']) ? $formData['FaxNum'] : null;
$ibanNum = isset($formData['ibanNum']) ? $formData['ibanNum'] : null;
$address = isset($formData['address']) ? $formData['address'] : null;
$postakodu = isset($formData['postakodu']) ? $formData['postakodu'] : null;
$notes = isset($formData['notes']) ? $formData['notes'] : null;
$userId = isset($formData['userId']) ? $formData['userId'] : null;

if (empty($vendorName) || empty($phoneNum)) {
    echo "<div class='alert alert-danger'> Tadarikçi Adı ve İletişim numarası Gereklidir </div>";
} else {
    require "db.php"; // Prepare and execute the SQL statement
    $sql = $db->prepare("INSERT INTO `vendors`(`vendorName`, `category`, `email`, `phoneNum`, `FaxNum`, `ibanNum`, `address`, `postakodu`, `notes`, `TCKNorVKN`, `userId`)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)");

    $sql->execute([$vendorName, $category, $email, $phoneNum, $FaxNum, $ibanNum,$address,$postakodu,$notes,$TCKNorVKN,$userId]);

    if ($sql) {
        // Redirect to vendors.php
        echo "<script>window.location.href = 'vendors.php';</script>";
        exit(); // Make sure to exit after redirection
    } else {
       echo $error = "<div class='alert alert-danger'>An error occurred while saving data.</div>";
       
    }
}
?>
