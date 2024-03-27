<?php
session_start();

if (empty ($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
require "db.php";

// Fetch selling data from the database
$sql = "SELECT * FROM selling";
$stmt = $db->prepare($sql);
$stmt->execute();
$sellingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total sales count
$totalSalesCount = count($sellingData);

// Calculate total revenue
$totalRevenue = array_sum(array_column($sellingData, 'totalPrice'));

// Calculate total gross revenue
$totalGrossRevenue = array_sum(array_column($sellingData, 'totalGrossPrice'));

// Calculate total discount
$totalDiscount = array_sum(array_column($sellingData, 'totaliskonto'));

// Calculate total tax
$totalTax = array_sum(array_column($sellingData, 'totalkdv'));

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
        cursor: pointer;
    }

    .detay:hover {}

    body {
        background-color: white;
    }

    ul {
        list-style: none;

    }

    .navbar {
        width: 82%;
        z-index: 1;
    }



    /* a:hover{


} */
</style>


<body>




    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <div class="col-md-12">
                <nav class="navbar d-flex justify-content-end p-2 w-100 pe-5 bg-black bg-opacity-75">
                    <div class="align-items-center d-flex justify-content-between">
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
                                    <?php echo $_SESSION["name"]; ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- End Navbar -->

            <!-- Sidebar -->
            <div class="col-md-3">
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
            </div>
            <!-- End Sidebar -->

            <!-- Content -->
            <div class="">
    <div class="div">
        <!-- header-->
        <div class="card-header bg-primary text-white text-center">
            Satış Raporu
        </div>
        <!-- body-->
        <div class="col-md-6">
            <!-- Content -->
            <div class="mt-4 m-5 py-1 ps-5 ms-5 d-flex justify-content-end container">
                <div class="">

                    <!-- Table for selling report -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Satış Tarihi</th>
                                <th scope="col">Müşteri</th>
                                <th scope="col">Satılan Ürün</th>
                                <th scope="col">Ödeme Türü</th>
                                <th scope="col">Toplam Fiyat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sellingData as $sale): ?>
                            <tr>
                                <td><?php echo $sale['date-added']; ?></td>
                                <td><?php echo $sale['costomer']; ?></td>
                                <td><?php echo $sale['products']; ?></td>
                                <td><?php echo $sale['paymentType']; ?></td>
                                <td><?php echo $sale['totalPrice']; ?> TL</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End of selling report table -->

                </div>
            </div>
            <!-- End Content -->
        </div>

        <!-- footer-->
    </div>
</div>


        </div>
    </div>
</body>

</html>