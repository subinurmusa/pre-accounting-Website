<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

?>


<?php

           

require "db.php"; // Assuming db.php contains your database connection code

$sql1 = $db->prepare("SELECT `id`, `itemName`, `category`, `date-added`, `price` FROM `selling` WHERE `date-added` = '" . date("y-m-d") . "';");
// $sql->bindParam(':date_added', date("Y-m-d"));
$sql1->execute();

$count = 0;
$countbuy = 0;

while ($result1 = $sql1->fetch(PDO::FETCH_ASSOC)) {
    $count += $result1["price"];
}

$sql = $db->prepare("SELECT `id`, `name`, `category`, `date-added`, `price` FROM `buying` WHERE `date-added` = '" . date("y-m-d") . "';");
// $sql->bindParam(':date_added', date("Y-m-d"));
$sql->execute();


$countbuy = 0;

while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
    $countbuy += $result["price"];
}
   function convertMonthToTurkishCharacter($date){
    $aylar = array(
        'January'    =>    'Ocak',
        'February'    =>    'Şubat',
        'March'        =>    'Mart',
        'April'        =>    'Nisan',
        'May'        =>    'Mayıs',
        'June'        =>    'Haziran',
        'July'        =>    'Temmuz',
        'August'    =>    'Ağustos',
        'September'    =>    'Eylül',
        'October'    =>    'Ekim',
        'November'    =>    'Kasım',
        'December'    =>    'Aralık',
        'Monday'    =>    'Pazartesi',
        'Tuesday'    =>    'Salı',
        'Wednesday'    =>    'Çarşamba',
        'Thursday'    =>    'Perşembe',
        'Friday'    =>    'Cuma',
        'Saturday'    =>    'Cumartesi',
        'Sunday'    =>    'Pazar',
        'Jan' => 'Oca',
        'Feb' => 'Şub',
        'Mar' => 'Mar',
        'Apr' => 'Nis',
        'May' => 'May',
        'Jun' => 'Haz',
        'Jul' => 'Tem',
        'Aug' => 'Ağu',
        'Sep' => 'Eyl',
        'Oct' => 'Eki',
        'Nov' => 'Kas',
        'Dec' => 'Ara'

    );
    return  strtr($date, $aylar);
}
$day = date('d');
$sMonth = convertMonthToTurkishCharacter(date("M"));
$one = 1;
$dataPoints1 = array(
    array("label" => "$day-$sMonth", "y" => "$countbuy"),
    array("label" => $day + (1) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (2) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (3) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (4) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (5) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (6) . "-" . $sMonth, "y" => 0)
);
$dataPoints2 = array(
    array("label" => "$day-$sMonth", "y" => "$count"),
    array("label" => $day + (1) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (2) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (3) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (4) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (5) . "-" . $sMonth, "y" => 0),
    array("label" => $day + (6) . "-" . $sMonth, "y" => 0)
);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: " Şirketinizin haftalık Nakıt akışı ",
                    
                    
                },
                axisY: {
                    includeZero: true,
                    title: "Para Akışı",
                    valueFormatString: "*",
                    interval: 0
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "right",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "Giderler",
                    indexLabel: "{y}",
                    yValueFormatString: "",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "column",
                    name: "Kazançlar",
                    indexLabel: "{y}",
                    yValueFormatString: "",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            function toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            }

        }
    </script>
    <link href="css\app.css" rel="stylesheet">

    <title>Document</title>
</head>

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


<body class="bg-primary   bg-opacity-50" >


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
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            >
                            <i class="fa-solid fa-house-user text-primary"></i> Ana Sayfa
</a>
                      

                    </li>
                    <li class="sidebar-item  justify-content-center">
                        <a
                            class="btn btn-toggle align-items-center rounded d-flex gap-3 fs-5 d-flex justify-content-start  "
                            data-bs-toggle="collapse" data-bs-target="#home-collapse2">
                            <i class="fa-solid fa-arrow-down-short-wide text-primary"></i> Satışlar
</a>
                        <div class="collapse  " id="home-collapse2">
                            <ul class="btn-toggle-nav list-unstyled text-secondary fw-normal pb-1 p-2 d-grid gap-2">
                                <li><a href="#" class="link-primary text-secondary  fs-5 p-2 rounded ">Satışlar</a></li>
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Faturalar</a></li>
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
                                <li><a href="#" class="link-primary text-secondary fs-5 p-2 rounded ">Dolaplar Arası Transfer</a></li>
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
  

    <div class="container mt-3 d-grid justify-content-end align-items-end ">
        <div class="row mt-5 mb-5 d-flex  text-center" style="gap:120px">
            <div class="fs-3 text-white"> Ön Muhasebe Programına Hoşgeldiniz</div>
        </div>
        <div class="row d-flex justify-content-center " style="gap:120px">
            <div class="col-md-2">
                <div class="card shadow bg-light  border-2 border border-secondary mb-3" style="width: 300px; height: 300px">
                    <div
                        class="card-header bg-info border border-secondary border-2 text-secondary fs-3 text-center p-0 ">
                        Günlük Tahsilatlar</div>
                    <div class="card-body text-secondary d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam ciro</h5>
                        <?php

                        require "db.php"; // Assuming db.php contains your database connection code
                        
                        $sql = $db->prepare("SELECT `id`, `itemName`, `category`, `date-added`, `price` FROM `selling` WHERE `date-added` = '" . date("y-m-d") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sql->execute();

                        $count = 0;
                        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $count += $result["price"];
                        }
                        // echo date("y-m-d");
                        if ($count > 0) {
                            $color = "blue";
                            $Mss = "Bol Kazançlar elde ediyorsunuz Haydi Devam";

                        } else {
                            $color = "lightgray";
                            $Mss = "bİraz daha Çlışın ";

                        }






                        ?>
                        <svg class="progress bg-transparent  " width="200" style="height: 120px;">
                            <circle class="progress-circle" cx="100" cy="60" stroke="<?php echo $color ?>" r="50"
                                fill="transparent" stroke-width="9" />
                            <text class="loading fs-3" fill="blue" x="100" y="60" alignment-baseline="middle"
                                text-anchor="middle">
                                <?php echo $count ?>
                            </text>
                        </svg>
                        <span class=" loading"></span>
                    </div>
                    <div class="card-footer bg-info border-2 border-secondary align-items-center d-flex p-2">
                        <?php echo $Mss ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow  border-2 border border-secondary mb-3" style="width: 300px; height: 300px">
                    <div
                        class="card-header bg-danger border border-secondary border-2 text-secondary fs-3 text-center p-0 ">
                        Günlük Giderler</div>
                    <div class="card-body text-white bg-danger  bg-opacity-50 d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam Gider</h5>
                        <?php

                        require "db.php"; // Assuming db.php contains your database connection code
                        
                        $sql = $db->prepare("SELECT `id`, `name`, `category`, `date-added`, `price` FROM `buying` WHERE `date-added` = '" . date("y-m-d") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sql->execute();

                        $count = 0;
                        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $count += $result["price"];
                        }
                        // echo date("y-m-d");
                        if ($count > 0) {
                            $color = "red";
                            $Mss = "Nakıt akışınızı Akıllaca Yönetin";

                        } else {
                            $color = "lightgray";
                            $Mss = "mantıklı bir yatırım İşinizi Geliştirebilir";
                        }

                        $db = null; // Close the database connection
                        



                        ?>
                        <svg class="progress bg-transparent" width="200" style="height: 120px;">
                            <circle class="progress-circle" cx="100" cy="60" stroke="<?php echo $color ?>" r="50"
                                fill="transparent" stroke-width="6" />
                            <text class="loading fs-3 text-secondary" fill="black" x="100" y="60"
                                alignment-baseline="middle" text-anchor="middle">
                                <?php echo $count ?>

                            </text>

                        </svg>
                        <span class=" loading"></span>
                    </div>
                    <div
                        class="card-footer bg-danger text-white border-2 border-secondary d-flex align-items-center justify-content-center  p-2">
                        <?php echo $Mss ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow bg-success bg-opacity-50  border-2 border border-secondary mb-3" style="width: 300px; height: 300px">
                    <div
                        class="card-header bg-success  border border-secondary border-2 text-white fs-3 text-center p-0 ">
                        Günlük Kazanç</div>
                    <div class="card-body text-white d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam Kazanç</h5>
                        <?php

                        require "db.php"; // Assuming db.php contains your database connection code
                        $buyingCount = 0;
                        $sellingCount = 0;
                        $total = 0;

                        $sql = $db->prepare("SELECT `id`, `itemName`, `category`, `date-added`, `price` FROM `selling` WHERE `date-added` = '" . date("y-m-d") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sql->execute();


                        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $sellingCount += $result["price"];
                        }
                        // echo date("y-m-d");
                        
                        //start calculating buying number
                        $sql = $db->prepare("SELECT `id`, `name`, `category`, `date-added`, `price` FROM `buying` WHERE `date-added` = '" . date("y-m-d") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sql->execute();


                        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $buyingCount += $result["price"];
                        }
                        // time to add all togather
                        
                        $total = $sellingCount - $buyingCount;
                        if ($total > 0) {
                            $color = "green";
                            $cheeringMs = "aynen Böyle Devam Edin ";
                        } else {
                            $color = "lightgreen";
                            $cheeringMs = "biraz daha çabalayın Başarabilirsiniz ";
                        }






                        ?>
                        <svg class="progress bg-transparent" width="200" style="height: 120px;">
                            <circle class="progress-circle" cx="100" cy="60" stroke="<?php echo $color ?>" r="50"
                                fill="transparent" stroke-width="6" />
                            <text class="loading fs-3" fill="<?php echo $color ?>" x="100" y="60"
                                alignment-baseline="middle" text-anchor="middle">
                                <?php echo $total ?>
                            </text>
                        </svg>
                        <span class=" loading"></span>
                    </div>
                    <div class="card-footer text-white bg-success border-2 border-secondary p-2 d-flex justify-content-center">
                        <?php echo $cheeringMs ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <hr class=" mt-5 bg-primary " style="height:30px ; ">
    <div class="container mt-2 d-grid justify-content-end pe-5 ms-5  ">
        <div id="chartContainer" style="height: 400px; width: 1000px;" class="ps-5 mt-5 mb-5 ms-3">

        </div>
    </div>

    </div>

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>