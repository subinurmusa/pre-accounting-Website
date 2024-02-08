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

$musteriId = isset($_GET["id"]) ? $_GET["id"] : null;
require "db.php";
$sqld = $db->prepare("SELECT * FROM customers where id = ? ");
$sqld->execute([$musteriId]);
$customers = $sqld->fetch(PDO::FETCH_ASSOC);

?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here
   
        // Your database operations, form processing, etc.
        $customername = isset($_POST['customername']) ? $_POST['customername'] : null;
        $companyname = isset($_POST['companyname']) ? $_POST['companyname'] : null;
        $taxnumber = isset($_POST['taxnumber']) ? $_POST['taxnumber'] : 0;
        $tc = isset($_POST['tc']) ? $_POST['tc'] : 0;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : 0;
        $vergidairesi = isset($_POST['vergidairesi']) ? $_POST['vergidairesi'] : null;
        $musterinumarasıhidden = isset($_POST['musterinumarasıhidden']) ? $_POST['musterinumarasıhidden'] : 0;
        //var_dump($_POST);
        if (empty($customername)||empty($companyname)) {
            $error = "<div class='alert alert-danger'> Şirket Adı / müşteri Adı alanlarda yanlış veya eksik bilgi vardır </div>";
            
        }
        else if(empty($email)||empty($tc)||empty($vergidairesi)){
            $error = "<div class='alert alert-danger'> Email  doldurulması zorunlu alanlardır </div>";
            
        }
        else if (empty($address)||empty($taxnumber)||empty($phoneNumber)){
            $error = "<div class='alert alert-danger'>telefon ve Vergi Numarası ve Address   Gerekli alanlardır </div>";
            
        }else{
         
            require "db.php"; // Prepare and execute the SQL statement
            $sql = $db->prepare("UPDATE `customers` SET `name`=?,`companyName`=?,`vergiNumber`=?,`IDnumber`=?,`companyAddress`=?,`email`=?,`phoneNumber`=?,`vergidairesi`=?,`musterinumara`=? WHERE id=?");
        
            $sql->execute([$customername, $companyname, $taxnumber, $tc, $address, $email, $phoneNumber,$vergidairesi,$musterinumarasıhidden,$musteriId]);
        
            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: musteriler.php");
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark"> <i class="fa-solid fa-pen-nib fs-3 text-secondary"></i> Müşteri Bilgilerini Düzenle</p>
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
                            <input class="form-control" type="text" id="musterinumarası" value="<?php echo $customers["musterinumara"]?>" name="musterinumarası" disabled>
                            <input type="hidden" id="musterinumarasıhidden" value="<?php echo $customers["musterinumara"]?>" name="musterinumarasıhidden">
                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-regular fa-user fs-5"></i>
                            <label for="customername" class="form-label w-25  me-5 pe-4 ps-4">Müşteri adı </label>
                           
                             <input class="form-control" type="text" id="customername" name="customername" value="<?php echo $customers["name"]?>">
                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="companyname" class="form-label w-25  me-5 pe-4 ps-4">Şirket adı </label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <input class="form-control" type="text" id="companyname" name="companyname" value="<?php echo $customers["companyName"]?>">
                          
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
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <input class="form-control" type="text" id="taxnumber" name="taxnumber" value="<?php echo $customers["vergiNumber"]?>">
                          
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
                            <input class="form-control" type="text" id="vergidairesi" value="<?php echo $customers["vergidairesi"]?>" name="vergidairesi">
                          
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
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <input class="form-control" value="<?php echo $customers["IDnumber"]?>" type="text" id="tc" name="tc">
                          
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
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <textarea class="form-control" type="text" id="address"  name="address">  <?php echo $customers["companyAddress"]?> </textarea>

                        </div>
                 </div>

                </div>


            </div>
           
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-envelope fs-5"></i>
                            <label for="email" class="form-label w-25  me-5 pe-4 ps-4">Email</label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <input class="form-control" type="email" id="email" name="email" value="<?php echo $customers["email"]?>">
                          
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-phone fs-5"></i>
                            <label for="phoneNumber" class="form-label w-25  me-5 pe-4 ps-4">Cep Telefon Numarası</label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                             <input class="form-control" type="number" id="phoneNumber" value="<?php echo $customers["phoneNumber"]?>" name="phoneNumber">
                          
                        </div>

                    </div>

                </div>


            </div>
          
                    <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex  align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                    <div class="bottons">
                        <a href="satislar.php" class="btn btn-secondary">Vazgeç</a>
                        <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75"> Düzenle</button>

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



</script>









</body>


</html>