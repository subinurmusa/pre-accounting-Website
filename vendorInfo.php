<?php
// require "db.php";
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
$num = 0;
$error="";
ini_set('display_errors', 1);
error_reporting(E_ALL);

$vendorid= isset($_GET["id"])? $_GET["id"]:"";
require "db.php";

$sql=$db->prepare("SELECT * FROM vendors where id=?");
$sql->execute([$vendorid]);
$vendorlist= $sql->fetch(PDO::FETCH_ASSOC);




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

                            echo $_SESSION["name"];
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

    <div class=" container d-flex align-items-center w-50 justify-content-start mt-5 ">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark"> <i class="fa-solid fa-folder-plus fs-3 text-secondary"></i>Yeni Tedarikçi Ekle</p>
    </div>
    <hr>
    <div class="">
    <form method="POST" id="form" >
      
    <div class="container">
        <div class="row d-flex justify-content-end align-items-center">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5" id="errorDiv">
                <!--   the error message will be displayed here-->
            </div>
        </div>

        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-regular fa-user fs-5"></i>
                        <label for="vendorName" class="form-label w-25 me-5 pe-4 ps-4">tedarikçi Adı</label>
                        <input class="form-control" type="text" id="vendorName" disabled value="<?php echo $vendorlist["vendorName"]; ?>" name="vendorName">
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-tag fs-5"></i>
                        <label for="category" class="form-label w-25 me-5 pe-4 ps-4">Kategori</label>
                        <input class="form-control" disabled type="text" id="category"  value="<?php echo $vendorlist["category"]==null?"":$vendorlist["vendorName"]; ?>" name="category">
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-envelope-o fs-5"></i>
                        <label for="email" class="form-label w-25 me-5 pe-4 ps-4">Elposta Adresi</label>
                        <input class="form-control" type="email" disabled id="email" value="<?php echo $vendorlist["email"]==null?"":$vendorlist["email"]; ?>" name="email">
                    </div>
                </div>
            </div>
        </div>

       

        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-phone fs-5"></i>
                        <label for="phoneNum" class="form-label w-25 me-5 pe-4 ps-4">İletişim Numarası</label>
                        <input class="form-control" type="text" disabled id="phoneNum" value="<?php echo $vendorlist["phoneNum"]==null?"":$vendorlist["phoneNum"]; ?>" name="phoneNum">
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-fax fs-5"></i>
                        <label for="FaxNum" class="form-label w-25 me-5 pe-4 ps-4">Fax Numarası</label>
                        <input class="form-control" type="text" disabled id="FaxNum" value="<?php echo $vendorlist["FaxNum"]==null?"":$vendorlist["FaxNum"]; ?>" name="FaxNum">
                    </div>
                </div>
            </div>
        </div>

        <!-- ibanNum -->
        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-hashtag fs-5"></i>
                        <label for="ibanNum" class="form-label w-25 me-5 pe-4 ps-4">IBAN Numarası</label>
                        <input class="form-control" type="text" disabled id="ibanNum" value="<?php echo $vendorlist["ibanNum"]==null?"":$vendorlist["ibanNum"]; ?>" name="ibanNum">
                    </div>
                </div>
            </div>
        </div>
<hr>
        <!-- address -->
        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-start justify-content-center w-100 gap-4">
                        <i class="fa fa-map-marker-alt fs-5"></i>
                        <label for="address" class="form-label w-25 me-5 pe-4 ps-4">Açık Adres</label>
                        
                            <textarea class="form-control" id="address" disabled name="address" cols="5" rows="5"><?php echo $vendorlist["address"]==null?"":$vendorlist["address"]; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- postakodu -->
        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-mail-bulk fs-5"></i>
                        <label for="postakodu" class="form-label w-25 me-5 pe-4 ps-4">Posta Kodu</label>
                        <input class="form-control" type="text" disabled id="postakodu" value="<?php echo $vendorlist["postakodu"]==null?"":$vendorlist["postakodu"]; ?>" name="postakodu">
                    </div>
                </div>
            </div>
        </div>

        <!-- notes -->
        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa fa-sticky-note fs-5"></i>
                        <label for="notes" class="form-label w-25 me-5 pe-4 ps-4">Açıklama / Notlar </label>                       
                        <textarea  class="form-control" disabled  id="notes" name="notes"cols="5" rows="5"><?php echo $vendorlist["notes"]==null?"":$vendorlist["notes"]; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- TCKN -->
        <div class="row d-flex justify-content-end align-items-center mt-3">
            <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                    <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-id-card fs-5"></i>
                        <label for="VKNorTCKN" class="form-label w-25 me-5 pe-4 ps-4">VKN/TCKN</label>
                        <input class="form-control" type="text" disabled id="VKNorTCKN" value="<?php echo $vendorlist["TCKNorVKN"]==null?"":$vendorlist["TCKNorVKN"]; ?>" name="VKNorTCKN">
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row d-flex justify-content-end align-items-center mt-3">
        <div class="col-md-9 d-flex align-items-center justify-content-end me-5 pe-5">
            <div class="buttons">
                <a href="vendors.php" class="btn btn-secondary">İptal</a>
                <button type="submit" name="submit" id="submit"  class="btn btn-primary opacity-75">Kaydet</button>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>








</body>


</html>