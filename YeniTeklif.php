<?php
// require "db.php";
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
$num = 0;
$error="kookokok";
ini_set('display_errors', 1);
error_reporting(E_ALL);


?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here
        echo "console.log('submit alse');";
        // Your database operations, form processing, etc.
        $musteri = isset($_POST['musteri']) ? $_POST['musteri'] : null;
        $date = isset($_POST['tarih']) ? $_POST['tarih'] : null;
        $productname = isset($_POST['urunhizmet1']) ? $_POST['urunhizmet1'] : null;
    
        if (empty($musteri)) {
            $error = "<div class='alert alert-danger'>Müşteriler doldurulması zorunlu alanlardır </div>";
      
        }
        else if(empty($date)){
            $error = "<div class='alert alert-danger'>tarih doldurulması zorunlu alanlardır </div>";
        }
        else if (empty($productname)){
            $error = "<div class='alert alert-danger'>Ürün seçilmelisiniz  </div>";
     
        }else{
          //  var_dump($_POST);
            $productcode = isset($_POST['ordernumber_hidden']) ? $_POST['ordernumber_hidden'] : null;
            $musteri = isset($_POST['musteri']) ? $_POST['musteri'] : null;
            $vadetarih = isset($_POST['vadetarih']) ? $_POST['vadetarih'] : null;
            $tarih = isset($_POST['tarih']) ? $_POST['tarih'] : null;
            $durum = isset($_POST['durum']) ? $_POST['durum'] : "bb";
            $toplamtekliftutar = isset($_POST['hiddentoplamtekliftutar']) ? $_POST['hiddentoplamtekliftutar'] : 0;
            $gross = isset($_POST['hiddenbürüt']) ? $_POST['hiddenbürüt'] : 0;
            $toplamiskonto = isset($_POST['hiddentoplamiskonto']) ? $_POST['hiddentoplamiskonto'] : 0;
            $toplamkdv = isset($_POST['hiddentoplamkdv']) ? $_POST['hiddentoplamkdv'] : 0;
            echo $productcode;
            // Define an array to store products
            $products = [];
            
            // Iterate through products
            for ($index = 1; $index < 50; $index++) {
                if (!isset($_POST['urunhizmet' . $index])) {
                    break;
                }
              
    
                // Retrieve product details from POST data
                $productname = $_POST['urunhizmet' . $index];
        $miktar = $_POST['miktar' . $index];
        $iskonto = isset($_POST['iskonto' . $index]) ? $_POST['iskonto' . $index] : null;
        $kdv = isset($_POST['kdv' . $index]) ? $_POST['kdv' . $index] : null;
        $birim = isset($_POST['hiddenbirim' . $index]) ? $_POST['hiddenbirim' . $index] : null;
        $birimfiyat = isset($_POST['hiddenbirimfiyat' . $index]) ? $_POST['hiddenbirimfiyat' . $index] : null;
        $urunfiyat = isset($_POST['hiddenfiyat' . $index]) ? $_POST['hiddenfiyat' . $index] : null;
    
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
            require "db.php";
        
            // Convert the products array to JSON
            $productsJson = json_encode($products);
        
            // Prepare and execute the SQL statement
            $sql = $db->prepare("INSERT INTO `selling`(`productcode`, `costomer`, `paymentType`, `products`, `date-added`, `satus`, `totalPrice`, `totalGrossPrice`, `totaliskonto`, `totalkdv`) 
                VALUES (?,?,?,?,?,?,?,?,?,?)");
        
            $sql->execute([$productcode, $musteri, $vadetarih, $productsJson, $tarih, $durum, $toplamtekliftutar, $gross, $toplamiskonto, $toplamkdv]);
        
            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: satislar.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
  

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
        width: 84%;
    }

    .sidebar-menu .menu .sidebar-item a {
        font-size: 17px;
    }

    /* a:hover{


} */
</style>

<body>

    <div id="sidebar">
        <div class="sidebar-wrapper  active shadow " style="width: 204px;">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo"> <!--insert  fake logo  -->
                        <a href="#" class="text-primary"> FineLogic </a>
                    </div>


                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu px-2">

                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item  justify-content-center">
                        <a href="dashbaordPage.php"
                            class="btn btn-toggle align-items-center text-nowrap rounded d-flex gap-3  d-flex justify-content-start  ">
                            <i class="fa-solid fa-house-user text-primary"></i> Ana Sayfa
                        </a>


                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a class="btn  btn-toggle align-items-center text-nowrap rounded d-flex gap-3  d-flex justify-content-start   "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse2">
                            <i class="fa-solid fa-arrow-down-short-wide text-primary"></i> Satışlar
                        </a>
                        <div class="collapse " id="home-collapse2">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="satislar.php"
                                        class="link-primary text-nowrap text-secondary  p-2 rounded ">Satışlar</a>
                                </li>
                                <li><a href="#"
                                        class="link-primary  text-nowrap text-secondary  p-2 rounded ">Faturalar</a>
                                </li>
                                <li><a href="#"
                                        class="link-primary text-nowrap text-secondary  p-2 rounded ">Muşteriler</a>
                                </li>
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">Satış
                                        Raporu</a>
                                </li>
                                <li><a href="#"
                                        class="link-primary text-nowrap text-secondary  p-2 rounded ">Tahsilatlar
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">Gelir
                                        Gider
                                        Raporu</a></li>



                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center text-nowrap rounded d-flex gap-3  d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse3">
                            <i class="fa-solid fa-arrow-up-from-bracket text-primary"></i> Giderler
                        </a>
                        <div class="collapse  " id="home-collapse3">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">Gider
                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>                                        Listesi</a>
                                </li>
                                <li><a href="#"

                                class="link-primary text-nowrap text-secondary  p-2 rounded ">Tedarikçiler</a>
                                </li>
                                <li><a href="#"
                                        class="link-primary text-nowrap text-secondary  p-2 rounded ">Çalışanlar</a>
                                </li>
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">Giderler
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">Ödemeler
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-nowrap text-secondary  p-2 rounded ">KDV
                                        raporu</a>
                                </li>


                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle text-nowrap align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse-subi">
                            <i class="fa-regular fa-money-bill-1 text-primary"></i> Nakit
                        </a>
                        <div class="collapse  " id="home-collapse-subi">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary  text-nowrap  p-2 rounded ">Kasa Ve
                                        Bankalar</a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Çekler</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Kasa/Banka
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap p-2 rounded ">Nakit Akışı
                                        Raporu</a></li>

                            </ul>
                        </div>

                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded d-flex gap-3  d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapses">
                            <i class="fa-solid fa-cubes text-primary"></i> Stok
                        </a>
                        <div class="collapse  " id="home-collapses">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Hizmet ve
                                        ürünler</a></li>
                                <li><a href="#"
                                        class="link-primary text-nowrap text-secondary  p-2 rounded ">Depolar</a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded">
                                        Dolaplar Arası Transfer </a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Giden
                                        İrsaliyeler</a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Gelen
                                        İrsaliyeler</a></li>

                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded text-nowrap d-flex gap-3  d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse4">
                            <i class="fa-solid fa-gears text-primary"></i> Ayarlar
                        </a>
                        <div class="collapse  " id="home-collapse4">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Firma
                                        Bilgileri
                                    </a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Kategori
                                        Ve
                                        etiketler</a></li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Dolaplar
                                        Arası
                                        Transfer</a></li>
                                <li><a href="#"
                                        class="link-primary text-secondary text-nowrap  p-2 rounded ">Kullanicilar</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary text-nowrap  p-2 rounded ">Yazdırma
                                        Şablonları</a></li>

                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a href="logout.php"
                            class="btn btn-toggle align-items-center rounded d-flex gap-3  d-flex justify-content-start  ">
                            <i class="fa-solid fa-right-from-bracket text-primary"></i> Çıkış
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class=" d-flex justify-content-end ">
        <nav class="navbar d-flex justify-content-end  p-2  ps-5 pe-5 bg-secondary">


            <div class=" align-items-center d-flex justify-content-between">
                <div class="text-dark d-flex align-items-center gap-2" style="position:absolute; left:60px">
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

                            echo $_SESSION["name"];
                            ?>
                        </div>


                    </li>






                </ul>
            </div>


        </nav>
    </div>

    <div class="">
        <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row  d-flex justify-content-end align-items-center mt-5 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <?php echo $tt = empty($error) ? "" : $error  ;
                    
                    ?>
                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-5 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <div class="d-flex align-items-center gap-5 w-75">
                        <i class="fa-solid fa-note-sticky fs-5 "></i>
                        <label for="bbb" class="form-label w-50  "> Sıpariş Numarası</label>
                        <input type="text" id="ordernumber" disabled name="ordernumber" class="form-control w-100">
                        <input type="hidden" id="ordernumber_hidden"  name="ordernumber_hidden" >

                    </div>
                    <div class="bottons">
                        <a href="satislar.php" class="btn btn-secondary">Vazgeç</a>
                        <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75"> Kaydet</button>

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
                                require "db.php";
                                $sql = $db->prepare("select * from customers ");
                                $sql->execute();
                                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <option value="<?php echo $row["name"]; ?>">
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
                            <input type="text" name="tarih" id="tarih" class="form-control w-100 ms-5 ps-5">

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
                            <select class="form-select ms-5" name="vadetarih" id="vadetarih"
                                aria-label="Default select example">
                                <option selected>Nakit</option>
                                <option value="1">Havale </option>
                                <option value="2">Çek</option>
                            </select>

                        </div>

                    </div>

                </div>


            </div>

            <hr class="h-auto">


            <div class="container mt-3 mb-5 me-1">
                <div class="row addnewrow justify-content-center " id="addnewrow">
                    <div class="d-flex justify-content-center gap-5 me-5 ">
                        <div class="col-md-1 me-5">
                            <div class="d-grid align-items-center">
                                <label class="form-label pe-5 me-5 text-nowrap">Hizmet /Ürün</label>
                                <select class="form-select" onchange="productchanged(1)" name="urunhizmet1"
                                    id="urunhizmet1">
                                    <option selected value=""></option>
                                    <?php
                                    require "db.php";
                                    $sql = $db->prepare("select * from products ");
                                    $sql->execute();
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
                                    <input type="text" class="form-control" name="birim1" id="birim1" disabled>
                                    <input type="hidden" class="form-control" name="hiddenbirim1" id="hiddenbirim1" >
                                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="d-grid align-items-center">
                                <label class="form-label pe-5 me-5">Miktar</label>
                                <input type="number" class="form-control" id="miktar1" onchange="productchanged(1)"
                                    name="miktar1" placeholder="0,00">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="d-grid align-items-center">
                                <label class="form-label pe-5 me-5">BR.fiyat</label>
                                <input type="number" class="form-control" disabled id="birimfiyat1" name="birimfiyat1"
                                    placeholder="0,00">
                                    <input type="hidden" class="form-control"  id="hiddenbirimfiyat1" name="hiddenbirimfiyat1"
                                    placeholder="0,00">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="d-grid align-items-center">
                                <label class="form-label pe-5 me-5">Iskonto</label>
                                <select class="form-select" name="iskonto1" onchange="productchanged(1)" id="iskonto1">
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
                                    <select class="form-select" name="kdv1" onchange="productchanged(1)" id="kdv1">
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
                                    <input type="number" class="form-control" disabled id="fiyat1" name="urunfiyat1"
                                        placeholder="0,00">
                                        <input type="hidden" class="form-control"  id="hiddenfiyat1" name="hiddenurunfiyat1"
                                        placeholder="0,00">
                                    <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="h-auto w-100">

            <div class="row  d-flex justify-content-center align-items-center mt-3  ">
                <div class="col-md-3  d-flex justify-content-start align-items-center w-75   ps-5 ms-4  gap-3">

                    <div class="d-grid align-items-center justify-content-center w-50 pt-3">

                        <label class="form-label pe-5 me-5 w-100 "> teklif durumu </label>
                        <div class="input-group mb-3">

                            <select class="form-select" name="durum" id="durum">
                                <option selected value="waiting">Onay bekliyor</option>

                                <option value="true">fatura oluşturuşdu</option>
                                <option value="false">fatura oluşturulmadı</option>


                            </select>
                        </div>

                    </div>

                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="toplamtekliftutar" disabled
                                id="toplamtutar" placeholder="0,00">
                                <input type="hidden" value="0" name="hiddentoplamtekliftutar" id="hiddentoplamtutar">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> BÜRÜT TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="bürüt" disabled id="bürüt"
                                placeholder="0,00">
                                <input type="hidden" value="0" name="hiddenbürüt"  id="hiddenbürüt" >
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM ISKONTO </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="toplamiskonto" disabled
                                id="toplamiskonto" placeholder="0,00">
                                <input type="hidden"  value="0" name="hiddentoplamiskonto" id="hiddentoplamiskonto" >
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM KDV </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="toplamkdv" disabled
                                id="toplamkdv" placeholder="0,00">
                                <input type="hidden"   value="0" name="hiddentoplamkdv" 
                                id="hiddentoplamkdv" >
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

    // Your jQuery methods go here...

    // Function to generate a random product ID
    function generateRandomProductId() {
        var productId = "";
        for (var i = 0; i < 10; i++) {
            productId += Math.floor(Math.random() * 10);
        }
        return productId;
    }

    // Get a random product ID
    var randomNumbers = generateRandomProductId();


    // Set the value of the element with ID "ordernumber"
    $("#ordernumber").val("HR" + randomNumbers);
    $("#ordernumber_hidden").val("HR" + randomNumbers);
});




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

                            var priceInput = $("#birimfiyat" + index);
                            var hiddenpriceInput = $("#hiddenbirimfiyat" + index);
                            var birimInput = $("#birim" + index);
                            var hiddenbirimInput = $("#hiddenbirim" + index);
                            var miktarInput = $("#miktar" + index);
                            var toplamtutarbox = $("#toplamtutar");
                            var toplamtutarbox = $("#hiddentoplamtutar");

                            birimInput.val(alSatBirim);
                            hiddenbirimInput.val(alSatBirim);
                            priceInput.val(price);
                            hiddenpriceInput.val(price);
                            var miktar = parseFloat(miktarInput.val());
                            var changeprice = parseFloat(miktar);
                            if (changeprice > 0) {
                                price = price * changeprice;

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
            calculateTotal();

        }
        //3th function
        function loadDoc() {
            dycnum = dycnum + 1;
            console.log(dycnum + "dynmuneber ");
            var newContent = document.createElement("div");
            newContent.className = "d-flex justify-content-center gap-5 ms-5";
            newContent.innerHTML =
                ` 
                <div class="col-md-1 me-5">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5 text-nowrap">Hizmet /Ürün</label>
                <select class="form-select" onchange="productchanged(`+ dycnum + `)" name="urunhizmet` + dycnum + `" id="urunhizmet` + dycnum + `">
                    <option selected value=""></option>
                    <?php
                    require "db.php";
                    $sql = $db->prepare("select * from products ");
                    $sql->execute();
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
                    <input type="text" class="form-control" name="birim`+ dycnum + `" id="birim` + dycnum + `" disabled>
                    <input type="hidden" name="hiddenbirim`+ dycnum + `" id="hiddenbirim` + dycnum + `" >
                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Miktar</label>
                <input type="number" class="form-control" id="miktar`+ dycnum + `" onchange="productchanged(` + dycnum + `)" name="miktar` + dycnum + `" placeholder="0,00">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">BR.fiyat</label>
                <input type="number" class="form-control" disabled id="birimfiyat`+ dycnum + `" name="birimfiyat` + dycnum + `" placeholder="0,00">
                
                <input type="hidden"   id="hiddenbirimfiyat`+ dycnum + `" name="hiddenbirimfiyat` + dycnum + `">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-5 me-5">Iskonto</label>
                <select class="form-select" name="iskonto`+ dycnum + `" onchange="productchanged(` + dycnum + `)" id="iskonto` + dycnum + `">
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
                    <select class="form-select" name="kdv`+ dycnum + `" onchange="productchanged(` + dycnum + `)" id="kdv` + dycnum + `">
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
                    <input type="number" class="form-control" disabled id="fiyat`+ dycnum + `" name="urunfiyat` + dycnum + `" placeholder="0,00">
                    <input type="hidden"  id="hiddenfiyat`+ dycnum + `" name="hiddenurunfiyat` + dycnum + `" >
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

        //calculateAllPrice function




    </script>







</body>


</html>