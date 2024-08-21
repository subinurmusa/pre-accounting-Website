<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

ini_set('display_errors', 0); // Suppress errors in production
ini_set('log_errors', 1);
require "db.php";

$response = ["success" => false, "message" => ""];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input
        $title = isset($_POST['kayitIsmi']) ? $_POST['kayitIsmi'] : "null";
        $duzenleme_tarihi = isset($_POST['duzenleme_tarihi']) ? $_POST['duzenleme_tarihi'] : "null";
        $toplam_tutar = isset($_POST['toplam_tutar']) ? $_POST['toplam_tutar'] : 0;
        $status = isset($_POST['odeme_durumu']) ? $_POST['odeme_durumu'] : 0;
        $vade_tarihi = isset($_POST['vade_tarihi']) ? $_POST['vade_tarihi'] : "null";
        $category = isset($_POST['category']) ? $_POST['category'] : "null";
        $editId = isset($_POST['editId']) ? $_POST['editId'] : null;
     //   var_dump($_POST);
     
        if (empty($title) || empty($toplam_tutar) || empty($vade_tarihi) || empty($category)) {
            $response["message"] = "Kayıt ismi / vade tarihi / toplam tutar alanları gereklidir.";
        } else {
            $userId = $db->prepare("SELECT id FROM `users` WHERE username = ?;");
            $userId->execute([$_SESSION["username"]]);
            $userId = $userId->fetch(PDO::FETCH_ASSOC);

            if ($editId) {
                $sql = $db->prepare("UPDATE `allGiderler` 
                    SET `title` = ?, `category` = ?, `issueDate` = ?, 
                        `totalCost` = ?, `status` = ?, `dueDate` = ?, `type` = ? 
                    WHERE `id` = ?");
                $sql->execute([$title, $category, $duzenleme_tarihi, $toplam_tutar, $status, $vade_tarihi, "Şirket Giderleri", $editId]);
            } else {
                $sql = $db->prepare("INSERT INTO `allGiderler` 
                    (`title`, `category`, `issueDate`, `totalCost`, `status`, `dueDate`, `type`, `userId`) 
                    VALUES (?,?,?,?,?,?,?,?)");
                $sql->execute([$title, $category, $duzenleme_tarihi, $toplam_tutar, $status, $vade_tarihi, "Şirket Giderleri", $userId["id"]]);
            }

            if ($sql->rowCount() > 0) {
                $response["success"] = true;
                $response["message"] = "Kayıt başarıyla eklendi.";
            } else {
                $response["message"] = "Veritabanına kayıt sırasında bir hata oluştu.";
                error_log("SQL execution error: " . print_r($errorInfo, true));
            }
        }
    } else {
        $response["message"] = "Form submit button not set.";
    }
} catch (Exception $e) {
    error_log("Caught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    $response["message"] = "Bir hata oluştu: {$e->getMessage()}";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
