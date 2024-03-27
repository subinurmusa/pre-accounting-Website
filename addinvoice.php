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

$sellingid = isset($_GET["id"]) ? $_GET["id"] : null;

$sqld = $db->prepare("SELECT * FROM selling where id = ? ");
$sqld->execute([$sellingid]);
$sellings = $sqld->fetch(PDO::FETCH_ASSOC);
$jsonData = $sellings["products"];
$productsArray = json_decode($jsonData, true);
//customers
$sqld = $db->prepare("SELECT * FROM customers where id = ? ");
$sqld->execute([$sellings["costomer"]]);
$customers = $sqld->fetch(PDO::FETCH_ASSOC);

$productarray= [];
?>

<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        $invoicenumber = isset($_POST['invoicenumber']) ? $_POST['invoicenumber'] : null;
        $invoiceDate = isset($_POST['invoiceDate']) ? $_POST['invoiceDate'] : null;
        $SendingComName = isset($_POST['SendingComName']) ? $_POST['SendingComName'] : null;

        $sendingComAddress = isset($_POST['sendingComAddress']) ? $_POST['sendingComAddress'] : null;
        $sendingComPhone = isset($_POST['sendingComPhone']) ? $_POST['sendingComPhone'] : null;
        $sendingComEmail = isset($_POST['sendingComEmail']) ? $_POST['sendingComEmail'] : null;
        $SendingComTaxNumber = isset($_POST['SendingComTaxNumber']) ? $_POST['SendingComTaxNumber'] : null;
        

        if (empty($invoicenumber)||empty($invoiceDate)) {
            $error = "<div class='alert alert-danger'>Fatura Bilgileri Doldurulmalıdır </div>";

        } else if (empty($SendingComName)||empty($sendingComAddress)||empty($sendingComPhone)||empty($sendingComEmail)||empty($SendingComTaxNumber)) {
            $error = "<div class='alert alert-danger'>Gönderici Bilgilerini Doldurulması zorunludur</div>";
        } else {
            //  var_dump($_POST);
          
            $sql = $db->prepare("INSERT INTO `invoice`( `sellingId`, `InvoiceDate`, `InvoiceNumber`, `sendingComName`, `sendingComAddress`, `sendingComPhone`, `sendingComEmail`, `SendingComTaxNumber`,`customerid`) VALUES (?,?,?,?,?,?,?,?,?)");

            $sql->execute([$sellingid, $invoiceDate, $invoicenumber, $SendingComName, $sendingComAddress, $sendingComPhone, $sendingComEmail, $SendingComTaxNumber,$sellings["costomer"]]);

            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                // urun stok miktarından urunleri çıkart
               
                foreach ($productsArray as $productlist) {
                    $sqlpro=$db->prepare("SELECT *  from  products where id=?");
                    $sqlpro->execute([$productlist["productname"]]);
                    $stokamount=$sqlpro->fetch(PDO::FETCH_ASSOC);
                    $calculation= $stokamount["stokmiktari"]-$productlist["miktar"];

                
                    // now update it with the new stokmiktar amount
                    $sqlproup=$db->prepare("UPDATE products set  stokmiktari =?   where id=?");
                    $sqlproup->execute([$calculation,$productlist["productname"]]);

               
                }
                $sqlproducts = $db->prepare("SELECT * FROM products");
                $sqlproducts->execute();
                $allproducts = $sqlproducts->fetchAll(PDO::FETCH_ASSOC);
                
                $productarray = []; // Corrected variable name
                
                foreach ($allproducts as $product) {
                    echo $product["stokmiktari"];
                    if ($product["stokmiktari"] < 5) {
                        echo "11111";
                        $Productdetailsformail = [
                            'productname' => $product["productname"],
                            'stockamount' => $product["stokmiktari"],
                            'productphoto' => $product["productphoto"],
                            'productcode' => $product["productcode"]
                        ];
                        $productarray[] = $Productdetailsformail;
                    }
                }
                
                
                
                if (!empty($productarray)) {
                  
                    $textmessage = ""; // Initialize variable correctly
                    
                    // Constructing HTML for product details
                    foreach ($productarray as $product) { 
                        $textmessage .= "<div style='margin-bottom: 20px;'>";
                        $textmessage .= "<p style='font-weight: bold; margin-bottom: 5px;'>Ürün: " . $product["productname"] . "</p>";
                        $textmessage .= "<p>Stok Seviyesi: <strong>" . $product["stockamount"] . "</strong></p>";
                        $textmessage .= "</div>";
                    }
                    
                    require "mailsender.php";
                
                    $mail->addAddress($_SESSION["email"], $_SESSION["name"]); // Add a recipient
                    $mail->addReplyTo('info@example.com', 'Information');
                
                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = '<b>Kritik stok seviyesi</b>';
                    
                    // Body of the email
                    $mail->Body = '
                        <html>
                        <head>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    background-color: #f2f2f2;
                                    margin: 0;
                                    padding: 0;
                                    color: #333; /* Setting text color to black */
                                }
                                .container {
                                    padding: 20px;
                                    background-color: #e6e7e8;
                                    border-radius: 10px;
                                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                    color: #333; /* Setting text color to black */
                                }
                                h2 {
                                    color: #333;
                                    margin-top: 0;
                                }
                                p {
                                    margin: 0;
                                    line-height: 1.5;
                                    color: #333; /* Setting text color to black */
                                }
                                strong {
                                    font-weight: bold;
                                }
                                a {
                                    color: #333 !important; /* Setting link color to black */
                                    text-decoration: underline; /* Adding underline to links */
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <h2>Depo Lardaki Ürünlerin Stok Miktarları Güncellenmesi Gerekiyor</h2>
                                <p>Seviyesi Kritik Olan Ürünler:</p>
                                '.$textmessage.'
                                <p>Lütfen stok seviyelerini güncelleyin.</p>
                            </div>
                        </body>
                        </html>';
                    
                    // Plain text version of the email
                    $mail->AltBody = 'Depo Lardaki Ürünlerin Stok Miktarları Güncellenmesi Gerekiyor. Seviyesi Kritik Olan:' . $textmessage . '. Lütfen stok seviyelerini güncelleyin.';
                
                    $mail->send();
                }
                
                



               
               header("location: satisfatura.php");
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
    <div class=" d-flex align-items-center w-50 justify-content-center mt-5 ms-5">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5"> Yeni Fatura Oluştur</p>
    </div>

    <div>
        <form method="POST" id="form" enctype="multipart/form-data" >
            
            <div class="row  d-flex justify-content-end align-items-center ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <?php echo $tt = empty($error) ? "" : $error;

                    ?>
                </div>


            </div>
            <hr>
            <!--fatura bilgileri-->
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                           <h5>Fatura Bilgileri</h5>
                        </div>

                    </div>

                </div>


            </div>
            <hr>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="invoicenumber" class="form-label w-25  me-5 pe-4 ps-4">Fatura Numarası </label>
                           
                            <input type="text" id="invoicenumber"  name="invoicenumber" class="form-control ms-5"
                            value="">
                        <input type="hidden" id="invoicenumber_hidden" name="invoicenumber_hidden"
                            value="">
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="invoiceDate" class="form-label w-25 text-nowrap me-5 pe-4 ps-4">Fatura Oluşturma Tarihi </label>
                           
                            <input type="text" id="invoiceDate"  name="invoiceDate" class="form-control ms-5"
                            >
                        <input type="hidden" id="invoiceDate_hidden" name="invoiceDate_hidden"
                            value="">
                        </div>

                    </div>

                </div>


            </div>
            <hr>
             <!--gönderici bilgileri-->
             <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                           <h5>Gönderici Firma Bilgileri</h5>
                        </div>

                    </div>

                </div>


            </div>
            <hr>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="SendingComName" class="form-label w-25  me-5 pe-4 ps-4">Gönderici Şirket Adı</label>
                           
                            <input type="text" id="SendingComName"  name="SendingComName" class="form-control ms-5"
                            value="">
                        
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-start justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="sendingComAddress" class="form-label w-25  me-5 pe-4 ps-4">Gönderici Şirket Adresi</label>
                           
                            <textarea type="text" id="sendingComAddress"  name="sendingComAddress" class="form-control ms-5" cols="10" rows="5"></textarea>
                        
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="sendingComPhone" class="form-label w-25  me-5 pe-4 ps-4">Gönderici Şirket İletişim numarası</label>
                         
                            <input type="number" id="sendingComPhone"  name="sendingComPhone" class="form-control ms-5">
                        
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="sendingComEmail" class="form-label w-25 text-nowrap  me-5 pe-4 ps-4">Gönderici Şirket  Email</label>
                           
                            <input type="email" id="sendingComEmail"  name="sendingComEmail" class="form-control ms-5">
                        
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="SendingComTaxNumber" class="form-label w-25   me-5 pe-4 ps-4">Gönderici Şirket  Vergi Numarası</label>
                           
                            <input type="number" id="SendingComTaxNumber"  name="SendingComTaxNumber" class="form-control ms-5">
                        
                        </div>

                    </div>

                </div>


            </div>
            <hr>
             <!-- Muşteri bilgileri-->
             <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                           <h5>Alıcı Muşteri  Bilgileri</h5>
                        </div>

                    </div>

                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                        <i class="fa-solid fa-hashtag fs-5"></i>
                            <label for="gettingComName" class="form-label w-25  me-5 pe-4 ps-4">Alıcı Muşteri Adı</label>
                           
                            <input type="text" id="gettingComName" disabled  name="gettingComName" class="form-control ms-5"
                            value="<?php echo  $customers["name"];?>">
                        
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
                             <input class="form-control ms-5" type="text" id="companyname" disabled name="companyname" value="<?php echo $customers["companyName"]?>">
                          
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
                             <input class="form-control ms-5" type="text" id="taxnumber" name="taxnumber" disabled value="<?php echo $customers["vergiNumber"]?>">
                          
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
                            <input class="form-control ms-5" type="text" id="vergidairesi" disabled value="<?php echo $customers["vergidairesi"]?>" name="vergidairesi">
                          
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
                             <input class="form-control ms-5" disabled value="<?php echo $customers["IDnumber"]?>" type="text" id="tc" name="tc">
                          
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
                             <textarea class="form-control ms-5" rows="5"type="text" disabled id="address"  name="address">  <?php echo $customers["companyAddress"]?> </textarea>

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
                             <input class="form-control ms-5" disabled type="email" id="email" name="email" value="<?php echo $customers["email"]?>">
                          
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
                             <input class="form-control ms-5" type="number" disabled id="phoneNumber" value="<?php echo $customers["phoneNumber"]?>" name="phoneNumber">
                          
                        </div>

                    </div>

                </div>


            </div>
           
            <hr>
             <!-- Sipariş bilgileri-->
             <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-start w-100 gap-4">
                           <h5>Oluşturulmuş Sıpariş Bilgileri</h5>
                        </div>

                    </div>

                </div>


            </div>
            <hr>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="costomer" class="form-label w-25  me-5 pe-4 ps-4">Sıpariş Numarası </label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->

                            <input type="text" id="ordernumber" disabled name="ordernumber" class="form-control ms-5"
                            value="<?php echo $sellings["productcode"] ?>">
                        <input type="hidden" id="ordernumber_hidden" name="ordernumber_hidden"
                            value="<?php echo $sellings["productcode"] ?>">
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
                            <select class="form-select ms-5" name="paymentType" disabled id="paymentType"
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
                            <input class="form-control ms-5" disabled name="vadetarihi" id="vadetarihi"
                                value=" <?php echo $sellings["vadetarihi"] ?>">

                            </input>

                        </div>

                    </div>

                </div>


            </div>

            <hr class="h-auto">


            <div class="container mt-3 mb-5 me-1">
                <div class="row addnewrow justify-content-center " id="addnewrow">
                    <!--  if there is one row add one  -->

                 
                    <?php



                    $i = 1;
                    foreach ($productsArray as $row_product) {

                        ?>
                        <div class="d-flex justify-content-center gap-5 ms-5 dive<?php echo $i; ?> ">
                            <div class="col-md-1 me-5">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5 text-nowrap">Hizmet /Ürün</label>
                                    <select class="form-select" disabled onchange="productchanged(<?php echo $i ?>)"
                                        name="urunhizmet[]" id="urunhizmet1">

                                        <?php

                                        $sql = $db->prepare("select * from products ");
                                        $sql->execute();
                                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <option value="<?php echo $row["id"] ?>" <?php echo $u = $row_product['productname'] == $row["id"] ? "selected" : "" ?>>
                                                <?php echo $row["productname"] ?>
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
                                        <input type="text" class="form-control" name="birim[]" id="birim1" disabled
                                            value="<?php echo $row_product["birim"]; ?>">
                                        <input type="hidden" class="form-control" name="birim[]" id="hiddenbirim1"
                                            value="<?php echo $row_product["birim"]; ?>">
                                        <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">Miktar</label>
                                    <input type="number" disabled class="form-control" id="miktar1"
                                        onchange="productchanged(<?php echo $i ?>)" name="miktar[]"
                                        value="<?php echo $v = $row_product["miktar"] == "" ? "1" : $row_product["miktar"] ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">BR.fiyat</label>
                                    <input type="number" class="form-control" disabled id="birimfiyat1" name="birimfiyat[]"
                                        placeholder="0,00" value="<?php echo $row_product["birimfiyat"] ?>">
                                    <input type="hidden" class="form-control" id="hiddenbirimfiyat1"
                                        name="hiddenbirimfiyat[]" value="<?php echo $row_product["birimfiyat"] ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">Iskonto</label>
                                    <select class="form-select" disabled name="iskonto[]" onchange="productchanged(<?php echo $i ?>)"
                                        id="iskonto1">
                                        <option value="0" <?php echo $tv = $row_product["iskonto"] == "0" ? "selected" : "" ?>>
                                            %0</option>
                                        <option value="1" <?php echo $tv = $row_product["iskonto"] == "1" ? "selected" : "" ?>>
                                            %1</option>
                                        <option value="3" <?php echo $tv = $row_product["iskonto"] == "3" ? "selected" : "" ?>>
                                            %3</option>
                                        <option value="5" <?php echo $tv = $row_product["iskonto"] == "5" ? "selected" : "" ?>>
                                            %5</option>
                                        <option value="8" <?php echo $tv = $row_product["iskonto"] == "8" ? "selected" : "" ?>>
                                            %8</option>
                                        <option value="10" <?php echo $tv = $row_product["iskonto"] == "10" ? "selected" : "" ?>>%10</option>
                                        <option value="15" <?php echo $tv = $row_product["iskonto"] == "15" ? "selected" : "" ?>>%15</option>
                                        <option value="17" <?php echo $tv = $row_product["iskonto"] == "17" ? "selected" : "" ?>>%17</option>
                                        <option value="20" <?php echo $tv = $row_product["iskonto"] == "20" ? "selected" : "" ?>>%20</option>
                                        <option value="25" <?php echo $tv = $row_product["iskonto"] == "25" ? "selected" : "" ?>>%25</option>
                                        <option value="28" <?php echo $tv = $row_product["iskonto"] == "28" ? "selected" : "" ?>>%28</option>
                                        <option value="30" <?php echo $tv = $row_product["iskonto"] == "30" ? "selected" : "" ?>>%30</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">Vergi</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="kdv">KDV</label>
                                        <select class="form-select" disabled name="kdv[]" onchange="productchanged(<?php echo $i ?>)"
                                            id="kdv1">
                                            <option value="20" <?php echo $tv = $row_product["kdv"] == "20" ? "selected" : "" ?>>%20</option>
                                            <option value="18" <?php echo $tv = $row_product["kdv"] == "18" ? "selected" : "" ?>>%18</option>
                                            <option value="10" <?php echo $tv = $row_product["kdv"] == "10" ? "selected" : "" ?>>%10</option>
                                            <option value="8" <?php echo $tv = $row_product["kdv"] == "8" ? "selected" : "" ?>>
                                                %8</option>
                                            <option value="1" <?php echo $tv = $row_product["kdv"] == "1" ? "selected" : "" ?>>
                                                %1</option>
                                            <option value="0" <?php echo $tv = $row_product["kdv"] == "0" ? "selected" : "" ?>>0
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="d-grid align-items-center">
                                    <label class="form-label pe-5 me-5">Fiyat</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" disabled id="fiyat1" name="urunfiyat[]"
                                            value="<?php echo $row_product["urunfiyat"] ?>">
                                        <input type="hidden" class="form-control" id="hiddenfiyat1" name="hiddenurunfiyat[]"
                                            value="<?php echo $row_product["urunfiyat"] ?>">
                                        <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <?php
                        $i++;
                    }

                    ?>
                </div>
            </div>

            <hr class="h-auto w-100">

            <div class="row  d-flex justify-content-end align-items-center mt-3 pe-5 me-5  ">
                <div class="col-md-3  d-flex justify-content-start align-items-center w-75  gap-3">

                    <div class="d-grid align-items-center justify-content-center w-100 pt-3">

                        <label class="form-label pe-5 me-5 w-100 "> teklif durumu </label>
                        <div class="input-group mb-3">

                            <select class="form-select " disabled name="durum" id="durum" style="width:190px;">
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
                  

                </div>


            </div>
            <hr style="height:10px">
            <div class="row p-3 d-flex justify-content-center align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-start w-75 align-items-center ps-5 ms-5 gap-5 ">
                    <div class="d-flex ps-5 ms-5 ">
                    <a href="satislar.php" class="btn btn-secondary">Vazgeç</a>

                    </div>
                    <div class=" d-flex justify-content-center align-items-center gap-2" >
                        
                       
                        <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75">
                            Fatura Oluştur</button>
                         <p class="fs-5 text-danger d-flex  p-0 m-0  "> Fatura oluşturulduktan sonra Ürün Sroklarında kalıcı güncellenme olucaktır  </p>
                 
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
                $("#tarih").datepicker($.datepicker.regional["tr"]);
                    $("#vadetarihi").datepicker($.datepicker.regional["tr"]);
                    $("#invoiceDate").datepicker($.datepicker.regional["tr"]);
                   
            });
        }) 
    </script>
    <script>
        var dycnum = <?php echo count($productsArray); ?>;

        function productchanged(indexl) {
            var urunhizmetValue = document.getElementsByName("urunhizmet[]")[2].value;
            console.log(urunhizmetValue + "urunhizmetValue ");

            var toplambirimfiyat = [];
            var toplamkdv = 0;
            var toplamiskonto = 0;
            var grossPrice = 0;

            console.log(dycnum + "-index number");
            var ajaxCalls = [];

            for (let index = 1; index <= dycnum; index++) {
                var productid = $("urunhizmet[]" + index + " option:selected").val();
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
            // calculateTotal();

        }
        //3th function
        function loadDoc() {

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