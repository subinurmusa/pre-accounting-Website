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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
 


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

    #invoice:hover::after {
        content: "Fatura Oluştur";
        position: fixed;
        /* Use fixed positioning to follow the cursor */
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        /* Adjust the positioning according to your needs */
        top: calc(var(--top) + 20px);
        /* Position the tooltip below the cursor */
        left: calc(var(--left) + 20px);
    }


    /* a:hover{


} */
</style>
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
</style>
<style>
    @media print{
        .navbar, #sidebar, .card-header {
            visibility: hidden;
            display: none;
        }
        @page {
                size: A4;
                margin-left: 0px;
            }
            .table{
                width: 100%;
            }

    }
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
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
            ?>
        </div>

    </div>

    <div class="">
        <div class="div">
            <!-- header-->
            <div class="div d-flex ps-5 mt-3 gap-5 align-items-center" style="position:relative; height:40px;">
                <div class="div text-black fs-3" style="position:absolute;left:280px;"> <span><h3>Satış Raporları</h3> </span> </div>

                <div class="div text-white" style="position:absolute;right:1px;"> <span><i
                            class="fa-solid fa-rainbow"></i></span> </div>
            </div>
            <!-- body-->
            <div class="mt-4 m-5 py-1 d-flex justify-content-end container">
                <section class="section" style="width:1100px">
                    <div class="row" id="table-striped-dark">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header p-0"><!--bbbbbuuuuuu-->
                                    <nav class="navbar navbar-light p-4 bg-light w-100">
                                        <div class="container-fluid d-flex align-items-center">
                                            <!-- <form class="d-flex m-0" method="GET">
                                                <select class="form-select w-75 me-1" name="category">
                                                    <option value="name">Müşteri İsmi</option>
                                                    <option value="orderNumber">Sipariş Numarası</option>
                                                                                                    
                                                </select>
                                                <input class="form-control me-2 w-100" type="search" placeholder="Ara"
                                                    aria-label="Search" name="search">
                                                <button class="btn btn-outline-primary rounded-pill text-dark"
                                                    type="submit">Ara</button>
                                            </form> -->
                                            <form class="d-flex m-0" method="GET">
    <select id="categorySelect" class="form-select w-50 me-1" name="category">
        <option value="name">Müşteri İsmi</option>
        <option value="orderNumber">Sipariş Numarası</option>
        <option value="date">Tarih</option> <!-- New option for date search -->
    </select>
    <input id="searchInput" class="form-control me-2 w-50" type="search" placeholder="Ara" aria-label="Search" name="search">
    <input id="dateInput" class="form-control me-2 w-50" type="text" placeholder="Tarih Seç"  name="date"  style="display: none;">
 
    <button class="btn btn-outline-primary rounded-pill text-dark" type="submit">Ara</button>
</form>

<script>
    // Get references to the select and input elements
    const categorySelect = document.getElementById('categorySelect');
    const searchInput = document.getElementById('searchInput');
    const dateInput = document.getElementById('dateInput');

    // Add event listener to the select element to toggle input visibility
    categorySelect.addEventListener('change', function() {
        // Check if the selected option is 'date'
        if (categorySelect.value === 'date') {
            // Hide the search input and show the date input
            searchInput.style.display = 'none';
            dateInput.style.display = 'block';
        } else {
            // Show the search input and hide the date input
            searchInput.style.display = 'block';
            dateInput.style.display = 'none';
        }
    });
</script>




                                            <div>
                                            <button onclick="exportToXLSX()" class="btn btn-secondary"> exel Dosya Aktar </button>
                                          
                                               <button onclick="window.print()" class="btn bg-primary bg-opacity-50   text-dark">Dışarı Aktar</button>
                                        
                                            </div>
                                           
                                        </div>
                                    </nav>
                                </div>
                                <div class="card-content mt-3">

                                    <!-- table strip dark -->
                                    <div class="table-responsive">


                                        <table class="table   mb-0">
                                            <thead class="table-primary   mb-0">
                                                <tr>
                                                    <th>Sipariş Kodu</th>
                                                    <th>Muşteri</th>
                                                    <th>Faturalama Durumu</th>
                                                    <th>Oluşturma Tarihi</th>
                                                    <th>Hizmet Ve Ürünler</th>
                                                    <th>Toplam Sipariş Tutarı </th>
                                                  

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                require "db.php";


                                                $searchTerm = isset($_GET["search"]) ? $_GET["search"] : null;
                                                $dateinput = isset($_GET["date"]) ? $_GET["date"] : null;
                                                $category = isset($_GET["category"]) ? $_GET["category"] : null;
                                               
                                                if  (!empty($searchTerm)|| !empty($dateinput) && !empty($category)) {
                                                    // Prepare the SQL query based on the selected category
                                                   
                                                    switch ($category) {
                                                        case 'name':
                                                            $sql_customerid = $db->prepare("SELECT id FROM customers WHERE name LIKE ?");
                                                            $sql_customerid->execute(["%" . $searchTerm . "%"]);
                                                            $searchTermRow = $sql_customerid->fetch(PDO::FETCH_ASSOC);
                                                            echo $searchTermRow["id"];

                                                            $stmt = $db->prepare("SELECT * FROM selling WHERE costomer = ? and status = 'true'");
                                                            $stmt->execute([$searchTermRow["id"]]);
                                                          /*   $ghgh = $stmt->fetch(PDO::FETCH_ASSOC);
                                                            echo $ghgh["name"]; */
                                                            //   $searchTerm = $stm->fetch(PDO::FETCH_ASSOC);
                                                            break;
                                                        case 'orderNumber':
                                                            $sql = "SELECT * FROM selling WHERE productcode LIKE :searchTerm and status = 'true';";
                                                            $stmt = $db->prepare($sql);
                                                            $stmt->execute(array(':searchTerm' => '%' . $searchTerm . '%'));
                                                            break;
                                                        case 'date':
                                                                $sql = "SELECT * FROM selling WHERE  `date-added` LIKE :searchTerm and status = 'true' ;";
                                                                $stmt = $db->prepare($sql);
                                                                $stmt->execute(array(':searchTerm' => '%' . $dateinput . '%'));
                                                                break;
                                                        default:

                                                            $sql = "SELECT * FROM selling  where status = 'true' ";
                                                            break;
                                                    }
                                                    $xlsxDataList = [['Sipariş Kodu', 'Muşteri', 'Fatura Durumu', 'oluşturma tarihi ', 'hizmet ve ürünler', 'toplam sıpariş tutarı']];

                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        // getting customer name
                                                        $sql_customer = $db->prepare("select * from customers where id=" . $row["costomer"]);
                                                        $sql_customer->execute();
                                                        $customer_row = $sql_customer->fetch(PDO::FETCH_ASSOC);
                                                        
                                                        // getting products 
                                                        $products = json_decode($row['products'], true);
                                                        $productDetails = "";
                                                        foreach ($products as $product) {
                                                            $sqlh = $db->prepare("select * from products where id = ?");
                                                            $sqlh->execute([$product['productname']]);
                                                            $productname = $sqlh->fetch(PDO::FETCH_ASSOC);
                                                            $productDetails .= $product['miktar'] . " " . $product['birim'] . " " . $productname["productname"] . "\n"; // Use "\n" instead of "<br>"
                                                        }
                                                    
                                                        // Fatura Durumu
                                                        $faturaDurumu = $row["status"] === "true" ? "Kabul Edildi" : ($row["status"] === "false" ? "Red Edildi" : ($row["status"] === "waiting" ? "Cevap Bekleniyor" : ""));
                                                    
                                                        $data = [
                                                            $row["productcode"],
                                                            $customer_row["name"],
                                                            $faturaDurumu,
                                                            $row["date-added"],
                                                            $productDetails,
                                                            $row["totalPrice"]
                                                        ];
                                                    
                                                        $xlsxDataList[] = $data;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row["productcode"]; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                
                                                                echo $customer_row["name"];
                                                                ?>
                                                            </td>
                                                            <td >
                                                              
                                                                <span id="ember3950" class=" text-nowrap fw-bold text-secondary">
                                                                    <i class="fa-regular fa-file-lines fs-5 text-secondary"></i>

                                                                    <svg width="35" height="50">
                                                                        <circle cx="18" cy="25" r="10" stroke-width="2" fill=" 
                                                                
                                                                <?php
                                                                echo $bb = $row["status"] === "true"
                                                                    ? "lightgreen"
                                                                    : ($row["status"] === "false" ? "#cc3333" : ($row["status"] === "waiting" ? "#f7e98e" : ""));
                                                                ?>
                                                                " />

                                                                    </svg>


                                                                    <?php
                                                                    echo $bb = $row["status"] === "true"
                                                                        ? "Kabul Edildi"
                                                                        : ($row["status"] === "false" ? "Red Edildi" : ($row["status"] === "waiting" ? "Cevap Bekleniyor" : ""));
                                                                    ?>

                                                                </span>
                                                            </td>
                                                            <td class="text-bold-500">
                                                                <?php echo $row["date-added"]; ?>
                                                            </td>
                                                            <td>
                                                            <?php
                                            $products = json_decode($row['products'], true);
                                            foreach ($products as $product) {
                                                echo  $product['miktar'] . " ";
                                                echo  $product['birim'] . " ";
                                                $sqlh = $db->prepare("select * from  products where id = ?");
                                                $sqlh->execute([$product['productname']]);
                                                $productname = $sqlh->fetch(PDO::FETCH_ASSOC);
                                                echo  $productname["productname"] . "<br>";
                                            }
                                            ?>

                                                            </td>
                                                            <td><i class="fa-solid fa-turkish-lira-sign"></i>
                                                                <?php echo $row["totalPrice"]; ?>
                                                            </td>
                                                          
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    $sql = $db->prepare("SELECT * FROM selling where status = 'true'");
                                                    $sql->execute();
                                                    // echo "1111111111111111111";
                                                    $xlsxDataList = [['Sipariş Kodu', 'Muşteri', 'Fatura Durumu', 'oluşturma tarihi ', 'hizmet ve ürünler', 'toplam sıpariş tutarı']];

                                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                                           // getting customer name
                                                                           $sql_customer = $db->prepare("select * from customers where id=" . $row["costomer"]);
                                                                           $sql_customer->execute();
                                                                           $customer_row = $sql_customer->fetch(PDO::FETCH_ASSOC);
                                                                           
                                                                           // getting products 
                                                                           $products = json_decode($row['products'], true);
                                                                           $productDetails = "";
                                                                           foreach ($products as $product) {
                                                                               $sqlh = $db->prepare("select * from products where id = ?");
                                                                               $sqlh->execute([$product['productname']]);
                                                                               $productname = $sqlh->fetch(PDO::FETCH_ASSOC);
                                                                               $productDetails .= $product['miktar'] . " " . $product['birim'] . " " . $productname["productname"] . "\n"; // Use "\n" instead of "<br>"
                                                                           }
                                                                       
                                                                           // Fatura Durumu
                                                                           $faturaDurumu = $row["status"] === "true" ? "Kabul Edildi" : ($row["status"] === "false" ? "Red Edildi" : ($row["status"] === "waiting" ? "Cevap Bekleniyor" : ""));
                                                                       
                                                                           $data = [
                                                                               $row["productcode"],
                                                                               $customer_row["name"],
                                                                               $faturaDurumu,
                                                                               $row["date-added"],
                                                                               $productDetails,
                                                                               $row["totalPrice"]
                                                                           ];
                                                                       
                                                                           $xlsxDataList[] = $data;

                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row["productcode"]; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                
                                                                echo $customer_row["name"];
                                                                ?>
                                                            </td>
                                                            <td >
                                                              
                                                                <span id="ember3950" class=" fw-bold text-secondary">
                                                                    <i class="fa-regular fa-file-lines fs-5 text-secondary"></i>

                                                                    <svg width="35" height="50">
                                                                        <circle cx="18" cy="25" r="10" stroke-width="2" fill=" 
                                                                
                                                                <?php
                                                                echo $bb = $row["status"] === "true"
                                                                    ? "lightgreen"
                                                                    : ($row["status"] === "false" ? "#cc3333" : ($row["status"] === "waiting" ? "#f7e98e" : ""));
                                                                ?>
                                                                " />

                                                                    </svg>


                                                                    <?php
                                                                    echo $bb = $row["status"] === "true"
                                                                        ? "Kabul Edildi"
                                                                        : ($row["status"] === "false" ? "Red Edildi" : ($row["status"] === "waiting" ? "Cevap Bekleniyor" : ""));
                                                                    ?>

                                                                </span>
                                                            </td>
                                                            <td class="text-bold-500">
                                                                <?php echo $row["date-added"]; ?>
                                                            </td>
                                                            <td>
                                                            <?php
                                            $products = json_decode($row['products'], true);
                                            foreach ($products as $product) {
                                                echo  $product['miktar'] . " ";
                                                echo  $product['birim'] . " ";
                                                $sqlh = $db->prepare("select * from  products where id = ?");
                                                $sqlh->execute([$product['productname']]);
                                                $productname = $sqlh->fetch(PDO::FETCH_ASSOC);
                                                echo  $productname["productname"] . "<br>";
                                            }
                                            ?>

                                                            </td>
                                                            <td><i class="fa-solid fa-turkish-lira-sign"></i>
                                                                <?php echo $row["totalPrice"]; ?>
                                                            </td>
                                                            
                                                        </tr>

                                                    <?php
                                                    }
                                                }






                                                ?>


                                            </tbody>
                                        </table>
                                    </div>
                                   




                                </div>
                            </div>
                        </div>
                    </div>
         
            </section>
            </div>
        </div>

        <!-- footer-->
    </div>
    </div>
</body>
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
        $("#dateInput").
            datepicker($.datepicker.regional["tr"]);
    });
}) 

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script>
    function exportToXLSX() {
        // Example data
      

        // Create a new workbook
        var wb = XLSX.utils.book_new();

        // Add a worksheet
        var ws = XLSX.utils.aoa_to_sheet(<?php echo json_encode($xlsxDataList); ?>);
        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

        // Save the workbook as XLSX
        XLSX.writeFile(wb, 'SatışRaporu.xlsx');
    } 
</script>


</html>

