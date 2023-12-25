
<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount=0;


?>
<script>
localStorage.setItem("mytime", Date.now());
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineLogic-satışlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
        <link href="css\app.css" rel="stylesheet">

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
                        <a href="#" class="text-primary"> FineLogic  </a>
                    </div>


                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">

                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item  justify-content-center">
                        <a href="dashbaordPage.php"
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            >
                            <i class="fa-solid fa-house-user text-primary"></i> Ana Sayfa
</a>
                      

                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a
                            class="btn  btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start   "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse2" >
                            <i class="fa-solid fa-arrow-down-short-wide text-primary"></i> Satışlar
</a>
                        <div class="  <?php  $url= $_SERVER['REQUEST_URI'];  $bb=trim($url,"/"); echo $result = str_contains($bb,"accounting")? "collapsed":""?> " id="home-collapse2">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="satislar.php"  class="link-primary  <?php  $url= $_SERVER['REQUEST_URI'];  $bb=trim($url,"/"); echo $result = str_contains($bb,"accounting")? "text-primary shadow-sm":"text-secondary"?> fs-5 p-2 rounded ">Satışlar</a></li>
                                <li><a href="#" class="link-primary  text-secondary fs-5 p-2 rounded ">Faturalar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Muşteriler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Satış Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Tahsilatlar Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Gelir Gider Raporu</a></li>
                            


                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse3">
                            <i class="fa-solid fa-arrow-up-from-bracket text-primary"></i> Giderler
</a>
                        <div class="collapse  " id="home-collapse3">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary  text-secondary fs-5 p-2 rounded ">Gider Listesi</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Tedarikçiler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Çalışanlar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Giderler Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Ödemeler Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">KDV raporu</a></li>


                            </ul>
                        </div>

                    </li>

                    <li class="sidebar-item  justify-content-center">
                        <a 
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse-subi">
                            <i class="fa-regular fa-money-bill-1 text-primary"></i> Nakit
</a>
                        <div class="collapse  " id="home-collapse-subi">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kasa Ve Bankalar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Çekler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kasa/Banka Raporu</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Nakit Akışı Raporu</a></li>

                            </ul>
                        </div>

                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a 
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapses">
                            <i class="fa-solid fa-cubes text-primary"></i> Stok
</a>
                        <div class="collapse  " id="home-collapses">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Hizmet ve ürünler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Depolar</a></li>
                                <li><a href="#" class="link-primary d-flex  text-secondary fs-5 p-2 rounded "> <span class="text"> Dolaplar Arası Transfer</span> </a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Giden İrsaliyeler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Gelen İrsaliyeler</a></li>

                            </ul>
                        </div>

                    </li>
                  
                    <li class="sidebar-item  justify-content-center">
                        <a 
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse4">
                            <i class="fa-solid fa-gears text-primary"></i> Ayarlar
</a>
                        <div class="collapse  " id="home-collapse4">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Firma Bilgileri </a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kategori Ve etiketler</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Dolaplar Arası Transfer</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Kullanicilar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Yazdırma Şablonları</a></li>

                            </ul>
                        </div>

                    </li>
                  
                    <li class="sidebar-item  justify-content-center">
                        <a href="logout.php"
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            >
                            <i class="fa-solid fa-right-from-bracket text-primary"></i> Çıkış
</a>

                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class=" d-flex justify-content-end ">
        <nav class="navbar d-flex justify-content-end  p-2  ps-5 pe-5 bg-secondary">
          <div class="div">

          </div>

            <div class=" align-items-center">
                <ul class="d-flex gap-3 m-0 justify-content-center align-items-center ">


                    <li><a href="#" class="text-white"> Hakkımızda 
        
          <?php
        





?>


</a></li>


                    <li><a href="#" class="text-white">Yardım</a></li>
                    <li><a href="logout.php" class="text-white">Çıkış</a></li>
                    <li>
                        <div
                            class=" bg-secondary text-white border border-2 p-2 shadow justify-content-center align-items-center">
                            <i class="fa-solid fa-user"></i>
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
        <div class="div">
            <!-- header-->
            <div class="div d-flex justify-content-end ">
               <div class="div"> <span> Satışlar </span> </div>
               <div class="div"> <span> Satışlar </span> </div>
               <div class="div"> <span> Satışlar </span> </div>
            </div>
            <!-- body-->
            <!-- footer-->
        </div>
    </div>
</body>
</html>