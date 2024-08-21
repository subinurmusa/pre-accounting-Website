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



?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);

//phpinfo();
require "db.php";

$sqluserid=$db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId=$sqluserid->fetch(PDO::FETCH_ASSOC);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here

        // Your database operations, form processing, etc.
        $uruncodu = isset($_POST['uruncoduhidden']) ? $_POST['uruncoduhidden'] : null;
        $urunname = isset($_POST['urunname']) ? $_POST['urunname'] : null;
        $addedDate = isset($_POST['addedDate']) ? $_POST['addedDate'] : null;
        $price = isset($_POST['price']) ? $_POST['price'] : 0;
        $barkod = isset($_POST['barkod']) ? $_POST['barkod'] : null;
        $photo = isset($_POST['photo']) ? $_POST['photo'] : null;
        $birim = isset($_POST['birim']) ? $_POST['birim'] : 0;
        $StokMiktar = isset($_POST['StokMiktar']) ? $_POST['StokMiktar'] : 0;
        $SellMiktar = isset($_POST['SellMiktar']) ? $_POST['SellMiktar'] : 0;
        $kiritikstokseviyesi = isset($_POST['kiritikstokseviyesi']) ? $_POST['kiritikstokseviyesi'] : 0;
          var_dump($_POST);
        if (!empty($_FILES["photo"]["name"])) {
            $filename = $_FILES["photo"]["name"];
            $tmpname = $_FILES["photo"]["tmp_name"];
            if (move_uploaded_file($tmpname, '../public_html/photoes/' . $filename)) {
                $photo = $filename;
            } else {
                $photo = "";
            }
        } else {
            $photo = "";
        }
       
        if (empty($uruncodu) || empty($urunname) || empty($birim) || empty($SellMiktar)) {
            $error = "<div class='alert alert-danger'> Ürün barkodu , Adı, Birimi , Satış Miktarı Zorunlu Alanlardır </div>";

        } else if (empty($addedDate) || empty($StokMiktar)) {
            $error = "<div class='alert alert-danger'> Stok miktarı ve Oluşturulma Trihi   doldurulması zorunlu alanlardır </div>";

        } else if (empty($price)) {
            $error = "<div class='alert alert-danger'>Adres , Fiyat Gerekli alanlardır </div>";

        } else {

                $sql = $db->prepare("INSERT INTO `products`(`productcode`, `date-added`, `productname`, `price`, `barkodnumara`, `productphoto`, `alSatBirim`, `stokmiktari`, `miktar`,  `kiritiklevel`, `userId`) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?)");

            $sql->execute([$uruncodu, $addedDate, $urunname, $price, $barkod, $photo, $birim, $StokMiktar, $SellMiktar,$kiritikstokseviyesi,$userId["id"]]);

            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: urunvehizmet.php");
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark"> <i
                class="fa-solid fa-cart-plus fs-3"></i> Yeni Ürün OLuştur</p>
    </div>
    <hr>
    <div class="">
        <form method="POST" id="form"  enctype="multipart/form-data"  >

            <div class="container mb-5">
                <div class="row  d-flex justify-content-end align-items-center  ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                        <?php echo $tt = empty($error) ? "" : $error;

                        ?>
                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-hashtag fs-5"></i>
                                <label for="uruncodu" class="form-label w-25  me-5 pe-4 ps-4">Ürün Codu</label>
                                <input class="form-control" type="text" id="uruncodu" name="uruncodu" disabled>
                                <input type="hidden" id="uruncoduhidden" name="uruncoduhidden">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-tag fs-5"></i>
                                <label for="urunname" class="form-label w-25  me-5 pe-4 ps-4">Ürün Adı</label>

                                <input class="form-control" type="text" id="urunname" name="urunname">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-calendar-plus fs-5"></i>
                                <label for="addedDate" class="form-label w-25  me-5 pe-4 ps-4">Ekleme Tarihi </label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="addedDate" name="addedDate">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="price" class="form-label w-25  me-5 pe-4 ps-4">Ürün Fiyatı </label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="price" name="price">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-barcode fs-5"></i>
                                <label for="barkod" class="form-label w-25  me-5 pe-4 ps-4">Ürün Barkodu </label>
                                <input class="form-control" type="text" id="barkod" name="barkod">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-image fs-5"></i>
                                <label class="form-label w-25  me-5 pe-4 ps-4">Ürün Fotorafı</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <!-- <input class="form-control" type="file" id="photo" name="photo"> -->
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <input class="form-control" type="file" id="photo" name="photo"
                                        onchange="previewImage()">
                                    <img id="imagePreview" src="#" alt="Preview"
                                        style="display:none; max-width: 80px; max-height: 80px;" class="p-1 ms-1 border rounded">
                                </div>

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-balance-scale fs-5"></i>

                                <label for="birim" class="form-label w-25  me-5 pe-4 ps-4">Birim</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <select class="form-select" type="text" id="birim" name="birim">
                                    <option value=""> <span> Birim Seç ..</option>
                                    <option value="Adet">Adet <span>(Tekil öğeler için)</span></option>
                                    <option value="Düzine">Düzine <span>(On iki adetlik miktar için)</span></option>
                                    <option value="Çift">Çift <span>(Çift olarak satılan ürünler için)</span></option>
                                    <option value="Kutu">Kutu <span>(Kutu şeklinde paketlenmiş ürünler için)</span>
                                    </option>
                                    <option value="Paket">Paket <span>(Paketlenmiş ürünler için)</span></option>
                                    <option value="Karton">Karton <span>(Karton ambalajlı ürünler için)</span></option>
                                    <option value="Kasa">Kasa <span>(Bir arada gruplandırılmış ürünler için)</span>
                                    </option>
                                    <option value="Demet">Demet <span>(Bağ halinde satılan ürünler için)</span></option>
                                    <option value="Set">Set <span>(Set halinde satılan ürünler için)</span></option>
                                    <option value="Şişe">Şişe <span>(Şişe içecekler için)</span></option>
                                    <option value="Kavanoz">Kavanoz <span>(Kavanoz içecekler veya gıdalar için)</span>
                                    </option>
                                    <option value="Tüp">Tüp <span>(Tüplü ürünler için)</span></option>
                                    <option value="Rulo">Rulo <span>(Rulo şeklinde satılan ürünler için)</span></option>
                                    <option value="Torba">Torba <span>(Torba içinde paketlenmiş ürünler için)</span>
                                    </option>
                                    <option value="Galon">Galon <span>(Sıvı ürünler için)</span></option>
                                    <option value="Litre">Litre <span>(Litre cinsinden ölçülen sıvı ürünler için)</span>
                                    </option>
                                    <option value="Ons">Ons <span>(Ons cinsinden ölçülen ürünler için)</span></option>
                                    <option value="Pound">Pound <span>(Pound cinsinden ölçülen ürünler için)</span>
                                    </option>
                                    <option value="Gram">Gram <span>(Gram cinsinden ölçülen ürünler için)</span>
                                    </option>
                                    <option value="Kilogram">Kilogram <span>(Kilogram cinsinden ölçülen ürünler
                                            için)</span></option>
                                    <option value="Mililitre">Mililitre <span>(Mililitre cinsinden ölçülen sıvı ürünler
                                            için)</span></option>
                                    <option value="Metre">Metre <span>(Metre cinsinden ölçülen ürünler için)</span>
                                    </option>
                                    <option value="Yard">Yard <span>(Yard cinsinden ölçülen ürünler için)</span>
                                    </option>
                                    <option value="Kare Ayak">Kare Ayak <span>(Kare Ayak cinsinden ölçülen ürünler
                                            için)</span></option>
                                    <option value="Kübik Ayak">Kübik Ayak <span>(Kübik Ayak cinsinden ölçülen ürünler
                                            için)</span></option>
                                    <option value="Kübik Metre">Kübik Metre <span>(Kübik Metre cinsinden ölçülen ürünler
                                            için)</span></option>
                                    <option value="Metrekare">Metrekare <span>(Metrekare cinsinden ölçülen ürünler
                                            için)</span></option>
                                    <option value="Ton">Ton <span>(Ton cinsinden ölçülen ürünler için)</span></option>
                                    <option value="Varil">Varil <span>(Belirli sıvılar veya toptan ürünler için)</span>
                                    </option>
                                    <option value="Palet">Palet <span>(Palet üzerinde taşınan , toptan taşımacılık
                                            ürünler için)</span></option>
                                    <option value="Tepsi">Tepsi <span>(Tepsilerde sunulan ürünler için)</span></option>
                                    <option value="Kağıt Paketi">Kağıt Paketi <span>(Kağıt ürünler için paket
                                            şeklinde)</span></option>
                                    <option value="Levha">Levha <span>(Levha şeklinde satılan inşaat malzemeleri
                                            için)</span></option>
                                    <option value="Düzine">Düzine <span>(Düzine halinde satılan ürünler için)</span>
                                    </option>
                                    <option value="Baş">Baş <span>(Baş şeklinde satılan marul, lahana vb. ürünler
                                            için)</span></option>
                                    <option value="Pound">Pound <span>(Pound cinsinden satılan , et, balık vb. ürünler
                                            için)</span></option>
                                    <option value="Raf">Raf <span>(Raf üzerinde sergilenen şarap şişeleri, giysiler vb.
                                            ürünler için)</span></option>


                                </select>

                            </div>
                        </div>

                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-cubes fs-5"></i>

                                <label for="StokMiktar" class="form-label w-25  me-5 pe-4 ps-4">Stok Miktarı</label>
                                <input class="form-control" type="number" id="StokMiktar" name="StokMiktar">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-cubes fs-5"></i>

                                <label for="kiritikstokseviyesi" class="form-label w-25  me-5 pe-4 ps-4">Kritik stok seviyesi</label>
                                <input class="form-control" type="number" id="kiritikstokseviyesi" name="kiritikstokseviyesi"> 
                               
                            </div>
                         
                        </div>
                       
                    </div>
                  
                   

                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="SellMiktar" class="form-label w-25  me-5 pe-4 ps-4">Satış Miktarı</label>
                                <input class="form-control" type="number" id="SellMiktar" name="SellMiktar">

                            </div>

                        </div>

                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex  align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                            <div class="bottons">
                                <a href="urunvehizmet.php" class="btn btn-secondary">Vazgeç</a>
                                <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75">
                                    Ekle</button>

                            </div>

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
            $("#uruncodu").val(randomNumbers);
            $("#uruncoduhidden").val(randomNumbers);
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
                $("#addedDate").datepicker($.datepicker.regional["tr"]);

            });
        }) 
    </script>
    <script>
        // JavaScript
        function previewImage() {
            var fileInput = document.getElementById('photo');
            var imagePreview = document.getElementById('imagePreview');

            // Check if file is selected
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>






</body>


</html>