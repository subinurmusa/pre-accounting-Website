<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
//$searchName =null;
require "db.php";

$userinfoSql = $db->prepare("SELECT * FROM `users` WHERE username=?");
$userinfoSql->execute([$_SESSION["username"]]);
$userinsolist = $userinfoSql->fetch(PDO::FETCH_ASSOC);

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
                <div class="div text-black fs-3" style="position:absolute;left:280px;"> <span> <i
                            class="fa-solid fa-landmark fs-3"></i> Kullanıcı Bilgileri </span> </div>

                <div class="div text-white" style="position:absolute;right:1px;"> <span><i
                            class="fa-solid fa-rainbow"></i></span> </div>
            </div>
            <!-- body-->
            <div class="mt-4 m-5 py-1 d-flex justify-content-end container">
                <section class="section" style="width:1100px">
                    <div class="row" id="table-striped-dark">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header p-0">
                                    <nav class="navbar navbar-light p-4 bg-light w-100">
                                        <div
                                            class="container-fluid d-flex align-items-center  justify-content-between gap-3">
                                            <div class="d-flex justify-content-start align-items-center gap-3">
                                                <div> <i class="fa-solid fa-building fs-5"></i></div>
                                                <div class="fs-5 bold "> kullanıcı Detayalarım </div>
                                            </div>



                                            <div>
                                                <a href="addUserInfo.php" class="btn btn-outline-primary text-dark">
                                                    Bilgileri Düzenle</a>
                                            </div>

                                        </div>
                                    </nav>
                                </div>
                                <hr>
                                <div class="card-content mt-3">


                                    <div class=" card-body shadow d-flex justify-content-start gap-5 ">
                                        <!-- rigth side   -->
                                        <div>
                                        <div>
                                            <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">
                                                <div class="d-flex  gap-3 justify-content-start align-items-center">
                                                    <div><i class="fa-solid fa-user fs-5"></i></div>
                                                    <div class=" fw-bold">Adı</div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">
                                            <div class="d-flex  gap-3 justify-content-start align-items-center">
                                                <div><i class="fa-regular fa-user fs-5"></i></div>
                                                <div class=" fw-bold">Soyadı</div>
                                            </div>

                                        </div>

                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">
                                            <div class="d-flex  gap-3 justify-content-start align-items-center">
                                                <div><i class="fa-solid fa-address-card fs-5"></i></div>
                                                <div class=" fw-bold"> Kullanıcı adı</div>
                                            </div>

                                        </div>
                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">
                                            <div class="d-flex  gap-3 justify-content-start align-items-center">
                                                <div><i class="fa-regular fa-envelope fs-5"></i></div>
                                                <div class=" fw-bold">  e-posta</div>
                                            </div>

                                        </div>

                                        </div>
                                       
                                 
                                    <!-- left  side   -->
                                    <div>
                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">

                                            <div>
                                                <?php echo $userinsolist["name"] == null ? "----------" : $userinsolist["name"]; ?>
                                            </div>
                                        </div>
                                      

                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">

                                            <div>    <?php echo $userinsolist["lastname"] == null ? "----------" : $userinsolist["lastname"]; ?></div>
                                        </div>

                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">

                                            <div>
                                                <?php echo $userinsolist["username"] == null ? "----------" : $userinsolist["username"]; ?>
                                            </div>
                                        </div>
                                        <div class="d-flex  gap-5 mb-3 justify-content-start align-items-center">

                                            <div>
                                            <?php echo $userinsolist["email"] == null ? "----------" : $userinsolist["email"]; ?>
                                            </div>
                                        </div>
                         
                                    </div>

                                    </div>
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

    <script>
        function confirmDelete(vendorId) {
            // Display confirmation popup
            if (confirm("Bu Tedarikçini sistemden çıkarmak istediğinize eminmisiniz?")) {
                // If confirmed, redirect to the delete link
                window.location.href = "vendordelete.php?id=" + vendorId;
            }
        }
    </script>


</body>

</html>