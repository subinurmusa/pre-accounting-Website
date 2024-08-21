<?php

require "db.php";
session_start();
$sqluserid=$db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId=$sqluserid->fetch(PDO::FETCH_ASSOC);





$invoiceid = isset($_GET["invoiceId"]) ? $_GET["invoiceId"] : null;

$sql = $db->prepare("SELECT * FROM invoice WHERE id=? and userId=?");
$sql->execute([$invoiceid,$userId["id"]]);

$invoicelist = $sql->fetch(PDO::FETCH_ASSOC);
if(!$invoicelist){
  echo'<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>geçersiz bilgi</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
  <link href="css\app.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
  </head>
  <body>
      <div class="row   d-flex justify-content-center align-items-center mt-5 ">
          <div class="col-md-6">
              <div class="card p-3 shadow d-flex align-items-center justify-content-center">
              <div>
                  <p class="text-danger">
                      yanlış veya geçersiz sayfa ya yönlendirildiniz
                  </p>
                  </div>
              </div>
          </div>
      </div>
      
  </body>
  </html>';
  exit;
}
//selling items
$customergid = $invoicelist["customerid"];
$sqlcus = $db->prepare("SELECT * FROM customers WHERE id=?");
$sqlcus->execute([$customergid]);

$customerlist = $sqlcus->fetch(PDO::FETCH_ASSOC);
// getting the company info about current user of this system 
$sqlcomInfo = $db->prepare("SELECT * FROM `companyinfo` WHERE userId=? ; ");
$sqlcomInfo->execute([$userId["id"]]);
$urrentCompanyInfo=$sqlcomInfo->fetch(PDO::FETCH_ASSOC);

// selling , items 
$sellingid = $invoicelist["sellingId"];
$sqlitems = $db->prepare("SELECT * FROM selling WHERE id=?");
$sqlitems->execute([$sellingid]);
$sellinglist = $sqlitems->fetch(PDO::FETCH_ASSOC);
$jsonproduct = $sellinglist["products"];
$productsArray = json_decode($jsonproduct, true);




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineLogic-Fatura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <link href="css\app.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
</head>

<style>
    @media print {
        body * {
            visibility: hidden;
           /*  margin: 0;
    color: #000;
    background-color: #fff; */
        }
      /*   article {
  column-width: 17em;
  column-gap: 4em;
} */
        .print-container, .print-container * {
            visibility: visible;
           
            
        }
        @page {
                size: A4;
                margin: 0;
            }
        
        #print-btn {
            display: none; /* or visibility: hidden; */
            
        }
        .add-pe-5{
            padding-right: 80px;
        }
    }
</style>

<body class="">
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

    <div class="container ps-5 add-pe-5 " style="font-size: 10px;">
    <div class="ps-5 ms-5 w-100 ">
    <div class="print-container pe-5 me-5">
        <div class="row mt-5">
        <hr style="height:1px; width:700px;  color: black;">
            <div class="col-md-10 mt-1 d-grid  justify-content-start">
               
                <p>
                    <?php echo $urrentCompanyInfo["companyName"]." ".$urrentCompanyInfo["ticariUnvan"] ?>  <br>
                    <?php echo $urrentCompanyInfo["address"] ?>
                    <br>
                        <!-- SK. Giyim SAN.TİC.MRK.sit B blok NO: 3B 216 kapın No: <br> 34490 Başakşehir İstanbul turkiye
                        <br> -->
                        Tel:  <?php echo $urrentCompanyInfo["phone"] ?> Fax: <br>
                        Web Sitesi:<br>
                        E-Posta:  <?php echo $_SESSION["email"]?> <br>
                        Vergi dairesi:  <?php echo $urrentCompanyInfo["vergiDairesi"] ?><br>
                        <?php echo $urrentCompanyInfo["vergiNum"]==null?"":"Vergi Numarası: ".$urrentCompanyInfo["vergiNum"]."<br>"; ?>
                        <?php echo $urrentCompanyInfo["vkn"]==null?"":"VKN: ".$urrentCompanyInfo["vkn"]."<br>"; ?>

                </p>
               
            </div>
            <hr style="height:1px; width:700px;  mb-1 color: black;">
            
        </div>
       
        <div class="row d-flex  align-items-center justify-content-between ">
      
            <div class="col-md-4 ps-0 d-grid aligh-items-center  justify-content-start">
            <hr style="height:1px; width:650px;   color: black;">
               <div class="text">
               <p >
                   <b> SAYIN </b> <br>
                   <?php echo $customerlist["companyName"].$customerlist["unvan"] ?><br>
                   <?php echo $customerlist["companyAddress"] ?> <br>
                    Web Sitesi:<br>
                    E-Posta: <?php echo $customerlist["email"] ?><br>
                    Tel: <?php echo $customerlist["phoneNumber"] ?>Fax: <br>
                    Vergi Dairesi: <?php echo $customerlist["vergidairesi"] ?><br>
                    <?php echo $customerlist["vkn"]==null?"":" Vergi Numarası: ".$customerlist["vergiNumber"]."<br>"; ?>

                    <?php echo $customerlist["vkn"]==null?"":"VKN: ".$customerlist["vkn"]."<br>"; ?>
                </p>
               </div>
              
               <hr style="height:1px; width:650px;  color: black;">
            <p> <b> ETTN:  </b> <?php echo $invoicelist["InvoiceNumber"] ?></p>
            </div>
      
            <div class="col-md-4 mt-1 d-flex align-items-center justify-content-end">
    <div class="d-flex flex-column justify-content-end" style="height:100px;">
        <table class="m-2 table-bordered border-dark mt-auto  text-nowrap" style=" width:70px; ">
            <tbody >
                <tr>
                    <td >
                     <b>   Özelleştirme No:</b>
                    </td>
                    <td >
                        TR1.2
                    </td>
                </tr>
                <tr>
                    <td >
                    <b>   Sanaryo:</b>
                    </td>
                    <td>
                        EARSİVEFATURA
                    </td>
                </tr>
                <tr>
                    <td >
                    <b>   Fatura Tipi:</b>
                    </td>
                    <td >
                        SATIŞ
                    </td>
                </tr>
                <tr>
                    <td >
                    <b>   Fatura  No:</b>
                    </td>
                    <td >
                    <?php echo $invoicelist["InvoiceNumber"] ?>
                    </td>
                </tr>
                <tr>
                    <td >
                    <b>   Fatura  Tarihi :</b>
                    </td>
                    <td >
                    <?php echo $invoicelist["InvoiceDate"] ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

        
        </div>
        <div class="row mb-3 w-100">
        
            <table class=" table-bordered border-dark mt-auto w-100 " >
                <thead >
                    <tr>
                        <th>
                            Sıra No
                        </th>
                        <th>
                           Hizmet ve Ürün
                        </th>
                        <th>
                            Ürün Birimi
                        </th>
                        <th>
                            Ürün Miktarı
                        </th>
                        <th>
                            Ürün Birim Fiyat
                        </th>
                        <th>
                            İskonto 
                        </th>
                         <th>
                            Vergi
                        </th>
                         <th>
                            Ürün Fiyatı
                        </th>
                    </tr>
                </thead>
                <tbody>

                <?php



$i = 1;
foreach ($productsArray as $row_product) {

    ?>
   <tr>
                        <td>
                        <?php echo $i; ?>
                        </td>
                        <td>
                        <?php
    $sqlp = $db->prepare("SELECT productname FROM products WHERE id=? ");
    $sqlp->execute([$row_product["productname"]]);
    $row = $sqlp->fetch(PDO::FETCH_ASSOC); // Fetch the data after executing the statement
    echo $row['productname']; // Access the column value from the fetched row
    ?>
                        </td>
                        <td>
                        <?php echo $row_product["birim"]; ?>
                        </td>
                        <td>
                        <?php echo $row_product["miktar"]; ?>
                        </td>
                        <td>
                        <?php echo $row_product["birimfiyat"]; ?>
                        </td>
                        <td>
                        <?php echo $row_product["iskonto"]; ?>
                        </td>
                        <td>
                        <?php echo $row_product["kdv"]; ?>
                        </td>
                        <td>
                        <?php echo $row_product["urunfiyat"]; ?>
                        </td>
                    </tr>
    <?php $i++; } ?>
                 
                   
                </tbody>
            </table>
        </div>
        <div class="row  mb-3 w-100">
        <table class=" table-bordered border-dark mt-auto w-100 " >
                <thead >
                    <tr>
                        <th>
                            Toplam Fiyat
                        </th>
                                              
                        <th>
                            Toplam Bürüt Fiyat
                        </th>
                        <th>
                            Toplam İskonto
                        </th>
                        <th>
                            Toplam KDV
                        </th>
                       
                    </tr>
                </thead>
                <tbody>
                    <tr>
                       
                        <td>
                        <?php echo $sellinglist["totalPrice"]; ?>
                        </td>
                        <td>
                        <?php echo $sellinglist["totalGrossPrice"]; ?>
                        </td>
                        <td>
                        <?php echo $sellinglist["totaliskonto"]; ?>
                        </td>
                        <td>
                        <?php echo $sellinglist["totalkdv"]; ?>
                        </td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
        <div class="row mt-5 mb-3">
        
     <button onclick="window.print()" class="btn btn-secondary" id="print-btn"> Yazdır <i class="fa-solid fa-print fs-4"></i> </button>
    </div>
        </div>
    </div>
        
       
    </div>
</body>

</html> 