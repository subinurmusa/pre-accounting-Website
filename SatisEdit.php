<?php
// require "db.php";
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
$num = 0;
$error = "";
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "db.php";
// current user id on the system
$sqluserid=$db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId=$sqluserid->fetch(PDO::FETCH_ASSOC);

$sellingid = isset($_GET["id"]) ? $_GET["id"] : null;

$sqld = $db->prepare("SELECT * FROM selling where id = ? ");
$sqld->execute([$sellingid]);
$sellings = $sqld->fetch(PDO::FETCH_ASSOC);
$jsonData = $sellings["products"];
$productsArray = json_decode($jsonData, true);

echo count($productsArray) . "nnnnnnnnuuuuuu";

?>

<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here
        echo "console.log('submit alse');";
        // Your database operations, form processing, etc.
        $musteri = isset($_POST['musteri']) ? $_POST['musteri'] : 0;
        $date = isset($_POST['hiddentarih']) ? $_POST['hiddentarih'] : null;
        $productname = isset($_POST['urunhizmet']) ? $_POST['urunhizmet'] : null;
       // var_dump($_POST);
        if (empty($musteri)) {
            $error = "<div class='alert alert-danger'>Müşteriler doldurulması zorunlu alanlardır </div>";

        } else if (empty($date)) {
            $error = "<div class='alert alert-danger'>tarih doldurulması zorunlu alanlardır </div>";
        } else if (empty($productname)) {
            $error = "<div class='alert alert-danger'>Ürün seçilmelisiniz  </div>";

        } else {
              var_dump($_POST);
            $productcode = isset($_POST['ordernumber_hidden']) ? $_POST['ordernumber_hidden'] : null;
            $musteri = isset($_POST['musteri']) ? $_POST['musteri'] : null;
            $paymentType = isset($_POST['paymentType']) ? $_POST['paymentType'] : null;
            $vadetarihi = isset($_POST['vadetarihi']) ? $_POST['vadetarihi'] : null;
            $tarih = isset($_POST['hiddentarih']) ? $_POST['hiddentarih'] : null;
            $durum = isset($_POST['durum']) ? $_POST['durum'] : "bb";
            $toplamtekliftutar = isset($_POST['hiddentoplamtekliftutar']) ? $_POST['hiddentoplamtekliftutar'] : 0;
            $gross = isset($_POST['hiddenbürüt']) ? $_POST['hiddenbürüt'] : 0;
            $toplamiskonto = isset($_POST['hiddentoplamiskonto']) ? $_POST['hiddentoplamiskonto'] : 0;
            $toplamkdv = isset($_POST['hiddentoplamkdv']) ? $_POST['hiddentoplamkdv'] : 0;
            echo $productcode;
            // Define an array to store products
            $products = [];

            // Iterate through products
            foreach ($_POST['urunhizmet'] as $key => $value) {

                // Retrieve product details from POST data
                /*   $productname = $_POST['urunhizmet' . $index];
                  $miktar = $_POST['miktar' . $index];
                  $iskonto = isset($_POST['iskonto' . $index]) ? $_POST['iskonto' . $index] : null;
                  $kdv = isset($_POST['kdv' . $index]) ? $_POST['kdv' . $index] : null;
                  $birim = isset($_POST['hiddenbirim' . $index]) ? $_POST['hiddenbirim' . $index] : null;
                  $birimfiyat = isset($_POST['hiddenbirimfiyat' . $index]) ? $_POST['hiddenbirimfiyat' . $index] : null;
                  $urunfiyat = isset($_POST['hiddenfiyat' . $index]) ? $_POST['hiddenfiyat' . $index] : null;
   */
                // Create an array for the current product
                $currentProduct = [
                    'productname' => $_POST['urunhizmet'][$key],
                    'iskonto' => $_POST['iskonto'][$key],
                    'miktar' => $_POST['miktar'][$key],
                    'kdv' => $_POST['kdv'][$key],
                    'birim' => $_POST['hiddenbirim'][$key],
                    'birimfiyat' => $_POST['hiddenbirimfiyat'][$key],
                    'urunfiyat' => $_POST['hiddenurunfiyat'][$key]
                ];

                // Push the current product to the products array
                $products[] = $currentProduct;
            }

            // Include the database connection file

            // Convert the products array to JSON
            $productsJson = json_encode($products);

            // Prepare and execute the SQL statement
            $sql = $db->prepare("UPDATE `selling` SET `productcode`=? ,`costomer`=?,`paymentType`=?,`products`=?,`date-added`=?,`status`=?,`totalPrice`=?,`totalGrossPrice`=?,`totaliskonto`=?,`totalkdv`=?,`vadetarihi`=?  WHERE id=?");

            $sql->execute([$productcode, $musteri, $paymentType, $productsJson, $tarih, $durum, $toplamtekliftutar, $gross, $toplamiskonto, $toplamkdv,  $vadetarihi, $sellingid]);

            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: satislar.php");
                // Make sure to exit after header to prevent further code execution
            } else {
                $error = "<div class='alert alert-danger'>An error occurred while editing data.</div>";
                //   echo json_encode(['error' => $error]);
                return;
            }
        }


        // If everything is successful, redirect to satislar.php

    }

} catch (Exception $e) {
    // Log the exception details
    error_log("Caught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());

    // Optionally, display a generic error message to the user
    $error = "$e";
    echo json_encode(['error' => $error]);
    return;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineLogic-satışlar </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="css\app.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">


</head>

<!-- navbar css-->
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    a,
    a:hover {
        text-decoration: none;
        color: black;
        cursor: pointer;
    }

    body {
        background-color: white;
    }

    ul {
        list-style: none;

    }

    .navbar {
        width: 84%;
        position: fixed;
        top: 0;
        z-index: 1;
    }

    .sidebar-menu .menu .sidebar-item a {
        font-size: 17px;
    }

    /* a:hover{


} */
</style>

<body>




<div class=" d-flex justify-content-end ">
        <nav class="navbar d-flex justify-content-end  p-2  w-100 pe-5 bg-black bg-opacity-75">


            <div class=" align-items-center d-flex justify-content-between">
                <div class="text-white d-flex align-items-center gap-2" style="position:absolute; left:250px">
                    <p class="m-0"> Deneme sürenizin bitmesine
                        <?php echo $visitcount ?> gün kaldı
                    </p> <a href="#" class="border rounded-circle border-3 p-2 bg-white"><i
                            class="fa-solid fa-gift fs-4"></i></a>
                </div>
                <div class=" ">
                    <a href="#" class="border bg-white border-2 rounded-pill p-2"><i
                            class="fa-brands fa-rocketchat"></i> Canlı Destek </a>
                </div>
                <ul class="d-flex gap-3 m-0 justify-content-center align-items-center ">





                    <li><a href="#" class="text-white">Yardım</a></li>
                    <li><a href="logout.php" class="text-white">Çıkış</a></li>

                    <li class="d-flex align-items-center gap-2">
                        <div
                            class=" bg-secondary text-white border border-2 p-2 rounded-circle shadow justify-content-center align-items-center">
                            <i class="fa-solid fa-user "></i>

                        </div>
                        <div class="text-white fs-5">
                            <?php

                            echo $_SESSION["username"];
                            ?>
                        </div>


                    </li>






                </ul>
            </div>


        </nav>
    </div>
    <div id="sidebar">
    <div class="sidebar-wrapper  active shadow " style="height: 100vh; width: 200px;">
    <?php
    
    try {
        require "sidebar.php";
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    ?>
    </div>

    </div>
    <div class=" d-flex align-items-center w-50 justify-content-center mt-5 ms-5">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5">Sipariş <i
                class="fa-solid fa-arrow-right"></i> Yenile</p>
    </div>

    <div>
        <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row  d-flex justify-content-end align-items-center ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <?php echo $tt = empty($error) ? "" : $error;

                    ?>
                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-5 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <div class="d-flex align-items-center gap-5 w-75">
                        <i class="fa-solid fa-note-sticky fs-5 "></i>
                        <label for="bbb" class="form-label w-50  "> Sıpariş Numarası</label>
                        <input type="text" id="ordernumber" disabled name="ordernumber" class="form-control w-100"
                            value="<?php echo $sellings["productcode"] ?>">
                        <input type="hidden" id="ordernumber_hidden" name="ordernumber_hidden"
                            value="<?php echo $sellings["productcode"] ?>">

                    </div>
                    <div class="bottons">
                        <a href="satislar.php" class="btn btn-secondary">Vazgeç</a>
                        <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75">
                            Düzenle</button>

                    </div>
                </div>


            </div>
            <hr>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="costomer" class="form-label w-25  me-5 pe-4 ps-4">Müsteri </label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->

                            <select class="form-select ms-5" name="musteri" id="musteri">
                                <option selected value="">Muşteri seç..</option>
                                <?php

                                $sql = $db->prepare("select * from customers where userId=? ");
                                $sql->execute([$userId["id"]]);
                                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <option value="<?php echo $row["id"]; ?>" <?php echo $ex = $sellings["costomer"] == $row["id"] ? "selected" : "" ?>>
                                        <?php echo $row["name"]; ?>
                                    </option>

                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                </div>


            </div>

            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-solid fa-calendar-days fs-5 me-2"></i>
                            <label for="tarih" class="form-label w-25 me-5 ps-3">Düzenleme Tarihi </label>
                            <input type="text" disabled name="tarih" id="tarih" class="form-control w-100 ms-5 ps-5"
                                value="<?php echo $sellings["date-added"] ?>">
                            <input type="hidden" name="hiddentarih" id="tarih"
                                value="<?php echo $sellings["date-added"] ?>">

                        </div>

                    </div>

                </div>


            </div>

            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-solid fa-bell fs-5"></i>
                            <label class="form-label w-25  me-5 pe-4 ps-4">Ödeme Şekli </label>
                            <select class="form-select ms-5" name="paymentType" id="paymentType"
                                aria-label="Default select example">
                                <option value="1" <?php echo $ex = $sellings["paymentType"] == "1" ? "selected" : "" ?>>
                                    Nakit</option>
                                <option value="2" <?php echo $ex = $sellings["paymentType"] == "2" ? "selected" : "" ?>>
                                    Havale </option>
                                <option value="3" <?php echo $ex = $sellings["paymentType"] == "3" ? "selected" : "" ?>>
                                    Çek
                                </option>
                            </select>

                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-calendar-xmark fs-5"></i>
                            <label class="form-label w-25  me-5 pe-4 ps-4">Son Ödeme Tarihi </label>
                            <input class="form-control ms-5" name="vadetarihi" id="vadetarihi"
                                value=" <?php echo $sellings["vadetarihi"] ?>">

                            </input>

                        </div>

                    </div>

                </div>


            </div>

            <hr class="h-auto">


            <div class="container mt-3 mb-5 me-1">
    <div class="row justify-content-center">
       
        <div class="col-lg-10 col-md-11 border p-3"id="addnewrow"><i class="fa-solid fa-asterisk fs-6 text-danger"></i>      <!--  if there is one row add one  -->

                 
                    <?php
$i = 1;
foreach ($productsArray as $row_product) {
    ?>
    <div class="row addnewrow ll mb-3">
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Hizmet / Ürün</label>
            <select class="form-select" onchange="productchanged(<?php echo $i; ?>)" name="urunhizmet[]" id="urunhizmet<?php echo $i; ?>">
                <option selected value=""></option>
                <?php
                require "db.php";
                $sql = $db->prepare("SELECT * FROM products WHERE userId = ?");
                $sql->execute([$userId["id"]]);
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <option value="<?php echo $row["id"]; ?>" <?php echo $row_product['productname'] == $row["id"] ? "selected" : "" ?>>
                        <?php echo $row["productname"]; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Birim</label>
            <div class="input-group">
                <input type="text" class="form-control" name="birim[]" id="birim<?php echo $i; ?>" disabled value="<?php echo $row_product["birim"]; ?>">
                <input type="hidden" name="hiddenbirim[]" id="hiddenbirim<?php echo $i; ?>" value="<?php echo $row_product["birim"]; ?>">
                <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
            </div>
        </div>
        <div class="col-lg-1 col-md-2 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Miktar</label>
            <input type="number" class="form-control" id="miktar<?php echo $i; ?>" onchange="productchanged(<?php echo $i; ?>)" name="miktar[]" value="<?php echo $row_product["miktar"] == "" ? "1" : $row_product["miktar"]; ?>">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">BR.fiyat</label>
            <input type="number" class="form-control" disabled id="birimfiyat<?php echo $i; ?>" name="birimfiyat[]" placeholder="0,00" value="<?php echo $row_product["birimfiyat"]; ?>">
            <input type="hidden" id="hiddenbirimfiyat<?php echo $i; ?>" name="hiddenbirimfiyat[]" value="<?php echo $row_product["birimfiyat"]; ?>">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Iskonto</label>
            <select class="form-select" name="iskonto[]" onchange="productchanged(<?php echo $i; ?>)" id="iskonto<?php echo $i; ?>">
                <option value="0" <?php echo $row_product["iskonto"] == "0" ? "selected" : "" ?>>%0</option>
                <option value="1" <?php echo $row_product["iskonto"] == "1" ? "selected" : "" ?>>%1</option>
                <option value="3" <?php echo $row_product["iskonto"] == "3" ? "selected" : "" ?>>%3</option>
                <option value="5" <?php echo $row_product["iskonto"] == "5" ? "selected" : "" ?>>%5</option>
                <option value="8" <?php echo $row_product["iskonto"] == "8" ? "selected" : "" ?>>%8</option>
                <option value="10" <?php echo $row_product["iskonto"] == "10" ? "selected" : "" ?>>%10</option>
                <option value="15" <?php echo $row_product["iskonto"] == "15" ? "selected" : "" ?>>%15</option>
                <option value="17" <?php echo $row_product["iskonto"] == "17" ? "selected" : "" ?>>%17</option>
                <option value="20" <?php echo $row_product["iskonto"] == "20" ? "selected" : "" ?>>%20</option>
                <option value="25" <?php echo $row_product["iskonto"] == "25" ? "selected" : "" ?>>%25</option>
                <option value="28" <?php echo $row_product["iskonto"] == "28" ? "selected" : "" ?>>%28</option>
                <option value="30" <?php echo $row_product["iskonto"] == "30" ? "selected" : "" ?>>%30</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Vergi(KDV)</label>
            <select class="form-select" name="kdv[]" onchange="productchanged(<?php echo $i; ?>)" id="kdv<?php echo $i; ?>">
                <option value="20" <?php echo $row_product["kdv"] == "20" ? "selected" : "" ?>>%20</option>
                <option value="18" <?php echo $row_product["kdv"] == "18" ? "selected" : "" ?>>%18</option>
                <option value="10" <?php echo $row_product["kdv"] == "10" ? "selected" : "" ?>>%10</option>
                <option value="8" <?php echo $row_product["kdv"] == "8" ? "selected" : "" ?>>%8</option>
                <option value="1" <?php echo $row_product["kdv"] == "1" ? "selected" : "" ?>>%1</option>
                <option value="0" <?php echo $row_product["kdv"] == "0" ? "selected" : "" ?>>0</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Fiyat</label>
            <div class="input-group">
                <input type="hidden" class="form-control" id="hiddenfiyat<?php echo $i; ?>" name="hiddenurunfiyat[]" placeholder="0,00" value="<?php echo $row_product["urunfiyat"]; ?>">
                <input type="number" class="form-control" disabled id="fiyat<?php echo $i; ?>" name="urunfiyat[]" placeholder="0,00" value="<?php echo $row_product["urunfiyat"]; ?>">
                <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
            </div>
        </div>
    </div>
    <?php
    $i++;
}
?>
 </div>
                </div>
            </div>

            <hr class="h-auto w-100">

            <div class="row  d-flex justify-content-center align-items-center mt-3  ">
                <div class="col-md-3  d-flex justify-content-start align-items-center w-75   ps-5 ms-4  gap-3">

                    <div class="d-grid align-items-center justify-content-center w-100 pt-3">

                        <label class="form-label pe-5 me-5 w-100 "> teklif durumu </label>
                        <div class="input-group mb-3">

                            <select class="form-select " name="durum" id="durum" style="width:190px;">
                                <option value="waiting" <?php echo $tv = $sellings["status"] == "waiting" ? "selected" : "" ?>>Onay bekliyor</option>

                                <option value="true" <?php echo $tv = $sellings["status"] == "true" ? "selected" : "" ?>>
                                 fatura oluşturuldu </option>
                                <option value="false" <?php echo $tv = $sellings["status"] == "false" ? "selected" : "" ?>>fatura oluşturulmadı
                                </option>


                            </select>
                        </div>

                    </div>

                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control " name="toplamtekliftutar" disabled
                                id="toplamtutar"
                                value="<?php echo $tv = $sellings["totalPrice"] == null ? "0000" : $sellings["totalPrice"] ?>">
                            <input type="hidden"
                                value="<?php echo $tv = $sellings["totalPrice"] == null ? "0000" : $sellings["totalPrice"] ?>"
                                name="hiddentoplamtekliftutar" id="hiddentoplamtutar">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> BÜRÜT TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control "
                                value="<?php echo $tv = $sellings["totalGrossPrice"] == 0 ? 00 : $sellings["totalGrossPrice"] ?>"
                                name="bürüt" disabled id="bürüt" placeholder="0,00">
                            <input type="hidden"
                                value="<?php echo $tv = $sellings["totalGrossPrice"] == 0 ? 00 : $sellings["totalGrossPrice"] ?>"
                                name="hiddenbürüt" id="hiddenbürüt">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM ISKONTO </label>
                        <div class="input-group">
                            <input type="number" class="form-control "
                                value="<?php echo $tv = $sellings["totaliskonto"] == null ? "0000" : $sellings["totaliskonto"] ?>"
                                name="toplamiskonto" disabled id="toplamiskonto">
                            <input type="hidden"
                                value="<?php echo $tv = $sellings["totaliskonto"] == null ? "0000" : $sellings["totaliskonto"] ?>"
                                name="hiddentoplamiskonto" id="hiddentoplamiskonto">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM KDV </label>
                        <div class="input-group">
                            <input type="number" class="form-control "
                                value="<?php echo $tv = $sellings["totalkdv"] == null ? "0000" : $sellings["totalkdv"] ?>"
                                name="toplamkdv" disabled id="toplamkdv">
                            <input type="hidden"
                                value="<?php echo $tv = $sellings["totalkdv"] == null ? "0000" : $sellings["totalkdv"] ?>"
                                name="hiddentoplamkdv" id="hiddentoplamkdv">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 pt-4 mt-1">
                        <button class="btn btn-outline-secondary text-nowrap" type="button" id="addRowButton"
                            onclick="loadDoc()">Yeni satır
                            ekle</button>
                    </div>

                </div>


            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script>

  
        $(document).ready(function () {
            if (localStorage.getItem("startdate")) {
                if (localStorage.getItem("startdate") != "24-01-19") {
                    console.log("girdi");
                }
            } else {
                localStorage.setItem("startdate", "24-01-19");
            }
            console.log(localStorage.getItem("startdate"));
        });


    </script>
    <script>

        $(document).ready(function () {
            /* Turkish initialisation for the jQuery UI date picker plugin. */
            /* Written by Izzet Emre Erkan (kara@karalamalar.net). */
            (function (factory) {
                "use strict";

                if (typeof define === "function" && define.amd) {

                    // AMD. Register as an anonymous module.
                    define(["../widgets/datepicker"], factory);
                } else {

                    // Browser globals
                    factory(jQuery.datepicker);
                }
            })(function (datepicker) {
                "use strict";

                datepicker.regional.tr = {
                    closeText: "kapat",
                    prevText: "geri",
                    nextText: "ileri",
                    currentText: "bugün",
                    monthNames: ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
                        "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
                    monthNamesShort: ["Oca", "Şub", "Mar", "Nis", "May", "Haz",
                        "Tem", "Ağu", "Eyl", "Eki", "Kas", "Ara"],
                    dayNames: ["Pazar", "Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi"],
                    dayNamesShort: ["Pz", "Pt", "Sa", "Ça", "Pe", "Cu", "Ct"],
                    dayNamesMin: ["Pz", "Pt", "Sa", "Ça", "Pe", "Cu", "Ct"],
                    weekHeader: "Hf",
                    dateFormat: "dd.mm.yy",
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ""
                };
                datepicker.setDefaults(datepicker.regional.tr);

                return datepicker.regional.tr;

            });
            $(function () {
                $("#tarih").
                    datepicker($.datepicker.regional["tr"]);
                    $("#vadetarihi").
                    datepicker($.datepicker.regional["tr"]);
            });
        }) 
    </script>
    <script>
        var dycnum = <?php echo count($productsArray); ?>;

        function productchanged(indexl) {
         /*    var urunhizmetValue = document.getElementsByName("urunhizmet[]")[indexl-1].value;
            console.log(urunhizmetValue + "urunhizmetValue "); */

            var toplambirimfiyat = [];
            var toplamkdv = 0;
            var toplamiskonto = 0;
            var grossPrice = 0;

            console.log(dycnum + "-index number");
            var ajaxCalls = [];

            for (let index = 1; index <= dycnum; index++) {
                var productid = $("select[name='urunhizmet[]']").eq(index - 1).val();

                console.log(productid + "urunvalue-index");

                var ajaxCall = $.ajax({
                    type: "POST",
                    url: "get_price.php",
                    data: { productid: productid },
                    success: function (response) {
                        var dataarray = JSON.parse(response);
                        console.log(dataarray["price"] + "response");

                        // var numericPart = response.match(/\d+(\.\d+)?/);

                        if (dataarray) {
                            var price = dataarray["price"];
                            var alSatBirim = dataarray["alSatBirim"];
                            var miktarnumber = dataarray["miktar"];

                            var priceInput = $("#birimfiyat" + index);
                            var hiddenpriceInput = $("#hiddenbirimfiyat" + index);
                            var birimInput = $("#birim" + index);
                            var hiddenbirimInput = $("#hiddenbirim" + index);
                            var miktarInput = $("#miktar" + index);
                            
                            var toplamtutarbox = $("#toplamtutar");
                            var toplamtutarbox = $("#hiddentoplamtutar");
                            // miktar 0 ise 1 yap yoksa kend,isini yansıt kı hesaplama yanlış olmasın 
                            
                           console.log(miktarnumber+"miktarnumber-------------------");
                            birimInput.val(alSatBirim);
                            hiddenbirimInput.val(alSatBirim);
                            priceInput.val(price);
                            hiddenpriceInput.val(price);
                            var miktar = parseFloat(miktarInput.val());
                            var changeprice = parseFloat(miktar);
                            if (changeprice > 0) {
                                price = price * changeprice;

                            }else{
                                miktarInput.val(miktarnumber);
                            }

                            grossPrice += parseFloat(price);
                            var kdv = $("#kdv" + index).val();
                            var totalpricewithkdv = (price * (1 + kdv / 100)).toFixed(2);

                            toplamkdv += (totalpricewithkdv - price);

                            if ($("#iskonto" + index).val() !== '0') {
                                var iskonto = $("#iskonto" + index).val();
                                var totaliskonto = (price * iskonto / 100);
                                var totaliskontopricce = (price - totaliskonto);
                                var ist = price - totaliskontopricce;
                                toplamiskonto += parseFloat(ist.toFixed(2));
                                totalpricewithkdv = (totaliskontopricce * (1 + kdv / 100)).toFixed(2);
                            }

                            $("#fiyat" + index).val(totalpricewithkdv);
                            $("#hiddenfiyat" + index).val(totalpricewithkdv);
                            toplambirimfiyat[index] = parseFloat(totalpricewithkdv);
                        }
                    }
                });

                ajaxCalls.push(ajaxCall);
            }

            $.when.apply($, ajaxCalls).then(function () {
                var totalAmount = toplambirimfiyat.reduce((acc, value) => acc + value, 0);
                $('#toplamtutar').val(totalAmount.toFixed(2));
                $('#hiddentoplamtutar').val(totalAmount.toFixed(2));
                $('#toplamkdv').val(toplamkdv.toFixed(2));
                $('#hiddentoplamkdv').val(toplamkdv.toFixed(2));
                $('#toplamiskonto').val(toplamiskonto.toFixed(2));
                $('#hiddentoplamiskonto').val(toplamiskonto.toFixed(2));
                $('#bürüt').val(grossPrice.toFixed(2));
                $('#hiddenbürüt').val(grossPrice.toFixed(2));

                console.log(totalAmount + "toplamfiyatoooooooooooooo");
                console.log(toplamkdv + "totalTaxAmount");
                console.log(toplamiskonto + "discount total");
            });
        }




        // second function 
        function deleterow(index) {

            $(`.dive${index}`).remove();
            // calculateTotal();

        }
        //3th function
       /*  function loadDoc() {

            dycnum++;
            console.log(dycnum + "dynmuneber ");
            var newContent = document.createElement("div");
            newContent.className = "d-flex justify-content-center gap-5 ms-5 dive" + dycnum + " ";
            newContent.innerHTML =
                ` 
                <div class="col-md-1 me-5">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5 text-nowrap">Hizmet /Ürün</label>
                <select class="form-select" onchange="productchanged(`+ dycnum + `)" name="urunhizmet[]" id="urunhizmet` + dycnum + `">
                    <option selected value=""></option>
                    <?php
                    require "db.php";
                    $sql = $db->prepare("select * from products WHERE userId=?");
                    $sql->execute([$userId["id"]]);
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                                    <option value="<?php echo $row["id"]; ?>">
                                                        <?php echo $row["productname"]; ?>
                                                    </option>
                                                <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Birim</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="birim[]" id="birim` + dycnum + `" disabled>
                    <input type="hidden" name="birim[]" id="hiddenbirim` + dycnum + `" >
                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Miktar</label>
                <input type="number" class="form-control" id="miktar`+ dycnum + `" onchange="productchanged(` + dycnum + `)" name="miktar[]" placeholder="0,00">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">BR.fiyat</label>
                <input type="number" class="form-control" disabled id="birimfiyat`+ dycnum + `" name="birimfiyat` + dycnum + `" placeholder="0,00">
                
                <input type="hidden"   id="hiddenbirimfiyat`+ dycnum + `" name="hiddenbirimfiyat[]">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Iskonto</label>
                <select class="form-select" name="iskonto`+ dycnum + `" onchange="productchanged(` + dycnum + `)" id="iskonto[]">
                    <option selected value="0">%0</option>
                    <option value="1">%1</option>
                                    <option value="3">%3</option>
                                    <option value="5">%5</option>
                                    <option value="8">%8</option>
                                    <option value="10">%10</option>
                                    <option value="15">%15</option>
                                    <option value="17">%17</option>
                                    <option value="20">%20</option>
                                    <option value="25">%25</option>
                                    <option value="28">%28</option>
                                    <option value="30">%30</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Vergi</label>
                <div class="input-group">
                    <label class="input-group-text" for="kdv">KDV</label>
                    <select class="form-select" name="kdv[]" onchange="productchanged(` + dycnum + `)" id="kdv` + dycnum + `">
                        <option selected value="20">%20</option>
                        <option value="18">%18</option>
                        <option value="10">%10</option>
                        <option value="8">%8</option>
                        <option value="1">%1</option>
                        <option value="0">0</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Fiyat</label>
                <div class="input-group">
                    <input type="number" class="form-control" disabled id="fiyat`+ dycnum + `" name="urunfiyat[]" placeholder="0,00">
                    <input type="hidden"  id="hiddenfiyat`+ dycnum + `" name="hiddenurunfiyat[]` + dycnum + `" >
                    <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                </div>
            </div>
              <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">Fiyat</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" disabled id="fiyat`+ dycnum + `" name="urunfiyat[]"
                                            >
                                        <input type="hidden" class="form-control" id="hiddenfiyat`+ dycnum + `"  name="hiddenurunfiyat[]"
                                            >
                                        <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                                    </div>
                                </div>
        </div>
        
   <div class="col-md-1 d-flex justify-content-bottom align-items-end align-self-end " style="width: 50px;">
              <div>   <button class="btn btn-outline-secondary" type="button" id="delete" onclick="deleterow(`+ dycnum + `)"><i class="fa-solid fa-trash-can"></i></button>
          </div>   </div>
        `;

            // Append the new content instead of replacing the entire innerHTML
            document.getElementById("addnewrow").append(newContent);

            // Rebind event handlers for the new elements
            // $(document).on("change", ".addnewrow .product-row select.urunhizmet"+dycnum+"", productchanged(dycnum));
            // $(document).on("change", ".addnewrow .product-row input[type='number'].miktar"+dycnum+"", productchanged(dycnum));
            // $(document).on("change", ".addnewrow .product-row select.iskonto"+dycnum+"", productchanged(dycnum));
            // $(document).on("change", ".addnewrow .product-row select.kdv"+dycnum+"", productchanged(dycnum));



        }
 */

 function loadDoc() {
    dycnum = dycnum + 1; // Increment the row index
    console.log(dycnum + "dynmuneber ");

    // Create new div element for the row
    var newContent = document.createElement("div");
    newContent.className = "row addnewrow dive" + dycnum;

    // Construct the HTML for the new row
    newContent.innerHTML =
        `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Hizmet / Ürün</label>
            <select class="form-select" onchange="productchanged(${dycnum})" name="urunhizmet[]" id="urunhizmet${dycnum}">
                <option selected value=""></option>
                <?php
                // PHP code for generating options
                require "db.php";
                $sql = $db->prepare("select * from products where userId=?");
                $sql->execute([$userId["id"]]);
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <option value="<?php echo $row["id"]; ?>">
                        <?php echo $row["productname"]; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
      
         <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Birim</label>
            <div class="input-group">
                <input type="text" class="form-control" name="birim[]" id="birim${dycnum}" disabled >
                <input type="hidden" name="hiddenbirim[]" id="hiddenbirim${dycnum}">
                <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
            </div>
        </div>
        <div class="col-lg-1 col-md-2 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Miktar</label>
            <input type="number" class="form-control" id="miktar${dycnum}" onchange="productchanged(${dycnum})" name="miktar[]" placeholder="0,00">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">BR.fiyat</label>
            <input type="number" class="form-control" disabled id="birimfiyat${dycnum}" name="birimfiyat[]" placeholder="0,00">
            <input type="hidden" id="hiddenbirimfiyat${dycnum}" name="hiddenbirimfiyat[]">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Iskonto</label>
            <select class="form-select" name="iskonto[]" onchange="productchanged(${dycnum})" id="iskonto${dycnum}">
                <option selected value="0">%0</option>
                <option value="1">%1</option>
                <option value="3">%3</option>
                <option value="5">%5</option>
                <option value="8">%8</option>
                <option value="10">%10</option>
                <option value="15">%15</option>
                <option value="17">%17</option>
                <option value="20">%20</option>
                <option value="25">%25</option>
                <option value="28">%28</option>
                <option value="30">%30</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Vergi</label>
            <div class="input-group">
                <label class="input-group-text" for="kdv">KDV</label>
                <select class="form-select" name="kdv[]" onchange="productchanged(${dycnum})" id="kdv${dycnum}">
                    <option selected value="20">%20</option>
                    <option value="18">%18</option>
                    <option value="10">%10</option>
                    <option value="8">%8</option>
                    <option value="1">%1</option>
                    <option value="0">0</option>
                </select>
            </div>
        </div>
            <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Fiyat</label>
            <div class="input-group">
                    <input type="hidden" class="form-control"  id="hiddenfiyat${dycnum}" name="hiddenurunfiyat[]"
                    placeholder="0,00">
                        <input type="number" class="form-control" disabled id="fiyat${dycnum}" name="urunfiyat[]" placeholder="0,00">
                        <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                   
            </div>
        </div>
        <div class="col-lg-1 d-flex align-items-center">
            <button class="btn btn-outline-secondary" type="button" onclick="deleterow(${dycnum})"><i class="fa-solid fa-trash-can"></i></button>
        </div>
        `;

    // Append the new row content to the container
    document.getElementById("addnewrow").append(newContent);

    // Rebind event handlers for new elements if needed
    // Ensure any logic or calculations dependent on the added rows are updated here

}
        //calculateAllPrice function




    </script>







</body>


</html>