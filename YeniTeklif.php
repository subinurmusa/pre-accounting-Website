<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;


?>
<script>
    if (localStorage.getItem("startdate")) {
        if (localStorage.getItem("startdate") != "<?php echo date("y-m-d") ?>") {
        <?php $visitcount = $visitcount - 5; ?>console.log("girdi")
        }
    } else {
        localStorage.setItem("startdate", "<?php echo date("y-m-d") ?>");

    }
    console.log(localStorage.getItem("startdate"));
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineLogic-satışlar </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <link href="css\app.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>


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
        width: 82%;
    }

    /* a:hover{


} */
</style>

<body>

    <div id="sidebar">
        <div class="sidebar-wrapper active shadow ">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo"> <!--insert  fake logo  -->
                        <a href="#" class="text-primary"> FineLogic </a>
                    </div>


                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">

                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item  justify-content-center">
                        <a href="dashbaordPage.php"
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  ">
                            <i class="fa-solid fa-house-user text-primary"></i> Ana Sayfa
                        </a>


                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a class="btn  btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start   "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse2">
                            <i class="fa-solid fa-arrow-down-short-wide text-primary"></i> Satışlar
                        </a>
                        <div class="collapse " id="home-collapse2">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="satislar.php"
                                        class="link-primary  text-secondary fs-5 p-2 rounded ">Satışlar</a>
                                </li>
                                <li><a href="#" class="link-primary  text-secondary fs-5 p-2 rounded ">Faturalar</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Muşteriler</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Satış Raporu</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Tahsilatlar
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Gelir Gider
                                        Raporu</a></li>



                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse3">
                            <i class="fa-solid fa-arrow-up-from-bracket text-primary"></i> Giderler
                        </a>
                        <div class="collapse  " id="home-collapse3">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary  text-secondary fs-5 p-2 rounded ">Gider Listesi</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Tedarikçiler</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Çalışanlar</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Giderler
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Ödemeler
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">KDV raporu</a>
                                </li>


                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse-subi">
                            <i class="fa-regular fa-money-bill-1 text-primary"></i> Nakit
                        </a>
                        <div class="collapse  " id="home-collapse-subi">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kasa Ve
                                        Bankalar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Çekler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kasa/Banka
                                        Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Nakit Akışı
                                        Raporu</a></li>

                            </ul>
                        </div>

                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapses">
                            <i class="fa-solid fa-cubes text-primary"></i> Stok
                        </a>
                        <div class="collapse  " id="home-collapses">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Hizmet ve
                                        ürünler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Depolar</a></li>
                                <li><a href="#" class="link-primary d-flex  text-secondary fs-5 p-2 rounded "> <span
                                            class="text"> Dolaplar Arası Transfer</span> </a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Giden
                                        İrsaliyeler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Gelen
                                        İrsaliyeler</a></li>

                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse4">
                            <i class="fa-solid fa-gears text-primary"></i> Ayarlar
                        </a>
                        <div class="collapse  " id="home-collapse4">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Firma Bilgileri
                                    </a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kategori Ve
                                        etiketler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Dolaplar Arası
                                        Transfer</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kullanicilar</a>
                                </li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Yazdırma
                                        Şablonları</a></li>

                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a href="logout.php"
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  ">
                            <i class="fa-solid fa-right-from-bracket text-primary"></i> Çıkış
                        </a>

                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class=" d-flex justify-content-end ">
        <nav class="navbar d-flex justify-content-end  p-2  ps-5 pe-5 bg-secondary">


            <div class=" align-items-center d-flex justify-content-between">
                <div class="text-dark d-flex align-items-center gap-2" style="position:absolute; left:60px">
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

    <div class="container">
        <form method="POST">
            <div class="row  d-flex justify-content-end align-items-center mt-5 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                    <div class="d-flex align-items-center gap-5 w-75">
                        <i class="fa-solid fa-note-sticky fs-5 "></i>
                        <label for="bbb" class="form-label w-50  "> Teklif Açıklaması</label>
                        <input type="text" id="bbb" class="form-control w-100">
                    </div>
                    <div class="bottons">
                        <a href="#" class="btn btn-secondary">Vazgeç</a>
                        <button type="submit" class="btn btn-primary opacity-75"> Kaydet</button>

                    </div>
                </div>


            </div>
            <hr>
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-5">
                            <i class="fa-regular fa-address-book fs-5"></i>
                            <label for="costomer" class="form-label pe-5 me-5">Müsteri </label>
                            <input type="text" id="costomer" class="form-control w-100 ms-5 ps-5">

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
                            <input type="text" id="tarih" class="form-control w-100 ms-5 ps-5">

                        </div>

                    </div>

                </div>


            </div>

            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-solid fa-bell fs-5"></i>
                            <label for="tarih" class="form-label w-25  me-5 pe-4 ps-4">Vade Tarihi </label>
                            <select class="form-select ms-5" aria-label="Default select example">
                                <option selected>Aynı Gün</option>
                                <option value="1">7 gÜN </option>
                                <option value="2">14 Gün</option>
                                <option value="3">30 Gün</option>
                                <option value="3">60 Gün</option>
                            </select>

                        </div>

                    </div>

                </div>


            </div>
            <hr class="h-auto">
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                        <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                            <i class="fa-solid fa-pen-nib fs-5"></i>
                            <label for="tarih" class="form-label w-25  me-5 pe-5 ps-4">Teklif Koşulları </label>
                            <textarea class="form-control w-75"
                                placeholder="Teklif Geçerli olduğu sure , ödeme şartları vb. bilgiler için bu alanı kullanabilirsiniz "
                                id="floatingTextarea2" style="height: 100px"></textarea>


                        </div>

                    </div>

                </div>


            </div>
            <hr class="h-auto">
            <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                <div class="col-md-3  d-flex justify-content-center align-items-center w-100  me-5 pe-5 ps-5 gap-3">

                    <div class="d-grid align-items-center  w-100 justify-content-end me-5 pe-5 w-100">

                        <label class="form-label pe-5 me-5"> Hizmet /Ürün </label>
                        <input type="text" class="form-control w-100 ps-5">

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label class="form-label pe-5 me-5 w-100"> FiYAT</label>
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="0,00">
                            <span class="input-group-text"><i class="fa-solid fa-turkish-lira-sign"></i></span>

                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 pt-3">

                        <label class="form-label pe-5 me-5 w-100"> VERGi</label>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">KDV</label>
                            <select class="form-select" id="inputGroupSelect01">
                                <option selected>%20</option>
                                <option value="1">%50</option>
                                <option value="2">%80</option>
                                <option value="3">%100</option>
                            </select>
                        </div>

                    </div>
                    <div class="d-grid align-items-center justify-content-center w-25 ">

                        <label for="costomer" class="form-label pe-5 me-5 w-100"> TOPLAM </label>
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="0,00">
                            <span class="input-group-text opacity-25"><i
                                    class="fa-solid fa-turkish-lira-sign text-dark"></i></span>

                        </div>

                    </div>


                </div>


            </div>
    </div>




    </form>
    </div>
</body>

</html>