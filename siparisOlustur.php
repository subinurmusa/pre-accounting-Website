<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

require "db.php";

$userIdQuery = $db->prepare("SELECT id FROM users WHERE username = ?");
$userIdQuery->execute([$_SESSION["username"]]);
$userId = $userIdQuery->fetch(PDO::FETCH_ASSOC)["id"];

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $musteri = $_POST['musteri'] ?? "";
    $tarih = $_POST['tarih'] ?? "";
    $productname = $_POST['urunhizmet1'] ?? "";
    $paymentType = $_POST['paymentType'] ?? "";
    $vadetarihi = $_POST['vadetarihi'] ?? "";
    $durum = $_POST['durum'] ?? "bb";
    $toplamtekliftutar = $_POST['hiddentoplamtekliftutar'] ?? 0;
    $gross = $_POST['hiddenbürüt'] ?? 0;
    $toplamiskonto = $_POST['hiddentoplamiskonto'] ?? 0;
    $toplamkdv = $_POST['hiddentoplamkdv'] ?? 0;
    $productcode = isset($_POST['ordernumber_hidden']) ? $_POST['ordernumber_hidden'] : null;
    $products = [];

    for ($index = 1; $index < 50; $index++) {
        if (!isset($_POST['urunhizmet' . $index])) {
            break;
        }

        $currentProduct = [
            'productname' => $_POST['urunhizmet' . $index],
            'iskonto' => $_POST['iskonto' . $index] ?? null,
            'miktar' => $_POST['miktar' . $index] ?? 0,
            'kdv' => $_POST['kdv' . $index] ?? null,
            'birim' => $_POST['hiddenbirim' . $index] ?? null,
            'birimfiyat' => $_POST['hiddenbirimfiyat' . $index] ?? null,
            'urunfiyat' => $_POST['hiddenurunfiyat' . $index] ?? null,
        ];

        $products[] = $currentProduct;
    }

    if (empty($musteri)) {
        $response["message"] = "Müşteriler doldurulması zorunlu alanlardır";
    } elseif (empty($tarih)) {
        $response["message"] = "Tarih doldurulması zorunlu alanlardır";
    } elseif (empty($productname)) {
        $response["message"] = "Ürün Seçmelisiniz";
    } else {
        try {
            $productsJson = json_encode($products);
            $sql = $db->prepare("INSERT INTO `selling`(`productcode`, `costomer`, `paymentType`, `products`, `date-added`, `status`, `totalPrice`, `totalGrossPrice`, `totaliskonto`, `totalkdv`, `vadetarihi`, `userId`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $sql->execute([$productcode, $musteri, $paymentType, $productsJson, $tarih, $durum, $toplamtekliftutar, $gross, $toplamiskonto, $toplamkdv, $vadetarihi, $userId]);

            if ($sql) {
                $response["success"] = true;
                $response["message"] = "Sipariş başarıyla oluşturuldu!";
            } else {
                $response["message"] = "Veritabanına kayıt sırasında bir hata oluştu.";
            }
        } catch (Exception $e) {
            error_log("Caught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $response["message"] = "Bir hata oluştu: {$e->getMessage()}";
        }
    }
}
header('Content-Type: application/json');
echo json_encode($response);

?>
