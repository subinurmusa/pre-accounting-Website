


<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

require "db.php";

$sqluserid = $db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId = $sqluserid->fetch(PDO::FETCH_ASSOC);

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
<script>
   $(document).ready(function(){
      $('#form').on('submit', function(event){
         event.preventDefault(); // Prevent the form from refreshing the page
         var formData = $(this).serialize(); // Serialize the form data

         $.ajax({
            type: 'POST',
            url: 'siparisOlustur.php',
            data: formData,
            success: function(response){
                if (response.success) {
                  window.location.href = 'satislar.php'; // Redirect to satislar.php on success
               } else {
                  $('#errordive').html("<div class='alert alert-danger'>" + response.message + "</div>"); // Display the error message
               }
              // $('#errordive').html(response); // Display the response from the PHP script
            },
            error: function(){
               alert('There was an error!');
            }
         });
      });
   });
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
          //  calculateTotal();
          dycnum = dycnum - 1;
          productchanged(0);

        }
        //3th function
   
       

        //calculateAllPrice function

     


      
  

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
            <select class="form-select" onchange="productchanged(${dycnum})" name="urunhizmet${dycnum}" id="urunhizmet${dycnum}">
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
                <input type="text" class="form-control" name="birim${dycnum}" id="birim${dycnum}" disabled>
                <input type="hidden" name="hiddenbirim${dycnum}" id="hiddenbirim${dycnum}">
                <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
            </div>
        </div>
        <div class="col-lg-1 col-md-2 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Miktar</label>
            <input type="number" class="form-control" id="miktar${dycnum}" onchange="productchanged(${dycnum})" name="miktar${dycnum}" placeholder="0,00">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">BR.fiyat</label>
            <input type="number" class="form-control" disabled id="birimfiyat${dycnum}" name="birimfiyat${dycnum}" placeholder="0,00">
            <input type="hidden" id="hiddenbirimfiyat${dycnum}" name="hiddenbirimfiyat${dycnum}">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Iskonto</label>
            <select class="form-select" name="iskonto${dycnum}" onchange="productchanged(${dycnum})" id="iskonto${dycnum}">
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
                <select class="form-select" name="kdv${dycnum}" onchange="productchanged(${dycnum})" id="kdv${dycnum}">
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
                    <input type="hidden" class="form-control"  id="hiddenfiyat${dycnum}" name="hiddenurunfiyat${dycnum}"
                    placeholder="0,00">
                        <input type="number" class="form-control" disabled id="fiyat${dycnum}" name="urunfiyat${dycnum}" placeholder="0,00">
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
    </script>
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

    <div class=" container d-flex align-items-center w-50 justify-content-center mt-5 ms-5">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5"> <i class="fa-solid fa-note-sticky fs-3 text-secondary"></i>Yeni Sipariş OLuştur</p>
    </div>

   
    <div class="">
        <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row  d-flex justify-content-end align-items-center  ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  " id="errordive">
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
                    <div class="d-flex align-items-center justify-content-center w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="costomer" class="form-label w-25  me-5 pe-4 ps-4">Müsteri <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                            <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->

                            <select class="form-select ms-5" name="musteri" id="musteri">
                                <option selected value="">Muşteri seç..</option>
                                <?php
                                require "db.php";
                                $sql = $db->prepare("select * from customers where userId=? ");
                                $sql->execute([$userId["id"]]);
                                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

<option <?php echo $musteri == $row["name"] ? "selected" : ""; ?> value="<?php echo $row["id"]; ?>">
    <?php echo $row["name"]; ?>
</option>


                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                      <!--   <div class="d-flex align-items-end justify-content-center w-50 gap-4">  
                            <button class="btn btn-outline-secondary">ekel yeni muşteri</button>
                        </div> -->

                    </div>

                </div>


            </div>

            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-solid fa-calendar-days fs-5 me-2"></i>
                            <label for="tarih" class="form-label w-25 me-5 ps-3">Düzenleme Tarihi <i class="fa-solid fa-asterisk fs-6 text-danger"></i></label>
                            <input type="text" name="tarih" id="tarih" class="form-control w-100 ms-5 ps-5" value="<?php echo htmlspecialchars($tarih); ?>">

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
                                <option value="1" selected>Nakit</option>
                                <option value="2">Havale </option>
                                <option value="3">Çek</option>
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
                            <input class="form-control ms-5" name="vadetarihi" id="vadetarihi" value="<?php echo htmlspecialchars($vade_tarihi); ?>"
                               >

                            </input>

                        </div>

                    </div>

                </div>


            </div>

            <hr class="h-auto">


       
            

            <div class="container  mt-3 mb-5">
    <div class="row justify-content-end">
       
        <div class="col-lg-10 col-md-11 border p-3"id="addnewrow"><i class="fa-solid fa-asterisk fs-6 text-danger"></i>
        <div class="row addnewrow ll" >
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label">Hizmet / Ürün</label>
                    <select class="form-select" onchange="productchanged(1)" name="urunhizmet1"
                                    id="urunhizmet1">
                                    <option selected value=""></option>
                                    <?php
                                    require "db.php";
                                    $sql = $db->prepare("select * from products where userId=?");
                                    $sql->execute([$userId["id"]]);
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <option value="<?php echo $row["id"];?>">
                                            <?php echo $row["productname"];?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">Birim</label>
            <div class="input-group">
                <input type="text" class="form-control" name="birim${dycnum}" id="birim1" disabled>
                <input type="hidden" name="hiddenbirim1" id="hiddenbirim1">
                <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
            </div>
        </div>
                <div class="col-lg-1 col-md-2 col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label">Miktar</label>
                    <input type="number" class="form-control" id="miktar1" onchange="productchanged(1)" name="miktar1" value="">
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0">
            <label class="form-label">BR.fiyat</label>
            <input type="number" class="form-control" disabled id="birimfiyat1" name="birimfiyat1" placeholder="0,00">
            <input type="hidden" id="hiddenbirimfiyat1" name="hiddenbirimfiyat1">
        </div>
                <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0"> <!-- Adjusted column width for Iskonto -->
                    <label class="form-label">Iskonto</label>
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
                <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0"> <!-- Adjusted column width for Vergi -->
                    <label class="form-label">Vergi(KDV)</label>
                    <select class="form-select" name="kdv1" onchange="productchanged(1)" id="kdv1">
                    <option selected value="20">%20</option>
                                        <option value="18">%18</option>
                                        <option value="10">%10</option>
                                        <option value="8">%8</option>
                                        <option value="1">%1</option>
                                        <option value="0">0</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 mb-3 mb-sm-0"> <!-- Adjusted column width for Fiyat -->
                    <label class="form-label">Fiyat</label>
                    <div class="input-group">
                    <input type="hidden" class="form-control"  id="hiddenfiyat1" name="hiddenurunfiyat1"
                    placeholder="0,00">
                        <input type="number" class="form-control" disabled id="fiyat1" name="urunfiyat1" placeholder="0,00">
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
                                <option  value="waiting">Onay bekliyor</option>

                                <option  value="true">fatura oluşturuldu</option>
                                <option  value="false">fatura oluşturulmadı</option>


                            </select>
                        </div>

                    </div>

                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="toplamtekliftutar" disabled
                                id="toplamtutar" placeholder="0,00">
                                <input type="hidden" name="hiddentoplamtekliftutar" id="hiddentoplamtutar">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> BÜRÜT TUTAR </label>
                        <div class="input-group">
                            <input type="number" class="form-control " value="0" name="bürüt" disabled id="bürüt"
                                placeholder="0,00" >
                                <input type="hidden" value="0" name="hiddenbürüt"   id="hiddenbürüt" >
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM ISKONTO </label>
                        <div class="input-group">
                            <input type="number" class="form-control "  name="toplamiskonto" disabled
                                id="toplamiskonto" placeholder="0,00">
                                <input type="hidden"  name="hiddentoplamiskonto" id="hiddentoplamiskonto" >
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100 text-nowrap"> TOPLAM KDV </label>
                        <div class="input-group">
                            <input type="number" class="form-control "  name="toplamkdv" disabled
                                id="toplamkdv" placeholder="0,00">
                                <input type="hidden" name="hiddentoplamkdv" 
                                id="hiddentoplamkdv" >
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>
                   
                            <div class="d-grid align-items-center justify-content-center w-25 ">

<button class="btn btn-outline-primary text-nowrap mt-4" type="button" id="addRowButton"
onclick="loadDoc()">Ekle</button>

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
                    $("#vadetarihi").
                    datepicker($.datepicker.regional["tr"]);
    });
}) 
</script>
 

</body>


</html>

