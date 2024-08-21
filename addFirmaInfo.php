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
 

$firmainfosql=$db->prepare("SELECT  * FROM `companyinfo` where userId=?");
$firmainfosql->execute([$userId["id"]]);
$firmainfo=$firmainfosql->fetch(PDO::FETCH_ASSOC);

?>
<?php

error_reporting(E_ALL);

//phpinfo();



try {
    if (isset($_POST['submit'])) {
        // Your existing code here

        // Your database operations, form processing, etc.
        $firmaAdi = isset($_POST['firmaAdi']) ? $_POST['firmaAdi'] : null;
        $logo = isset($_FILES['imgName']) ? $_FILES['imgName']['name'] : null;
        $logo_temp = isset($_FILES['imgName']) ? $_FILES['imgName']['tmp_name'] : null;
        
        $imza = isset($_FILES['firmaImzasiName']) ? $_FILES['firmaImzasiName']['name'] : null;
        $imza_temp = isset($_FILES['firmaImzasiName']) ? $_FILES['firmaImzasiName']['tmp_name'] : null;
        
        $unvan = isset($_POST['unvan']) ? $_POST['unvan'] : null;
        $sektor = isset($_POST['sektor']) ? $_POST['sektor'] : null;
        $acikAdres = isset($_POST['acikAdres']) ? trim($_POST['acikAdres']) : null;
        $vkn = isset($_POST['vkn']) ? trim($_POST['vkn']) : null;

        $il = isset($_POST['il']) ? $_POST['il'] : null;
        $ilce = isset($_POST['ilce']) ? $_POST['ilce'] : null;
        $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
        $fax = isset($_POST['fax']) ? $_POST['fax'] : null;
        $vergidairesi = isset($_POST['vergidairesi']) ? $_POST['vergidairesi'] : null;
        $vergiNum = isset($_POST['vergiNum']) ? $_POST['vergiNum'] : null;
        $mersisNum = isset($_POST['mersisNum']) ? $_POST['mersisNum'] : null;
        $ticaretSicilNum = isset($_POST['ticaretSicilNum']) ? $_POST['ticaretSicilNum'] : null;
        var_dump($_POST);
      
        if (empty($firmaAdi)) {
            $error = "<div class='alert alert-danger'>Firma Adı Zorunlu Alandır</div>";
        }
         else if (empty($vkn)){
            $error = "<div class='alert alert-danger'>VKN Zorunlu Alandır</div>";


        } else {
            if ($logo_temp && move_uploaded_file($logo_temp, "photoes/$logo")) {
                // File uploaded successfully, proceed with saving the filename to the database
                $logo_filename = "photoes/$logo";
                $sql = $db->prepare("UPDATE `companyinfo` SET  `logo`=? where userId=? ;");

                $sql->execute([$logo_filename,$userId["id"]]);
            } 
            
            if ($imza_temp && move_uploaded_file($imza_temp, "photoes/$imza")) {
                // File uploaded successfully, proceed with saving the filename to the database
                $imza_filename = "photoes/$imza";
                $sql = $db->prepare("UPDATE `companyinfo` SET `imza`=? where userId=?;");

            $sql->execute([ $imza_filename,$userId["id"]]);
        
            }
            $sql = $db->prepare("UPDATE `companyinfo` SET `companyName`=?,  `ticariUnvan`=?, `vkn`=?, `sektor`=?, `address`=?, `province`=?, `district`=?, `phone`=?, `fax`=?, `vergiDairesi`=?, `vergiNum`=?, `mersisNum`=?, `ticaretSicilNum`=? where userId=?");

            $sql->execute([$firmaAdi,  $unvan, $vkn, $sektor, $acikAdres, $il, $ilce, $phone, $fax, $vergidairesi, $vergiNum, $mersisNum, $ticaretSicilNum,$userId["id"]]);
        
          /*   try {
                $testdata = $db->prepare("SELECT * FROM `companyinfo` ");
                
                if ($testdata->execute()) {
                    $sql = $db->prepare("UPDATE `companyinfo` SET `companyName`=?,  `ticariUnvan`=?, `sektor`=?, `address`=?, `province`=?, `district`=?, `phone`=?, `fax`=?, `vergiDairesi`=?, `vergiNum`=?, `mersisNum`=?, `ticaretSicilNum`=? where userId=");

                    $sql->execute([$firmaAdi,  $unvan, $sektor, $acikAdres, $il, $ilce, $phone, $fax, $vergidairesi, $vergiNum, $mersisNum, $ticaretSicilNum,$userId["id"]]);
                
                } 
            } catch (PDOException $e) {
                // Handle PDO exception
              //  echo "PDO Exception: " . $e->getMessage();
              $sql = $db->prepare("INSERT INTO `companyinfo` (`companyName`, `ticariUnvan`, `sektor`, `address`, `province`, `district`, `phone`, `fax`, `vergiDairesi`, `vergiNum`, `mersisNum`, `ticaretSicilNum`) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sql->execute([$firmaAdi,  $unvan, $sektor, $acikAdres, $il, $ilce, $phone, $fax, $vergidairesi, $vergiNum, $mersisNum, $ticaretSicilNum]);
      
            }
            */
              
            
           
            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to urunvehizmet.php
                header("location: firmaInfo.php");
                // Make sure to exit after header to prevent further code execution
                exit();
            } else {
                $error = "<div class='alert alert-danger'>Veri kaydedilirken bir hata oluştu.</div>";
            }
        }
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark">
            <i class="fa-solid fa-marker fs-3 "></i> Firma Bilgilerini Güncelle
        </p>
    </div>
    <hr>
    <div class="">
        <form method="POST" id="form" enctype="multipart/form-data">

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
                                <i class="fa-solid fa-building fs-5"></i>
                                <?php $firmaAdisql=$db->prepare("SELECT  companyName FROM users WHERE username= ? and Id=?");
                                $firmaAdisql->execute([$_SESSION["username"],$userId["id"]]);
                                $firmaAdi=$firmaAdisql->fetch(PDO::FETCH_COLUMN);?>
                                <label for="firmaAdi" class="form-label w-25  me-5 pe-4 ps-4">Firma Adı</label>
                                <input class="form-control" type="text" value="<?php 
echo isset($firmainfo["companyName"]) 
? ($firmaAdi == $firmainfo["companyName"] 
    ? $firmainfo["companyName"] 
    : ($firmainfo["companyName"] == null 
        ? $firmaAdi 
        : $firmainfo["companyName"]
    )
)
: $firmaAdi; 
?>" id="firmaAdi" name="firmaAdi">


                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-start w-100 gap-1">
                                <i class="fa-regular fa-image"></i>
                                <label for="urunname" class="form-label w-25 me-4 pe-4 ps-5">Firma Logosu</label>

                                <!-- Input field for selecting image -->
                                <input type="file" id="logoInput" value="<?php echo $firmainfo["logo"]==null?"":$firmainfo["logo"]; ?>" name="imgName" class="d-none" accept="image/*">

                                <div class="imgDisplaying">

                                    <img id="logoPreview" src="<?php echo $firmainfo["logo"]==null?"":$firmainfo["logo"]; ?>" alt="" class="<?php  echo $firmainfo["logo"] == null ? "d-none" : ""; ?> me-2"
                                        style="width: 80px; height: 80px;">
                                </div>

                                <!-- Button to trigger file input -->
                                <button id="selectImageButton" class="btn btn-primary me-2">Logo Yükle</button>
                                <button id="eraseButton" class="btn btn-secondary d-none">Sil</button>
                            </div>




                        </div>

                    </div>


                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-start w-100 gap-1">
                                <i class="fa-regular fa-image"></i>
                                <label for="firmaImzasiInput" class="form-label w-25 me-4 pe-4 ps-5">Firma
                                    İmzası</label>

                                <!-- Input field for selecting firma imzasi image -->
                                <input type="file" id="firmaImzasiInput"  value="<?php echo $firmainfo["imza"]==null?"":$firmainfo["imza"]; ?>" name="firmaImzasiName" class="d-none"
                                    accept="image/*">

                                <div class="imgDisplaying">
                                <img id="firmaImzasiPreview" src="<?php echo $firmainfo["imza"] == null ? "" : $firmainfo["imza"]; ?>" alt="" class="imgDisplaying <?php  echo $firmainfo["imza"] == null ? "d-none" : ""; ?> me-2" style="width: 80px; height: 80px;">

                                </div>

                                <!-- Button to trigger firma imzasi file input -->
                                <button id="selectFirmaImzasiButton" class="btn btn-primary me-1">İMZA Yükle</button>
                                <button id="eraseFirmaImzasiButton" class="btn btn-secondary d-none">Sil</button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="price" class="form-label w-25  me-5 pe-4 ps-4">TİCARİ UNVAN</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="unvan" name="unvan" value="<?php echo isset($firmainfo["ticariUnvan"]) ? $firmainfo["ticariUnvan"] : "";?>">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-barcode fs-5"></i>
                                <label for="barkod" class="form-label w-25  me-5 pe-4 ps-4">Sektör </label>
                                <select class="form-select" name="sektor" id="sektor">

                                <option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Reklam/Tasarım"?"selected":""; ?> value="Reklam/Tasarım">Reklam/Tasarım</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Halkla İlişkiler & Organizasyon"?"selected":""; ?> value="Halkla İlişkiler & Organizasyon">Halkla İlişkiler & Organizasyon</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Eğitim/Danışmanlık"?"selected":""; ?> value="Eğitim/Danışmanlık">Eğitim/Danışmanlık</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Mimarlık"?"selected":""; ?> value="Mimarlık">Mimarlık</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Hukuk Hizmetleri"?"selected":""; ?> value="Hukuk Hizmetleri">Hukuk Hizmetleri</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="SMMM Hizmetleri"?"selected":""; ?> value="SMMM Hizmetleri">SMMM Hizmetleri</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Yazılım/Teknoloji"?"selected":""; ?> value="Yazılım/Teknoloji">Yazılım/Teknoloji</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="E-Ticaret"?"selected":""; ?> value="E-Ticaret">E-Ticaret</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Perakende"?"selected":""; ?> value="Perakende">Perakende</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Üretim"?"selected":""; ?> value="Üretim">Üretim</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="İnşaat/Gayrimenkul"?"selected":""; ?> value="İnşaat/Gayrimenkul">İnşaat/Gayrimenkul</option>
<option <?php echo isset($firmainfo["sektor"]) && $firmainfo["sektor"]=="Diğer"?"selected":( !isset($firmainfo["sektor"])?"selected":""); ?> value="Diğer">Diğer</option>
</select>

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                                <i class="fas fa-image fs-5"></i>
                                <label class="form-label w-25 me-5 pe-2  ps-4">Açık Adress</label>
                                <textarea class="form-control" name="acikAdres" id="acikAdres"  cols="30"
                                    rows="5">
                                <?php echo isset($firmainfo["address"])?$firmainfo["address"]:""; ?>
                                </textarea>


                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                                <i class="fas fa-balance-scale fs-5"></i>
                                <label for="ilceil" class="form-label w-25   ps-4">İlçe , İl </label>
                                <div class="form-group">
                                    <label for="il">İl Seçiniz:</label>
                                    <input type="text" id="il" name="il" class="form-control" value="<?php echo isset($firmainfo["province"]) ? $firmainfo["province"] : ""; ?>
" placeholder="İl arayın...">
                                    <div id="il-search-results" class="search-results"></div>
                                </div>
                                <div class="form-group">
                                    <label for="ilce">İlçe Seçiniz:</label>
                                    <input type="text" id="ilce" name="ilce" class="form-control"  value="<?php echo isset($firmainfo["district"])? $firmainfo["district"] :"" ;?>"placeholder="İlçe arayın...">
                                    <div id="ilce-search-results" class="search-results"></div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-cubes fs-5"></i>

                                <label for="phone" class="form-label w-25  me-5 pe-4 ps-4">Telefon</label>
                                <input class="form-control" type="number" id="phone" value="<?php echo $firmainfo["phone"]==null?"":$firmainfo["phone"]; ?>" name="phone">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-cubes fs-5"></i>

                                <label for="fax" class="form-label w-25  me-5 pe-4 ps-4"> Faks </label>
                                <input class="form-control" type="text" id="fax" name="fax" value="<?php echo isset($firmainfo["fax"])?$firmainfo["fax"]:""; ?>" >

                            </div>

                        </div>

                    </div>



                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="vergidairesi" class="form-label w-25 text-nowrap me-5 pe-4 ps-4">VERGİ DAİRESİ</label>
                                <input class="form-control" type="text" id="vergidairesi" value="<?php echo isset($firmainfo["vergiDairesi"])?$firmainfo["vergiDairesi"]:""; ?>" name="vergidairesi">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="vergiNum" class="form-label w-25 text-nowrap me-5 pe-2 ps-4">VERGİ NUMARASI</label>
                                <input class="form-control" type="text" id="vergiNum"  value="<?php echo isset($firmainfo["vergiNum"])?$firmainfo["vergiNum"]:""; ?>"name="vergiNum">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="mersisNum" class="form-label w-25 text-nowrap  pe-5 ps-4">MERSİS NUMARASI</label>
                                <input class="form-control" type="text" id="mersisNum"  value="<?php echo isset($firmainfo["mersisNum"])?$firmainfo["mersisNum"]:""; ?>" name="mersisNum">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="vkn" class="form-label w-25  me-5 pe-4 ps-4">VKN</label>
                                <input class="form-control" type="number" id="vkn" placeholder="Vergi Kimlik Numarası" value="<?php echo isset($firmainfo["vkn"])?$firmainfo["vkn"]:""; ?>" name="vkn">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-shopping-basket fs-5"></i>

                                <label for="ticaretSicilNum" class="form-label w-25  text-nowrap pe-3 ps-2">TİCARET SİCİL
                                    NUMARASI</label>
                                <input class="form-control" type="text" id="ticaretSicilNum" value="<?php echo isset($firmainfo["ticaretSicilNum"])?$firmainfo["ticaretSicilNum"]:""; ?>" name="ticaretSicilNum">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex  align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                            <div class="bottons">
                                <a href="firmaInfo.php" class="btn btn-secondary">Vazgeç</a>
                                <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75">
                                    Düzenle </button>

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

    <script>
        // Function to handle file input change event for the logo
        function handleFileInputChange(event) {
            const file = event.target.files[0];
            const logoPreview = document.getElementById('logoPreview');
            const eraseButton = document.getElementById('eraseButton');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('d-none');
                    eraseButton.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }

        // Function to handle erasing button click for the logo
        function eraseLogoImage(event) {
            event.preventDefault(); // Prevent default button behavior
            const logoPreview = document.getElementById('logoPreview');
            const eraseButton = document.getElementById('eraseButton');
            const logoInput = document.getElementById('logoInput');

            logoPreview.src = '';
            logoPreview.classList.add('d-none');
            eraseButton.classList.add('d-none');
            logoInput.value = '';
        }

        // Function to handle select image button click for the logo
        document.getElementById('selectImageButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default button behavior
            document.getElementById('logoInput').click(); // Trigger file input click
        });

        // Event listener for logo file input change
        document.getElementById('logoInput').addEventListener('change', handleFileInputChange);

        // Event listener for erasing button click for the logo
        document.getElementById('eraseButton').addEventListener('click', eraseLogoImage);

        // Function to handle file input change event for the firma imzasi
        function handleFirmaImzasiInputChange(event) {
            const file = event.target.files[0];
            const firmaImzasiPreview = document.getElementById('firmaImzasiPreview');
            const eraseFirmaImzasiButton = document.getElementById('eraseFirmaImzasiButton');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    firmaImzasiPreview.src = e.target.result;
                    firmaImzasiPreview.classList.remove('d-none');
                    eraseFirmaImzasiButton.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }

        // Function to handle erasing button click for the firma imzasi
        function eraseFirmaImzasi(event) {
            event.preventDefault(); // Prevent default button behavior
            const firmaImzasiPreview = document.getElementById('firmaImzasiPreview');
            const eraseFirmaImzasiButton = document.getElementById('eraseFirmaImzasiButton');
            const firmaImzasiInput = document.getElementById('firmaImzasiInput');

            firmaImzasiPreview.src = '';
            firmaImzasiPreview.classList.add('d-none');
            eraseFirmaImzasiButton.classList.add('d-none');
            firmaImzasiInput.value = '';
        }

        // Function to handle select image button click for the firma imzasi
        document.getElementById('selectFirmaImzasiButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default button behavior
            document.getElementById('firmaImzasiInput').click(); // Trigger file input click
        });

        // Event listener for firma imzasi file input change
        document.getElementById('firmaImzasiInput').addEventListener('change', handleFirmaImzasiInputChange);

        // Event listener for erasing button click for the firma imzasi
        document.getElementById('eraseFirmaImzasiButton').addEventListener('click', eraseFirmaImzasi);
    </script>

<script>
    // Function to prevent default action on Enter key press for text input fields
    document.addEventListener('keydown', function(event) {
        const focusedElement = document.activeElement;
        if (event.key === 'Enter' && focusedElement.tagName === 'INPUT' && focusedElement.type === 'text') {
            event.preventDefault(); // Prevent default action
        }
    });
</script>

 <script>
    // İl ve ilçe verileri
    const iller = ['İstanbul', 'Ankara', 'İzmir', 'Adana', 'Antalya', 'Bursa', 'Kocaeli'];
    const ilceler = {
        'İstanbul': ["Adalar",
            "Arnavutköy",
            "Ataşehir",
            "Avcılar",
            "Bağcılar",
            "Bahçelievler",
            "Bakırköy",
            "Başakşehir",
            "Bayrampaşa",
            "Beşiktaş",
            "Beykoz",
            "Beylikdüzü",
            "Beyoğlu",
            "Büyükçekmece",
            "Çatalca",
            "Çekmeköy",
            "Esenler",
            "Esenyurt",
            "Eyüpsultan",
            "Fatih",
            "Gaziosmanpaşa",
            "Güngören",
            "Kadıköy",
            "Kağıthane",
            "Kartal",
            "Küçükçekmece",
            "Maltepe",
            "Pendik",
            "Sancaktepe",
            "Sarıyer",
            "Silivri",
            "Sultanbeyli",
            "Sultangazi",
            "Şile",
            "Şişli",
            "Tuzla",
            "Ümraniye",
            "Üsküdar",
            "Zeytinburnu"],
        'Ankara': ["Akyurt",
            "Altındağ",
            "Ayaş",
            "Bala",
            "Beypazarı",
            "Çamlıdere",
            "Çankaya",
            "Çubuk",
            "Elmadağ",
            "Etimesgut",
            "Evren",
            "Gölbaşı",
            "Güdül",
            "Haymana",
            "Kalecik",
            "Kahramankazan",
            "Keçiören",
            "Kızılcahamam",
            "Mamak",
            "Nallıhan",
            "Polatlı",
            "Pursaklar",
            "Sincan",
            "Şereflikoçhisar",
            "Yenimahalle"],
        'İzmir': ["Aliağa",
            "Balçova",
            "Bayındır",
            "Bayraklı",
            "Bergama",
            "Beydağ",
            "Bornova",
            "Buca",
            "Çeşme",
            "Çiğli",
            "Dikili",
            "Foça",
            "Gaziemir",
            "Güzelbahçe",
            "Karabağlar",
            "Karaburun",
            "Karşıyaka",
            "Kemalpaşa",
            "Kınık",
            "Kiraz",
            "Konak",
            "Menderes",
            "Menemen",
            "Narlıdere",
            "Ödemiş",
            "Seferihisar",
            "Selçuk",
            "Tire",
            "Torbalı",
            "Urla"],
        'Adana': ["Aladağ",
            "Ceyhan",
            "Çukurova",
            "Feke",
            "İmamoğlu",
            "Karaisalı",
            "Karataş",
            "Kozan",
            "Pozantı",
            "Saimbeyli",
            "Sarıçam",
            "Seyhan",
            "Tufanbeyli",
            "Yumurtalık",
            "Yüreğir"],
        'Antalya': ["Aksu",
            "Alanya",
            "Demre",
            "Döşemealtı",
            "Elmalı",
            "Finike",
            "Gazipaşa",
            "Gündoğmuş",
            "Kaş",
            "Kemer",
            "Kepez",
            "Konyaaltı",
            "Korkuteli",
            "Kumluca",
            "Manavgat",
            "Muratpaşa",
            "Serik"],
        'Bursa': ["Büyükorhan",
            "Gemlik",
            "Gürsu",
            "Harmancık",
            "İnegöl",
            "İznik",
            "Karacabey",
            "Keles",
            "Kestel",
            "Mudanya",
            "Mustafakemalpaşa",
            "Nilüfer",
            "Orhaneli",
            "Orhangazi",
            "Osmangazi",
            "Yenişehir",
            "Yıldırım"],
        'Kocaeli': ["Başiskele",
            "Çayırova",
            "Darıca",
            "Derince",
            "Dilovası",
            "Gebze",
            "Gölcük",
            "İzmit",
            "Kandıra",
            "Karamürsel",
            "Kartepe",
            "Körfez"]
    };

  

     // İl ve ilçe inputlarını seçme
     const ilInput = document.getElementById('il');
    const ilceInput = document.getElementById('ilce');
    const ilSearchResults = document.getElementById('il-search-results');
    const ilceSearchResults = document.getElementById('ilce-search-results');

    // Click event listener to hide search results when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.matches('#il, #ilce, .search-item')) {
            hideSearchResults();
        }
    });

    // İl inputuna yazıldıkça il araması yapma
    ilInput.addEventListener('input', function () {
        const searchText = this.value.trim();
        const filteredIller = iller.filter(function (il) {
            return new RegExp(searchText.replace('i', '[iıİİ]'), 'i').test(il);
        });
        displaySearchResults(filteredIller, ilSearchResults);
    });

    // İlçe inputuna yazıldıkça ilçe araması yapma
    ilceInput.addEventListener('input', function () {
        const secilenIl = ilInput.value.trim();
        if (!secilenIl) {
            // İl seçilmemişse ilçe araması yapma
            return;
        }
        const searchText = this.value.trim();
        const filteredIlceler = ilceler[secilenIl].filter(function (ilce) {
            return new RegExp(searchText.replace('i', '[iıİİ]'), 'i').test(ilce);
        });
        displaySearchResults(filteredIlceler, ilceSearchResults);
    });

    // Arama sonuçlarını gösterme
    function displaySearchResults(results, container) {
        container.innerHTML = '';
        results.forEach(function (result) {
            const item = document.createElement('div');
            item.textContent = result;
            item.classList.add('search-item');
            item.addEventListener('click', function () {
                container.previousElementSibling.value = result;
                hideSearchResults();
            });
            container.appendChild(item);
        });
        container.style.display = results.length > 0 ? 'block' : 'none'; // Show/hide results container
    }

    // Function to hide search results
    function hideSearchResults() {
        ilSearchResults.innerHTML = '';
        ilceSearchResults.innerHTML = '';
    }
</script>

<style>
    .search-results {
        max-height: 200px; /* Adjust the maximum height as needed */
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        position: absolute;
        z-index: 1000;
        width: 15%;
    }
    .search-item {
        cursor: pointer;
    }

    .search-item:hover {
        background-color: #f0f0f0;
    }
</style>

</body>


</html>