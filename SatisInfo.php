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

echo count($productsArray) . "nnnnnnnnuuuuuu";
 
?>

<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);




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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sidebars/">
    <link href="/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5"><i class="fa-solid fa-circle-info text-secondary fs-3"></i>Sipariş Detay</p>
    </div>

    <div>
        <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row  d-flex justify-content-end align-items-center ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <?php echo $tt = empty($error) ? "" : $error;

                    ?>
                </div>


            </div>
            <div class="row  d-flex justify-content-end align-items-center mt-5 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <div class="d-flex align-items-center gap-5 w-75">
                        <i class="fa-solid fa-note-sticky fs-5 "></i>
                        <label for="bbb" class="form-label w-50  "> Sıpariş Numarası</label>
                        <input type="text" id="ordernumber" disabled name="ordernumber" class="form-control w-100"
                            value="<?php echo $sellings["productcode"] ?>">
                        <input type="hidden" id="ordernumber_hidden" name="ordernumber_hidden"
                            value="<?php echo $sellings["productcode"] ?>">

                    </div>
                    <div class="bottons">
                        <a href="satislar.php" class="btn btn-secondary">Geri Dön <i class="fa-solid fa-circle-left"></i></a>
                        

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

                            <select class="form-select ms-5" disabled name="musteri" id="musteri">
                                <option selected value="">Muşteri seç..</option>
                                <?php

                                $sql = $db->prepare("select * from customers ");
                                $sql->execute();
                                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <option value="<?php echo $row["costomer"]; ?>" <?php echo $ex = $sellings["costomer"] == $row["id"] ? "selected" : "" ?>>
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
                            <select class="form-select ms-5" disabled name="paymentType" id="paymentType"
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
                                        <input type="hidden"  name="birim[]" id="hiddenbirim1"
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
                                    <select class="form-select" name="iskonto[]" disabled onchange="productchanged(<?php echo $i ?>)"
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
                                        <select class="form-select" name="kdv[]" disabled onchange="productchanged(<?php echo $i ?>)"
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

            <div class="row  d-flex justify-content-center align-items-center mt-3  ">
                <div class="col-md-3  d-flex justify-content-start align-items-center w-75   ps-5 ms-4  gap-3">

                    <div class="d-grid align-items-center justify-content-center w-50 pt-3">

                        <label class="form-label pe-5 me-5 w-100 "> teklif durumu </label>
                        <div class="input-group mb-3">

                            <select class="form-select" name="durum" id="durum" disabled>
                                <option value="waiting" <?php echo $tv = $sellings["status"] == "waiting" ? "selected" : "" ?>>Onay bekliyor</option>

                                <option value="true" <?php echo $tv = $sellings["status"] == "true" ? "selected" : "" ?>>
                                    fatura oluşturuldu</option>
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
    
    <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="sidebars.js"></script>







</body>


</html>