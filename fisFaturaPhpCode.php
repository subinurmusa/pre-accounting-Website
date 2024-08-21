<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

$visitcount = 7;
$num = 0;
$error = "";
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";

$sqluserid = $db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId = $sqluserid->fetch(PDO::FETCH_ASSOC);

ini_set('display_errors', 0);
ini_set('log_errors', 1);

$response = ["success" => false, "message" => ""];

try {
    if (isset($_POST['submit'])) {
        // Debugging: Log the received POST data
        error_log("POST data: " . print_r($_POST, true));
        
        $kayitIsmi = isset($_POST['kayitIsmi']) ? $_POST['kayitIsmi'] : null;
        $vendor = isset($_POST['vendor']) ? $_POST['vendor'] : null;
        $fis_fatura_tarihi = isset($_POST['fis_fatura_tarihi']) ? $_POST['fis_fatura_tarihi'] : 0;
        $fis_fatura_number = isset($_POST['fis_fatura_number']) ? $_POST['fis_fatura_number'] : 0;
        $currency = isset($_POST['currency']) ? $_POST['currency'] : null;
        $odeme_durumu = isset($_POST['odeme_durumu']) ? $_POST['odeme_durumu'] : null;
        $odenecek_tarih = isset($_POST['odenecek_tarih']) ? $_POST['odenecek_tarih'] : null;
        $gross = isset($_POST['gross']) ? $_POST['gross'] : null;
        $toplamkdv = isset($_POST['toplamkdv']) ? $_POST['toplamkdv'] : null;
        $toplamtutar = isset($_POST['toplamtutar']) ? $_POST['toplamtutar'] : null;
        $toplamiskonto = isset($_POST['toplamiskonto']) ? $_POST['toplamiskonto'] : null;

        if (empty($kayitIsmi) || empty($vendor) || empty($fis_fatura_number)) {
            $response["message"] = "Kayıt ismi /tedarikçi/ fiş fatura/ alanları gereklidir  Gereklidir";
        } else {
            $products = [];

            for ($index = 1; $index < 50; $index++) {
                if (!isset($_POST['urunhizmet' . $index])) {
                    break;
                }

                $productname = $_POST['urunhizmet' . $index];
                $iskonto = isset($_POST['iskonto' . $index]) ? $_POST['iskonto' . $index] : null;
                $miktar = isset($_POST['miktar' . $index]) ? $_POST['miktar' . $index] : 0;
                $kdv = isset($_POST['kdv' . $index]) ? $_POST['kdv' . $index] : null;
                $birim = isset($_POST['hiddenbirim' . $index]) ? $_POST['hiddenbirim' . $index] : null;
                $birimfiyat = isset($_POST['hiddenbirimfiyat' . $index]) ? $_POST['hiddenbirimfiyat' . $index] : null;
                $urunfiyat = isset($_POST['hiddenurunfiyat' . $index]) ? $_POST['hiddenurunfiyat' . $index] : null;

                $currentProduct = [
                    'productname' => $productname,
                    'iskonto' => $iskonto,
                    'miktar' => $miktar,
                    'kdv' => $kdv,
                    'birim' => $birim,
                    'birimfiyat' => $birimfiyat,
                    'urunfiyat' => $urunfiyat
                ];

                $products[] = $currentProduct;
            }

            $productsJson = json_encode($products);

            $sql = $db->prepare("INSERT INTO `fisfaturagiderler`(`titleName`, `vendor`, `fisFaturaNum`, `currency`, `status`, `dueDate`, `products`, `araToplam`, `toplamkdv`, `geneltoplam`,`fisFaturaDate`, `toplamIskonto`, `userId`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

            $sqlExecuted = $sql->execute([$kayitIsmi, $vendor, $fis_fatura_number, $currency, $odeme_durumu, $odenecek_tarih, $productsJson, $gross, $toplamkdv, $toplamtutar, $fis_fatura_tarihi, $toplamiskonto, $userId["id"]]);

            if ($sqlExecuted) {
                $response["success"] = true;
                $response["message"] = "Kayıt başarıyla eklendi.";
            } else {
                $response["message"] = "Veritabanına kayıt sırasında bir hata oluştu.";
                error_log("SQL execution error: " . print_r($sql->errorInfo(), true));
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
