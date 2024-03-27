<?php
// require "db.php";
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
$num = 0;
$error="";
ini_set('display_errors', 1);
error_reporting(E_ALL);



?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);


try {
    if (isset($_POST['submit'])) {
        // Your existing code here
   
        // Your database operations, form processing, etc.
        $title = isset($_POST['kayitIsmi']) ? $_POST['kayitIsmi'] : null;
        $vergi_ay = isset($_POST['vergi_ay']) ? $_POST['vergi_ay'] : null;
        $vergi_yil = isset($_POST['vergi_yil']) ? $_POST['vergi_yil'] : 0;
        $toplam_tutar = isset($_POST['toplam_tutar']) ? $_POST['toplam_tutar'] : 0;        
        $status = isset($_POST['odeme_durumu'])=="Ödenecek"? 0 : 1;
        $vade_tarihi = isset($_POST['vade_tarihi']) ? $_POST['vade_tarihi'] : null;
       //var_dump($_POST);
        if (empty($title)||empty($toplam_tutar)||empty($vade_tarihi)) {
            $error = "<div class='alert alert-danger'> Kayıt ismi /vade tarihi/ toplam tutar alanları  Gereklidir</div>";
            
        }        
        else{
         
            require "db.php"; // Prepare and execute the SQL statement
            $sql = $db->prepare("INSERT INTO `vergisgkpirimigiderler`(`title`, `vergiDonemiMonth`, `vergiDonemiYear`, `totalCost`, `status`, `dueDate`, `type`)  VALUES (?,?,?,?,?,?,?)");
        
            $sql->execute([$title, $vergi_ay, $vergi_yil, $toplam_tutar, $status,$vade_tarihi,"Vergi / SGK Primi"]);
        
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
    }

 catch (Exception $e) {
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

    <div class=" container d-flex align-items-center w-50 justify-content-start mt-5 ">
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark">  <i class="fa-solid fa-file-invoice-dollar"></i> Vergi / SGK Primi</p>
    </div>
    <hr>
    <div class="">
    <div class="container">
    <div class="row">
        <div class="col-lg-9 offset-lg-3">
            <!-- Content area -->
            <div class="container mt-3">
                <form method="POST" id="form" enctype="multipart/form-data">
                <div class="row mt-3">
                       <?php echo $error==""?"": $error; ?>
                    
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kayitIsmi" class="form-label">Kayıt İsmi</label>
                                <input class="form-control" type="text" id="kayitIsmi" name="kayitIsmi">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-between align-items-center gap-2">
                            <div class="mb-3 w-50 ">
                                <label for="vergi_ay" class="form-label">Vergi Dönemi (Ay)</label>
                                <select class="form-select" id="vergi_ay" name="vergi_ay">
                              
    <option value="Ocak">Ocak</option>
    <option value="Şubat">Şubat</option>
    <option value="Mart">Mart</option>
    <option value="Nisan">Nisan</option>
    <option value="Mayıs">Mayıs</option>
    <option value="Haziran">Haziran</option>
    <option value="Temmuz">Temmuz</option>
    <option value="Ağustos">Ağustos</option>
    <option value="Eylül">Eylül</option>
    <option value="Ekim">Ekim</option>
    <option value="Kasım">Kasım</option>
    <option value="Aralık">Aralık</option>




                                </select>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="vergi_yil" class="form-label">Vergi Dönemi (Yıl)</label>
                                <select class="form-select" id="vergi_yil" name="vergi_yil">
                                    <?php $time= date("Y");
                                          for ($i=$time+4 ; $i >= $time-4; $i--) { 
                                            # code...
                                      ?>
                                            <option value="<?php echo $i ?>" <?php echo $i==date("Y")? "selected":""; ?>><?php echo $i ?></option>
                                            
                                  <?php }  ?>
                                    
                                    
                                 
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="toplam_tutar" class="form-label">Toplam Tutar</label>
                                <input class="form-control" type="number" id="toplam_tutar" name="toplam_tutar" value="0.00" step="0.01" inputmode="numeric">

                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="mb-3">
    <label for="odeme_durumu" class="form-label">Ödeme Durumu</label>

    <div id="radioDiv" class="d-flex align-items-center border border-2 p-1">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="odeme_durumu" id="odeme_durumu_odenecek" value="Ödenecek" checked>
            <label class="form-check-label" for="odeme_durumu_odenecek">Ödenecek</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="odeme_durumu" id="odeme_durumu_odendi" value="Ödendi">
            <label class="form-check-label" for="odeme_durumu_odendi">Ödendi</label>
        </div>
    </div>
</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vade_tarihi" class="form-label">Vade Tarihi</label>
                                <input class="form-control" type="text" id="vade_tarihi" name="vade_tarihi" placeholder="Ödemenin vadesi" data-date-format="dd.mm.yyyy" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <a href="giderler.php" class="btn btn-secondary">Vazgeç</a>
                                <button type="submit" name="submit" id="submit" class="btn btn-primary">Kaydet</button>
                            </div>
                        </div>
                    </div>
                    <!-- End of Buttons -->
                </form>
            </div>
        </div>
    </div>
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
            dropdown.style.backgroundColor = "#f8f9fa"; // Light gray background
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
        $("#vade_tarihi").datepicker($.datepicker.regional["tr"]);

    });
}) 
</script>

<script>
    const radioDiv = document.getElementById("radioDiv");
    const odemeDurumuOdenecek = document.getElementById("odeme_durumu_odenecek");
    const odemeDurumuOdendi = document.getElementById("odeme_durumu_odendi");

    // Function to change background color based on selected radio button
    function changeBackgroundColor() {
        if (odemeDurumuOdendi.checked) {
            radioDiv.style.backgroundColor = "lightgreen";
        } else if (odemeDurumuOdenecek.checked) {
            radioDiv.style.backgroundColor = "lightgray";
        }
    }

    // Add event listener to radio buttons
    odemeDurumuOdenecek.addEventListener("change", changeBackgroundColor);
    odemeDurumuOdendi.addEventListener("change", changeBackgroundColor);

    // Initially set the background color based on the default selected radio button
    changeBackgroundColor();
</script>

</body>


</html>