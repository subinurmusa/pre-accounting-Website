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
                        <div class="  <?php $url = $_SERVER['REQUEST_URI'];
                        $bb = trim($url, "/");
                        echo $result = str_contains($bb, "accounting") ? "collapsed" : "" ?> " id="home-collapse2">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="satislar.php"
                                        class="link-primary  <?php $url = $_SERVER['REQUEST_URI'];
                                        $bb = trim($url, "/");
                                        echo $result = str_contains($bb, "accounting") ? "text-primary shadow-sm" : "text-secondary" ?> fs-5 p-2 rounded ">Satışlar</a>
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

    <div class="">
        <div class="div">
            <!-- header-->
            <div class="div d-flex ps-5 bg-secondary bg-opacity-50 gap-5 align-items-center"
                style="position:relative; height:40px;">
                <div class="div text-white fs-4" style="position:absolute;left:370px;"> <span> Satışlar </span> </div>

                <div class="div text-white" style="position:absolute;right:1px;"> <span><i
                            class="fa-solid fa-rainbow"></i></span> </div>
            </div>
            <!-- body-->
            <div class="mt-4 m-5 py-1 d-flex justify-content-end ">
                <section class="section" style="width:1100px">
                    <div class="row" id="table-striped-dark">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header p-0"><!--bbbbbuuuuuu-->
                                    <nav class="navbar navbar-light p-4 bg-light w-100">
                                        <div class="container-fluid d-flex align-items-center">
                                            <form class="d-flex m-0 " method="POST">
                                                <input class="form-control me-2 w-100" type="search"
                                                    placeholder="Search" aria-label="Search">
                                                <button class="btn btn-outline-success rounded-pill text-dark"
                                                    type="submit">Search</button>
                                                <!--another dropdown here -->
                                                <!-- <div class="dropdown">
  <button class="btn btn-outline-success rounded-pill text-dark dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
    Filtreler
  </button>
  <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownMenuButton2">
    <li><a class="dropdown-item active" href="#">Action</a></li>
    <li><a class="dropdown-item" href="#">Another action</a></li>
    <li><a class="dropdown-item" href="#">Something else here</a></li>  
    <li><a class="dropdown-item" href="#">Separated link</a></li>
  </ul>
</div>  -->
                                            </form>

                                            <div>
                                                <a href="#" class="btn bg-success bg-opacity-25 text-dark">Cevap
                                                    beklenenler</a>
                                                <a href="#"
                                                    class="btn bg-success bg-opacity-25  text-dark">Onaylalanlar</a>
                                                <a href="#"
                                                    class="btn bg-success bg-opacity-25  text-dark">reddedilenler</a>
                                                <a href="#" class="btn bg-success bg-opacity-25   text-dark">tümü</a>
                                            </div>
                                            <div>
                                                <button class="btn btn-outline-success text-dark">Yeni Teklif
                                                    Oluştur</button>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                                <div class="card-content mt-3">

                                    <!-- table strip dark -->
                                    <div class="table-responsive">
                                        <?php
                                        $listIds = array(
                                            (object) [
                                              'teklifAciklama' => 'bilgisayar leptop',
                                              'FaturalamaDurumu' => 'true',
                                              'Oluşturma Tarihi' => '12-28-2023',
                                              'totalMonay' => '10000'

                                            ]
                                            
                                          );
                                        // $listIds = array("teklifAciklama"=>"bilgisayar leptop", "FaturalamaDurumu"=>"True", "Oluşturma Tarihi"=>"12-28-2023", "totalMonay"=>"10000");
                                        $jsonIds = json_encode($listIds);
                                        ?>
                                        <script>
                                            localStorage.setItem("myIds", '<?= $jsonIds ?>');
                                                                                  
                                        </script>
                                        <table class="table table-striped table-dark mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Teklif Açıklaması</th>
                                                    <th>Faturalama Durumu</th>
                                                    <th>Oluşturma Tarihi</th>
                                                    <th>Toplam Teklif Tutarı </th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                            <script>
  // Retrieve data from local storage
  var satislar = localStorage.getItem("myIds");

  // Parse the string into a JavaScript object
  satislar = JSON.parse(satislar);

  console.log(satislar);


    for (let index = 0; index < satislar.length; index++) {
                document.write(`
                <tr>
                                                    <td>${satislar[index].teklifAciklama}</td>
                                                    <td class="d-grid ">
                                                        <span class="text-start text-secondary">
                                                        ${satislar[index].FaturalamaDurumu === "true" ? "Faturalama Oluşturuldu" : "faturalama Oluşturulmadı"}
                                                        </span> 
                                                        <span id="ember3950" class=" fw-bold text-secondary">
                                                            <i class="fa-regular fa-file-lines fs-5 text-secondary"></i>

                                                            <svg width="35" height="50">
                                                                <circle cx="18" cy="25" r="10" stroke-width="2"
                                                                    fill="lightgreen" />
                                                                  
                                                            </svg> 
                                                            ${satislar[index].FaturalamaDurumu === "true" ? "kabuledildi" : "Cevap Bekleniyor"}
                                                           
                                                        </span>
                                                    </td>
                                                    <td class="text-bold-500">${satislar[index]['Oluşturma Tarihi']}</td>
                                                    <td><i class="fa-solid fa-turkish-lira-sign"></i> ${satislar[index].totalMonay}</td>

                                                </tr>
                `);
            }
    
 </script>
                                             
                                         
                                              
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- footer-->
        </div>
    </div>
</body>

</html>