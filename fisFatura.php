
<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
    exit;
}

require "db.php";

$userIdQuery = $db->prepare("SELECT id FROM users WHERE username = ?");
$userIdQuery->execute([$_SESSION["username"]]);
$userId = $userIdQuery->fetch(PDO::FETCH_ASSOC);

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
                class="fa-solid fa-folder-plus fs-3 text-secondary"></i> Yeni Fiş / Fatura</p>
    </div>
    <hr>
    <div class="">
       

            <div class="container ps-5 ms-5 ">
            <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row mt-3  d-flex justify-content-center align-items-center ps-5 ms-5"  >
                    
                       <div class="w-75 ps-5 ms-5"  id="errordive">
                       
                       </div>
                    
                    </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-regular fa-user fs-5"></i>
                                <label for="kayitIsmi" class="form-label w-25 me-5 pe-4 ps-4">Kayıt İsmi </label>
                                <input class="form-control" type="text" id="kayitIsmi" name="kayitIsmi">
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
                                        <option value="<?php echo $vendors; ?>">
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
                                    value="<?php echo date("d.m.Y")?>">
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
                                <input class="form-control" type="number" id="fis_fatura_number" name="fis_fatura_number"
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
                                    <option value="tl" selected> <i class="fa-solid fa-turkish-lira-sign"></i> Türk Lirası</option>
                                    <option value="dolar"> <i class="fa-solid fa-dollar-sign"></i>ABD doları</option>
                                    <option value="euro"><i class="fa-solid fa-euro-sign"></i> Euro</option>
                                    <option value="sterlin"><i class="fa-solid fa-sterling-sign"></i> İngiliz sterlini</option>
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
                                    <option value="Ödenecek" selected>Ödenecek</option>
                                    <option value="Ödendi">Ödendi</option>
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
                                value="<?php echo date("d.m.Y")?>">
                            </div>
                        </div>
                    </div>
                </div>
                
            <hr class="h-auto">

<div class="row justify-content-end align-items-center mt-3" id="addnewrow">
    <div class="col-md-12 d-flex justify-content-end gap-2 me-5">
        <div class="col-md-2">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Hizmet /Ürün</label>
                <select class="form-select" onchange="productchanged(1)" name="urunhizmet1" id="urunhizmet1">
                    <option selected value=""></option>
                    <?php
                    require "db.php";
                    $sql = $db->prepare("SELECT * FROM products WHERE userId=? ");
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
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Birim</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="birim1" id="birim1" disabled>
                    <input type="hidden" class="form-control" name="hiddenbirim1" id="hiddenbirim1" >
                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Miktar</label>
                <input type="number" class="form-control" id="miktar1" onchange="productchanged(1)"
                    name="miktar1" value="">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">BR.fiyat</label>
                <input type="number" class="form-control" disabled id="birimfiyat1" name="birimfiyat1"
                    placeholder="0,00">
                <input type="hidden" class="form-control"  id="hiddenbirimfiyat1" name="hiddenbirimfiyat1"
                    placeholder="0,00">
            </div>
        </div>
        <div class="col-md-1">
            <div class="d-grid align-items-center">
                <label class="form-label pe-2">Iskonto</label>
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
            <div class="d-grid align-items-center ">
                <label class="form-label pe-2">KDV</label>
                <div class="input-group">
                    <label class="input-group-text me-2" for="kdv1">%</label>
                    <select class="form-select" name="kdv1" onchange="productchanged(1)" id="kdv1">
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
                    <input type="number" class="form-control" disabled id="fiyat1" name="urunfiyat1"
                        placeholder="0,00">
                    <input type="hidden" class="form-control"  id="hiddenfiyat1" name="hiddenurunfiyat1"
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
</div>


                <div class="row d-flex justify-content-end align-items-center mt-3">
                <div class="col-md-9 d-flex align-items-center me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                        <div class="bottons">
                            <a href="giderler.php" class="btn btn-secondary">Vazgeç</a>
                            <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75"> Kaydet</button>

                        </div>
                    </div>
                </div>
            </div>
         

            <div class="row d-grid justify-content-center w-50 align-items-center ms-5 ps-5 mt-3">
            <hr>
            <div class="col-md-4">
                
            <div class="mb-3 d-flex justify-content-start align-items-center">
                <label class="form-label text-nowrap mb-0">ARA TOPLAM : </label>
                <input type="text" disabled id="gross" class="border-0">
                <input type="text" hidden value="" id="gross_hidden" name="gross"class="border-0">
              
            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0">TOPLAM KDV : </label>
                <input type="text" disabled id="toplamkdv"  class="border-0">
                <input type="text" hidden id="toplamkdv_hidden" name="toplamkdv" class="border-0">
            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0">GENEL TOPLAM : </label>
                <input type="text" disabled id="toplamtutar"class="border-0">
                <input type="text" hidden id="toplamtutar_hidden" name="toplamtutar" class="border-0">

            </div>
        </div>
        <hr>
        <div class="col-md-4">
            <div class="mb-3  d-flex justify-content-start">
            <label class="form-label text-nowrap mb-0"> TOPLAM Iskonto: </label>
                <input type="text" disabled id="toplamiskonto"class="border-0">
                <input type="text" hidden id="hiddentoplamiskonto" name="toplamiskonto" class="border-0">

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

            if (option.value === "Ödenecek") {
                dropdown.style.backgroundColor = "#e6e8eb"; // Light gray background
            } else if (option.value === "Ödendi") {
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

<!-- <script>
   $(document).ready(function(){
      $('#form').on('submit', function(event){
         event.preventDefault(); // Prevent the form from refreshing the page
         var formData = $(this).serialize(); // Serialize the form data

         $.ajax({
            type: 'POST',
            url: 'fisFaturaPhpCode.php',
            data: formData,
            dataType: 'json',
            success: function(response){
                if (response.success) {
                  window.location.href = 'giderler.php'; 
               } else {
                console.log("response.message"+response.message+"-response.success:"+response.success);
                  $('#errordive').html("<div class='alert alert-danger'>" + response.message + "</div>"); // Display the error message
               }
            
            },
            error: function(){
               alert('There was an error!');
            }
         });
      });
   });
</script> -->
<script>
   $(document).ready(function(){
      $('#form').on('submit', function(event){
         event.preventDefault(); // Prevent the form from refreshing the page
         var formData = $(this).serialize(); // Serialize the form data
         formData += '&submit=submit'; 
         $.ajax({
            type: 'POST',
            url: 'fisFaturaPhpCode.php',
            data: formData,
            dataType: 'json', // Expect JSON response from the server
            success: function(response){
               if (response.success) {
                  window.location.href = 'giderler.php'; 
               } else {
                  console.log("response.message: " + response.message + " - response.success: " + response.success);
                  $('#errordive').html("<div class='alert alert-danger'>" + response.message + "</div>"); // Display the error message
               }
            },
            error: function(xhr, status, error){
               console.error("AJAX error: ", status, error);
               $('#errordive').html("<div class='alert alert-danger'>There was an error!</div>"); // Display a generic error message
            }
         });
      });
   });
</script>

</body>


</html>