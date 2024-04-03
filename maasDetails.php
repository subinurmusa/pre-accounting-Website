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

require "db.php"; // Prepare and execute the SQL statement
$sql=$db->prepare("select * from maas where id=?");
$sql->execute([$_GET["id"]]);
$maaslist=$sql->fetch(PDO::FETCH_ASSOC);


?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here

        // Your database operations, form processing, etc.
        $title = isset($_POST['kayitIsmi']) ? $_POST['kayitIsmi'] : null;
        $employee = isset($_POST['employee']) ? $_POST['employee'] : null;
        $hakedisdate = isset($_POST['hakedis_tarihi']) ? $_POST['hakedis_tarihi'] : 0;
        $toplamtutar = isset($_POST['toplam_tutar']) ? $_POST['toplam_tutar'] : 0;
        $lastPaymentDate = isset($_POST['odenecek_tarih']) ? $_POST['odenecek_tarih'] : null;
        $status = isset($_POST['odeme_durumu']) ? $_POST['odeme_durumu'] : null;
        //var_dump($_POST);
        if (empty($title) || empty($employee) || empty($toplamtutar)) {
            $error = "<div class='alert alert-danger'> Kayıt ismi / Çalışan / toplam tutar alanları  Gereklidir</div>";

        } else {

          
            $sql = $db->prepare("UPDATE `maas` SET `title`=?,`employeeName`=?,`hakedisdate`=?,
            `toplamtutar`=?,`lastPaymentDate`=?,`status`=? WHERE id=?");

            $sql->execute([$title, $employee, $hakedisdate, $toplamtutar, $lastPaymentDate, $status,$_GET["id"]]);

            // Check if the SQL statement was executed successfully
            if ($sql) {
                // Redirect to satislar.php
                header("location: giderler.php");
                // Make sure to exit after header to prevent further code execution
            } else {
                $error = "<div class='alert alert-danger'>An error occurred while saving data.</div>";
                //   echo json_encode(['error' => $error]);
                return;
            }
        }


        // If everything is successful, redirect to satislar.php
    }
} catch (Exception $e) {
    // Log the exception details
    error_log("Caught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());

    // Optionally, display a generic error message to the user
    $error = "$e";
    echo json_encode(['error' => $error]);
    return;
}

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

    <div class=" container d-flex align-items-center w-50 justify-content-start mt-5 ">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark"> <i
                class="fa-solid fa-folder-plus fs-3 text-secondary"></i>Çalışan maas Detayları Düzenle</p>
    </div>
    <hr>
    <div class="">
       

            <div class="container">
            <form method="POST" id="form" enctype="multipart/form-data">
            <div class="row mt-3  d-flex justify-content-center align-items-center ps-5 ms-5">
                    
                       <div class="w-75 ps-5 ms-5">
                       <?php echo $error==""?"": $error; ?>
                       </div>
                    
                    </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-regular fa-user fs-5"></i>
                                <label for="kayitIsmi" class="form-label w-25 me-5 pe-4 ps-4">Kayıt İsmi </label>
                                <input class="form-control" type="text" id="kayitIsmi" value="<?php echo $maaslist["title"]?>" name="kayitIsmi">
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Require the database connection
                require "db.php";

                // Fetch the names of employees from the database
                $statement = $db->prepare("SELECT `nameSurname` FROM `employees`");
                $statement->execute();
                $employees = $statement->fetchAll(PDO::FETCH_COLUMN);
                ?>

                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-id-card fs-5"></i>
                                <label for="employee" class="form-label w-25 me-5 pe-4 ps-4">Çalışan</label>
                                <select class="form-select" id="employee" name="employee">
                                    
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?php echo $employee; ?>" <?php echo $maaslist["employeeName"]==$employee?"selected":""?>>
                                            <?php echo $employee; ?>
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
                                <label for="hakedis_tarihi" class="form-label w-25 me-5 pe-4 ps-4">Hakediş
                                    Tarihi</label>
                                <input class="form-control" type="text" id="hakedis_tarihi" name="hakedis_tarihi"
                                    value="<?php echo $maaslist["hakedisdate"] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                    <div class="col-md-9 d-flex justify-content-around align-items-center me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fa-solid fa-money-bill fs-5"></i>
                                <label for="toplam_tutar" class="form-label w-25 me-5 pe-4 ps-4">Toplam Tutar</label>
                                <input class="form-control" type="number" id="toplam_tutar" name="toplam_tutar"
                                value="<?php echo $maaslist["toplamtutar"] ?>">
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
                                    <option value="0"  <?php echo $maaslist["status"]==0?"selected":""?> >Ödenecek</option>
                                    <option value="1" <?php echo $maaslist["status"]==1?"selected":""?> >Ödendi</option>
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
                                <label for="odenecek_tarih" class="form-label w-25 me-5 pe-4 ps-4">Ödenecek
                                    Tarih</label>
                                <input class="form-control" type="text" id="odenecek_tarih" name="odenecek_tarih"
                                    value="<?php echo $maaslist["lastPaymentDate"]?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end align-items-center mt-3">
                <div class="col-md-9 d-flex align-items-center me-5 pe-5">
                    <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                        <div class="bottons">
                            <a href="giderler.php" class="btn btn-secondary">Vazgeç</a>
                            <button type="submit" name="submit" id="submit"
                                class="btn btn-primary opacity-75">Düzenle</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            </div>
            <!-- End of salary related fields -->

            <!-- Buttons -->
         



     
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

            if (option.value === "0") {
                dropdown.style.backgroundColor = "#e6e8eb"; // Light gray background
            } else if (option.value === "1") {
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
                $("#odenecek_tarih").datepicker($.datepicker.regional["tr"]);
                $("#hakedis_tarihi").datepicker($.datepicker.regional["tr"]);

            });
        }) 
    </script>


</body>


</html>