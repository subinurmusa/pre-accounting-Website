<?php
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

$sqluserid = $db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId = $sqluserid->fetch(PDO::FETCH_ASSOC);

$customername = $companyname = $unvan = $taxnumber = $vkn = $tc = $address = $email = $phoneNumber = $vergidairesi = $musterinumarasıhidden = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customername = $_POST['customername'] ?? '';
    $companyname = $_POST['companyname'] ?? '';
    $unvan = $_POST['unvan'] ?? '';
    $taxnumber = $_POST['taxnumber'] ?? '';
    $vkn = trim($_POST['vkn'] ?? '');
    $tc = $_POST['tc'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phoneNumber = $_POST['phoneNumber'] ?? '';
    $vergidairesi = $_POST['vergidairesi'] ?? '';
    $musterinumarasıhidden = $_POST['musterinumarasıhidden'] ?? '';

    if (empty($customername) || empty($companyname) || empty($unvan)) {
        $error = "<div class='alert alert-danger'> Şirket Adı / unvan / müşteri Adı alanlarda yanlış veya eksik bilgi vardır </div>";
    } else if (empty($email)) {
        $error = "<div class='alert alert-danger'> Email doldurulması zorunlu alandır </div>";
    } else if (empty($phoneNumber)) {
        $error = "<div class='alert alert-danger'>telefon Gerekli alandır </div>";
    } else {
        try {
            $sql = $db->prepare("INSERT INTO `customers`(`name`,`vkn`, `companyName`, `unvan`, `vergiNumber`, `IDnumber`, `companyAddress`, `email`, `phoneNumber`,`vergidairesi`,`musterinumara`,`userId`) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

            $sql->execute([$customername, $vkn, $companyname, $unvan, $taxnumber, $tc, $address, $email, $phoneNumber, $vergidairesi, $musterinumarasıhidden, $userId["id"]]);

            if ($sql) {
                header("location: musteriler.php");
                exit;
            } else {
                $error = "<div class='alert alert-danger'>An error occurred while saving data.</div>";
            }
        } catch (Exception $e) {
            error_log("Caught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $error = "<div class='alert alert-danger'>An error occurred: " . $e->getMessage() . "</div>";
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineLogic-satışlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   
    <link href="css\app.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            margin-bottom: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div class="d-flex justify-content-end">
        <nav class="navbar d-flex justify-content-end p-2 w-100 pe-5 bg-black bg-opacity-75">
            <div class="align-items-center d-flex justify-content-between">
                <div class="text-white d-flex align-items-center gap-2" style="position:absolute; left:250px">
                    <p class="m-0">Deneme sürenizin bitmesine <?php //echo $visitcount ?> gün kaldı</p>
                    <a href="#" class="border rounded-circle border-3 p-2 bg-white"><i class="fa-solid fa-gift fs-4"></i></a>
                </div>
                <div class="">
                    <a href="#" class="border bg-white border-2 rounded-pill p-2"><i class="fa-brands fa-rocketchat"></i> Canlı Destek</a>
                </div>
                <ul class="d-flex gap-3 m-0 justify-content-center align-items-center">
                    <li><a href="#" class="text-white">Yardım</a></li>
                    <li><a href="logout.php" class="text-white">Çıkış</a></li>
                    <li class="d-flex align-items-center gap-2">
                        <div class="bg-secondary text-white border border-2 p-2 rounded-circle shadow justify-content-center align-items-center">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="text-white fs-5">
                            <?php echo $_SESSION["username"]; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div id="sidebar">
        <div class="sidebar-wrapper active shadow" style="height: 100vh; width: 200px;">
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark"> <i class="fa-solid fa-folder-plus fs-3 text-secondary"></i>Yeni Müşteri OLuştur</p>
    </div>
    <hr>
    <div class="">
        <form method="POST" id="form" enctype="multipart/form-data">

 <div class="container">
        <div class="row  d-flex justify-content-end align-items-center  ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <?php echo $tt = empty($error) ? "" : $error  ;
                    
                    ?>
                </div>


            </div>
        
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fas fa-briefcase fs-5"></i>
                            <label for="musterinumarası" class="form-label w-25  me-5 pe-4 ps-4">Müşteri Numarası</label>
                            <input class="form-control" type="text" id="musterinumarası" name="musterinumarası" disabled>
                            <input type="hidden" id="musterinumarasıhidden" name="musterinumarasıhidden">
                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-regular fa-user fs-5"></i>
                            <label for="customername" class="form-label w-25  me-5 pe-4 ps-4">Müşteri adı <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                           
                              <input type="text" class="form-control" id="customername" name="customername" value="<?php echo htmlspecialchars($customername); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="companyname" class="form-label w-25  me-5 pe-4 ps-4">Şirket adı <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                        
                                      <input type="text" class="form-control" id="companyname" name="companyname" value="<?php echo htmlspecialchars($companyname); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-heading fs-5"></i>
                            <label for="unvan" class="form-label w-25  me-5 pe-4 ps-4">Şirket ünvanı <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                            
                    <input type="text" class="form-control" id="unvan" name="unvan" value="<?php echo htmlspecialchars($unvan); ?>" placeholder="Elmas Yazılım ve Danışmanlık Hizmetleri Sanayi ve Ticaret Anonim Şirketi">
                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-rectangle-list fs-5"></i>
                            <label for="taxnumber" class="form-label w-25  me-5 pe-4 ps-4">Vergi Numarası </label>
                    <input type="text" class="form-control" id="taxnumber" name="taxnumber" value="<?php echo htmlspecialchars($taxnumber); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fas fa-briefcase fs-5"></i>
                            <label for="vergidairesi" class="form-label w-25  me-5 pe-4 ps-4">Vergi Dairesi </label>
                    <input type="text" class="form-control" id="vergidairesi" name="vergidairesi" value="<?php echo htmlspecialchars($vergidairesi); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fas fa-briefcase fs-5"></i>
                            <label for="vkn" class="form-label w-25  me-5 pe-4 ps-4">VKV</label>
                                    <input type="text" class="form-control" id="vkn" name="vkn" value="<?php echo htmlspecialchars($vkn); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-id-card fs-5"></i>
                            <label for="tc" class="form-label w-25  me-5 pe-4 ps-4">TC numarası</label>
                             <input type="text" class="form-control" id="tc" name="tc" value="<?php echo htmlspecialchars($tc); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-map fs-5"></i>
                            <label for="address" class="form-label w-25  me-5 pe-4 ps-4">Şirket Adresi</label>
                              <textarea class="form-control" type="text" id="address" name="address"> <?php echo htmlspecialchars($address); ?> </textarea>

                        </div>
                 </div>

                </div>


            </div>
           
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-envelope fs-5"></i>
                            <label for="email" class="form-label w-25  me-5 pe-4 ps-4">Email <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-phone fs-5"></i>
                            <label for="phoneNumber" class="form-label w-25  me-5 pe-4 ps-4">Cep Telefon Numarası <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>">

                          
                        </div>

                    </div>

                </div>


            </div>
          
                    <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex  align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                    <div class="bottons">
                        <a href="musteriler.php" class="btn btn-secondary">Vazgeç</a>
                        <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75"> Kaydet</button>

                    </div>

                    </div>

                </div>


            </div>
              </div>
                </form>
        </div>


</body>
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
$("#musterinumarası").val( randomNumbers);
$("#musterinumarasıhidden").val( randomNumbers);
});


</script>
<script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
</html>
