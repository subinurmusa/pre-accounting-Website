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

$sqluserid=$db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId=$sqluserid->fetch(PDO::FETCH_ASSOC);

$fisfaturaId=$_GET["id"];
$sql=$db->prepare("select * from  fisfaturagiderler where id = ? ");
$sql->execute([$_GET["id"]]);
$fisfaturalist=$sql->fetch(PDO::FETCH_ASSOC);

?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here

        // Your database operations, form processing, etc.
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
      
        //var_dump($_POST);
        if (empty($kayitIsmi) || empty($vendor) || empty($fis_fatura_number)) {
            $error = "<div class='alert alert-danger'> Kayıt ismi / tedarikçi / fiş fatura alanları gereklidir  Gereklidir</div>";

        } else {

            $products = [];
            
            // Iterate through products
            for ($index = 1; $index < 50; $index++) {
                if (!isset($_POST['urunhizmet' . $index])) {
                    break;
                }
              
    
                // Retrieve product details from POST data
                $productname = $_POST['urunhizmet' . $index];
                
             //   $miktar = isset($_POST['miktar' . $index]) && strval($_POST['miktar' . $index]) !== "" ? strval($_POST['miktar' . $index]) : "1";
        
     
        $iskonto = isset($_POST['iskonto' . $index]) ? $_POST['iskonto' . $index] : null;
        $miktar = isset($_POST['miktar' . $index]) ? $_POST['miktar' . $index] : 0;
        $kdv = isset($_POST['kdv' . $index]) ? $_POST['kdv' . $index] : null;
        $birim = isset($_POST['hiddenbirim' . $index]) ? $_POST['hiddenbirim' . $index] : null;
        $birimfiyat = isset($_POST['hiddenbirimfiyat' . $index]) ? $_POST['hiddenbirimfiyat' . $index] : null;
        $urunfiyat = isset($_POST['hiddenurunfiyat' . $index]) ? $_POST['hiddenurunfiyat' . $index] : null;
    
                // Create an array for the current product
                $currentProduct = [
                    'productname' => $productname,
                    'iskonto' => $iskonto,
                    'miktar' => $miktar,
                    'kdv' => $kdv,
                    'birim' => $birim,
                    'birimfiyat' => $birimfiyat,
                    'urunfiyat' => $urunfiyat
                ];
        
                // Push the current product to the products array
                $products[] = $currentProduct;
            }        
            // Include the database connection file
                
            // Convert the products array to JSON
            $productsJson = json_encode($products);
        
            // Prepare and execute the SQL statement
            $sql = $db->prepare("UPDATE `fisfaturagiderler` SET `titleName`=? ,`vendor`=?,`fisFaturaNum`=?,`currency`=?
            ,`status`=?,`dueDate`=?,`products`=?,`araToplam`=?,`toplamkdv`=?,
            `geneltoplam`=?,`fisFaturaDate`=?,`toplamIskonto`=? where id = ? and userId=?");
        
            $sql->execute([$kayitIsmi, $vendor, $fis_fatura_number, $currency, $odeme_durumu, $odenecek_tarih, $productsJson, $gross, $toplamkdv, $toplamtutar,$fis_fatura_tarihi,$toplamiskonto,$fisfaturaId,$userId["id"]]);
        
            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: giderler.php");
                 // Make sure to exit after header to prevent further code execution
            } else {
                $error = "<div class='alert alert-danger'>An error occurred while saving data.</div>";
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
    }

    body {
        background-color: white;
    }

    ul {
        list-style: none;

    }

    .navbar {
        width: 88%;
        position: fixed;
        top: 0;
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
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
            ?>
        </div>

    </div>

    <div class=" container d-flex align-items-center w-50 justify-content-start mt-5 ">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark">  Fiş / Fatura Düzenle</p>
    </div>
    <hr>
    <div class="">
       

            <div class="container ps-5 ms-5 ">
            <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row mt-3  d-flex justify-content-center align-items-center ps-5 ms-5">
                    
                       <div class="w-75 ps-5 ms-5">
                       <?php echo $error==""?"": $error; ?>
                       </div>
                    
                    </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-regular fa-user fs-5"></i>
                                <label for="kayitIsmi" class="form-label w-25 me-5 pe-4 ps-4">Kayıt İsmi </label>
                                <input class="form-control" type="text" id="kayitIsmi" value="<?php echo $fisfaturalist["titleName"] ?>"name="kayitIsmi">
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Require the database connection
                require "db.php";

                // Fetch the names of employees from the database
                $vendor = $db->prepare("SELECT `vendorName` FROM `vendors` where userId=?");
                $vendor->execute([$userId["id"]]);
                $vendorlist = $vendor->fetchAll(PDO::FETCH_COLUMN);
                ?>

                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-id-card fs-5"></i>
                                <label for="vendor" class="form-label w-25 me-5 pe-4 ps-4">Tedarikçi</label>
                                <select class="form-select" id="vendor" name="vendor">
                                    <option value="0">-----</option>
                                    <?php foreach ($vendorlist as $vendors): ?>
                                        <option value="<?php echo $vendors; ?>" <?php echo $fisfaturalist["vendor"]==$vendors? "selected" :"" ?>>
                                            <?php echo $vendors; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Salary related fields -->
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-calendar fs-5"></i>
                                <label for="fis_fatura_tarihi" class="form-label w-25 me-5 pe-4 ps-4">
                                FİŞ/FATURA TARİHİ</label>
                                <input class="form-control" type="text" id="fis_fatura_tarihi" name="fis_fatura_tarihi"
                                    value="<?php echo $fisfaturalist["fisFaturaDate"]?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-money-bill fs-5"></i>
                                <label for="fis_fatura_number" class="form-label w-25 me-5 pe-4 ps-4">FİŞ/FATURA NUMARASI</label>
                                <input class="form-control" type="number" id="fis_fatura_number" value="<?php echo  $fisfaturalist["fisFaturaNum"] ?>"name="fis_fatura_number"
                                    >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-hand-holding-usd fs-5"></i>
                                <!-- Changed icon to a money-related icon -->
                                <label for="currency" class="form-label w-25 me-5 pe-4 ps-4">FATURA DÖVİZİ</label>
                                <select class="form-select" id="currency" name="currency"
                                 >
                                    <option value="tl" <?php echo $fisfaturalist["currency"]=="tl"? "selected":"" ?>> <i class="fa-solid fa-turkish-lira-sign"></i> Türk Lirası</option>
                                    <option value="dolar" <?php echo $fisfaturalist["currency"]=="dolar"? "selected":"" ?>> <i class="fa-solid fa-dollar-sign"></i>ABD doları</option>
                                    <option value="euro" <?php echo $fisfaturalist["currency"]=="euro"? "selected":"" ?>><i class="fa-solid fa-euro-sign"></i> Euro</option>
                                    <option value="sterlin" <?php echo $fisfaturalist["currency"]=="sterlin"? "selected":"" ?>><i class="fa-solid fa-sterling-sign"></i> İngiliz sterlini</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-hand-holding-usd fs-5"></i>
                                <!-- Changed icon to a money-related icon -->
                                <label for="odeme_durumu" class="form-label w-25 me-5 pe-4 ps-4">Ödeme Durumu</label>
                                <select class="form-select" id="odeme_durumu" name="odeme_durumu"
                                    onchange="changeBackground()">
                                    <option value="0" <?php echo $fisfaturalist["status"]==0? "selected":"" ?>>Ödenecek</option>
                                    <option value="1" <?php echo $fisfaturalist["status"]==1? "selected":"" ?>>Ödendi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>





                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-calendar-check fs-5"></i>
                                <label for="odenecek_tarih" class="form-label w-25 me-5 pe-4 ps-4">
                                ÖDENECEĞİ TARİH</label>
                                <input class="form-control" type="text" id="odenecek_tarih" name="odenecek_tarih"
                                value="<?php echo $fisfaturalist["dueDate"]?>">
                            </div>
                        </div>
                    </div>
                </div>
                
            <hr class="h-auto">

<div class="row justify-content-end align-items-center mt-3" id="addnewrow">
<?php 
$productlist=json_decode($fisfaturalist["products"],true);
$i = 1;
foreach ($productlist as $row_product) {

    ?>
    
    <div class="col-md-12 d-flex justify-content-end gap-2 me-5">
        <div class="col-md-2">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Hizmet /Ürün</label>
                <select class="form-select" onchange="productchanged(1)" name="urunhizmet1" id="urunhizmet1">
                    
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
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Birim</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="birim1" id="birim1" value="<?php echo $row_product["birim"] ?>" disabled>
                    <input type="hidden" class="form-control" name="hiddenbirim1" value="<?php echo $row_product["birim"] ?>" id="hiddenbirim1" >
                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Miktar</label>
                <input type="number" class="form-control" id="miktar1" onchange="productchanged(1)"
                    name="miktar1"  value="<?php echo $row_product["miktar"] ?>">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">BR.fiyat</label>
                <input type="number" class="form-control" disabled  value="<?php echo $row_product["birimfiyat"] ?>" id="birimfiyat1" name="birimfiyat1"
                    placeholder="0,00">
                <input type="hidden" class="form-control"  value="<?php echo $row_product["birimfiyat"] ?>"  id="hiddenbirimfiyat1" name="hiddenbirimfiyat1"
                    placeholder="0,00">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Iskonto</label>
                <select class="form-select" name="iskonto1" onchange="productchanged(1)" id="iskonto1">
                    <option  value="0"  <?php echo $row_product["iskonto"]=="0"?"selected":""; ?>>%0</option>
                    <option value="1" <?php echo $row_product["iskonto"]=="1"?"selected":""; ?>>%1</option>
                    <option value="3" <?php echo $row_product["iskonto"]=="3"?"selected":""; ?>>%3</option>
                    <option value="5" <?php echo $row_product["iskonto"]=="5"?"selected":""; ?>>%5</option>
                    <option value="8" <?php echo $row_product["iskonto"]=="8"?"selected":""; ?>>%8</option>
                    <option value="10" <?php echo $row_product["iskonto"]=="10"?"selected":""; ?>>%10</option>
                    <option value="15" <?php echo $row_product["iskonto"]=="15"?"selected":""; ?>>%15</option>
                    <option value="17" <?php echo $row_product["iskonto"]=="17"?"selected":""; ?>>%17</option>
                    <option value="20" <?php echo $row_product["iskonto"]=="20"?"selected":""; ?>>%20</option>
                    <option value="25" <?php echo $row_product["iskonto"]=="25"?"selected":""; ?>>%25</option>
                    <option value="28" <?php echo $row_product["iskonto"]=="28"?"selected":""; ?>>%28</option>
                    <option value="30" <?php echo $row_product["iskonto"]=="30"?"selected":""; ?>>%30</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center ">
                <label class="form-label pe-2">KDV</label>
                <div class="input-group">
                    <label class="input-group-text me-2" for="kdv1">%</label>
                    <select class="form-select" name="kdv1" onchange="productchanged(1)" id="kdv1">
                        <option  value="20" <?php echo $row_product["kdv"]=="20"?"selected":""; ?>>20</option>
                        <option value="18" <?php echo $row_product["kdv"]=="18"?"selected":""; ?>>18</option>
                        <option value="10" <?php echo $row_product["kdv"]=="10"?"selected":""; ?>>10</option>
                        <option value="8" <?php echo $row_product["kdv"]=="8"?"selected":""; ?>>8</option>
                        <option value="1" <?php echo $row_product["kdv"]=="1"?"selected":""; ?>>1</option>
                        <option value="0" <?php echo $row_product["kdv"]=="0"?"selected":""; ?>>0</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Fiyat</label>
                <div class="input-group">
                    <input type="number" class="form-control" disabled id="fiyat1" name="urunfiyat1"
                        placeholder="0,00" value="<?php echo $row_product["urunfiyat"]; ?>">
                    <input type="hidden" class="form-control"  value="<?php echo $row_product["urunfiyat"]; ?>" id="hiddenfiyat1" name="hiddenurunfiyat1"
                        placeholder="0,00">
                    <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-1 align-self-center ">
            <div class="m-1  pt-4 ">
                <button class="btn btn-outline-secondary " type="button" id="addRowButton" onclick="loadDoc()"><i class="fa-solid fa-plus"></i></button>
            </div>
        </div>
    </div>
    
    <?php 
    $i++;
}

?>
 
</div>


                <div class="row d-flex justify-content-end align-items-center mt-3">
                <div class="col-md-9 d-flex align-items-center me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                        <div class="bottons">
                            <a href="giderler.php" class="btn btn-secondary">Vazgeç</a>
                            <button type="submit" name="submit" id="submit"
                                class="btn btn-primary opacity-75">Kaydet</button>
                        </div>
                    </div>
                </div>
            </div>
         

            <div class="row d-grid justify-content-center w-50 align-items-center ms-5 ps-5 mt-3">
            <hr>
            <div class="col-md-4">
                
            <div class="mb-3 d-flex justify-content-start align-items-center">
                <label class="form-label text-nowrap mb-0">ARA TOPLAM : </label>
                <input type="text" disabled value="<?php echo $fisfaturalist["araToplam"]; ?>" id="gross" class="border-0">
                <input type="text" hidden value="<?php echo $fisfaturalist["araToplam"]; ?>" id="gross_hidden" name="gross"class="border-0">
              
            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0">TOPLAM KDV : </label>
                <input type="text" value="<?php echo $fisfaturalist["toplamkdv"]; ?>" disabled id="toplamkdv"  class="border-0">
                <input type="text" value="<?php echo $fisfaturalist["toplamkdv"]; ?>" hidden id="toplamkdv_hidden" name="toplamkdv" class="border-0">
            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0">GENEL TOPLAM : </label>
                <input type="text" disabled id="toplamtutar"class="border-0" value="<?php echo $fisfaturalist["geneltoplam"]; ?>">
                <input type="text" hidden id="toplamtutar_hidden" value="<?php echo $fisfaturalist["geneltoplam"]; ?>" name="toplamtutar" class="border-0">

            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0"> TOPLAM Iskonto: </label>
                <input type="text" disabled id="toplamiskonto"class="border-0"  value="<?php echo $fisfaturalist["geneltoplam"]; ?>">
                <input type="text" hidden id="hiddentoplamiskonto" name="toplamiskonto"  value="<?php echo $fisfaturalist["geneltoplam"]; ?>" class="border-0">

            </div>
        </div>
        <hr>
    </div>
    </form>
            </div>            
            </div>


<div class="w-50">

</div>
        



     
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script>






        $(document).ready(function () {
            changeBackground();
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
        function changeBackground() {
            var select = document.getElementById("odeme_durumu");
            var option = select.options[select.selectedIndex];
            var dropdown = select;

            if (option.value === "0") {
                dropdown.style.backgroundColor = "#e6e8eb"; // Light gray background
            } else if (option.value === "1") {
                dropdown.style.backgroundColor = "#d4edda"; // Light green background
            }
        }
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
                $("#fis_fatura_tarihi").datepicker($.datepicker.regional["tr"]);
                $("#odenecek_tarih").datepicker($.datepicker.regional["tr"]);

            });
        }) 
    </script>
  <script>
        var dycnum = 1;

        function productchanged(indexl) {
            var toplambirimfiyat = [];
            var toplamkdv = 0;
            var toplamiskonto = 0;
            var grossPrice = 0;

            console.log(dycnum + "-index number");
            var ajaxCalls = [];

            for (let index = 1; index <= dycnum; index++) {
                var productid = $("#urunhizmet" + index + " option:selected").val();
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
                $('#toplamtutar_hidden').val(totalAmount.toFixed(2));
                $('#toplamkdv').val(toplamkdv.toFixed(2));
                $('#toplamkdv_hidden').val(toplamkdv.toFixed(2));
                $('#toplamiskonto').val(toplamiskonto.toFixed(2));
                $('#hiddentoplamiskonto').val(toplamiskonto.toFixed(2));
                $('#gross').val(grossPrice.toFixed(2));
                $('#gross_hidden').val(grossPrice.toFixed(2));

                console.log(totalAmount + "toplamfiyatoooooooooooooo");
                console.log(toplamkdv + "totalTaxAmount");
                console.log(toplamiskonto + "discount total");
            });
        }




        // second function 
        function deleterow(index) {

            $(`.dive${index}`).remove();
          //  calculateTotal();
          dycnum = dycnum - 1;
          productchanged(0);

        }
        //3th function
        function loadDoc() {
    dycnum = dycnum + 1;
    console.log(dycnum + "dynmuneber ");
    var newContent = document.createElement("div");
    newContent.className = "col-md-12 d-flex justify-content-end gap-2 me-5 dive"+dycnum;
    newContent.id = "addnewrow";
    newContent.innerHTML =
        `
        
            <div class="col-md-2">
                <div class="d-grid align-items-center">
                    <label class="form-label pe-2">Hizmet /Ürün</label>
                    <select class="form-select" onchange="productchanged(` + dycnum + `)" name="urunhizmet` + dycnum + `" id="urunhizmet` + dycnum + `">
                        <option selected value=""></option>
                        <?php
                        require "db.php";
                        $sql = $db->prepare("select * from products where userId=? ");
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
                    <label class="form-label pe-2">Birim</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="birim` + dycnum + `" id="birim` + dycnum + `" disabled>
                        <input type="hidden" class="form-control" name="hiddenbirim` + dycnum + `" id="hiddenbirim` + dycnum + `" >
                        <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-grid align-items-center">
                    <label class="form-label pe-2">Miktar</label>
                    <input type="number" class="form-control" id="miktar` + dycnum + `" onchange="productchanged(` + dycnum + `)" name="miktar` + dycnum + `" placeholder="0,00">
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-grid align-items-center">
                    <label class="form-label pe-2">BR.fiyat</label>
                    <input type="number" class="form-control" disabled id="birimfiyat` + dycnum + `" name="birimfiyat` + dycnum + `" placeholder="0,00">
                    <input type="hidden" class="form-control"  id="hiddenbirimfiyat` + dycnum + `" name="hiddenbirimfiyat` + dycnum + `" placeholder="0,00">
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-grid align-items-center">
                    <label class="form-label pe-2">Iskonto</label>
                    <select class="form-select" name="iskonto` + dycnum + `" onchange="productchanged(` + dycnum + `)" id="iskonto` + dycnum + `">
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
                    <label class="form-label pe-2">KDV</label>
                    <div class="input-group">
                        <label class="input-group-text me-2" for="kdv` + dycnum + `">%</label>
                        <select class="form-select" name="kdv` + dycnum + `" onchange="productchanged(` + dycnum + `)" id="kdv` + dycnum + `">
                            <option selected value="20">20</option>
                            <option value="18">18</option>
                            <option value="10">10</option>
                            <option value="8">8</option>
                            <option value="1">1</option>
                            <option value="0">0</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-grid align-items-center">
                    <label class="form-label pe-2">Fiyat</label>
                    <div class="input-group">
                        <input type="number" class="form-control" disabled id="fiyat` + dycnum + `" name="urunfiyat` + dycnum + `" placeholder="0,00">
                        <input type="hidden" class="form-control"  id="hiddenfiyat` + dycnum + `" name="hiddenurunfiyat` + dycnum + `" placeholder="0,00">
                        <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-1 align-self-center ">
                <div class="m-1  pt-4 ">
                    <button class="btn btn-outline-secondary " type="button" id="delete" onclick="deleterow(` + dycnum + `)"><i class="fa-solid fa-minus"></i></button>
                </div>
            </div>
        
        `;

    // Append the new content instead of replacing the entire innerHTML
    document.getElementById("addnewrow").appendChild(newContent);
}


      



    </script>


</body>


</html>