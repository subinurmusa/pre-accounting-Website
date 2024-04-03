<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
require "db.php";

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                <div class="div text-black fs-3" style="position:absolute;left:280px;"> <span><i
                            class="fa-solid fa-arrow-down-short-wide text-secondary fs-3"></i> Giderler  </span> </div>

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
                                            <form class="d-flex m-0" method="GET">
                                                <select class="form-select w-100 me-1" name="category">
                                                    <option value="">kategori Seç ..</option>
                                                    <option value="bank">Banka Giderler</option>
                                                    <option value="devletKurumu">Devlet kurumu </option>
                                                    <option value="maas">Çalışan maaşları </option>
                                                    <option value="fisfatura">Fiş Fatura  </option>
                                                   
                                                </select>
                                               
                                                <button class="btn btn-outline-success rounded-pill text-dark"
                                                    type="submit">Ara</button>
                                            </form>
     
               

                                        </div>
                                    </nav>
                                </div>
                                <div class="card-content mt-3">
  
                                <div class="card shadow">
    <h5 class="card-header mb-2  p-2 text-dark bg-opacity-50 p-2 d-flex align-items-center">
        <span class="ps-3 flex-fill">Kayıt İsmi</span>
        <span class="flex-fill">Düzenleme Tarihi</span>
        <span class="pe-3 flex-fill">Kalan Meblağ</span>
    </h5>
   
    <?php  
                                           $category = isset($_GET["category"]) ? $_GET["category"] : null;
                                           
                                           if ( !empty($category)) {
                                               // Prepare the SQL query based on the selected category
                                               switch ($category) {
                                                case 'devletKurumu':
                                                    $sql = "SELECT * FROM vergisgkpirimigiderler WHERE dueDate < CURDATE()";
                                                    break;
                                                case 'bank':
                                                    $sql = "SELECT * FROM bankagiderler WHERE dueDate < CURDATE()";
                                                    break;
                                                case 'fisfatura':
                                                    $sql = "SELECT * FROM fisfaturagiderler WHERE dueDate < CURDATE()";
                                                    break;
                                                case 'maas':
                                                    $sql = "SELECT * FROM maas WHERE lastPaymentDate < CURDATE()";
                                                    break;
                                            }
                                            
                                               $stmt = $db->prepare($sql);
                                               $stmt->execute();
                                           
                                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                                                     //   echo "222222end";
                                                       // print_r($row);

                                                ?>  
                                                          <a href="bankDetails.php?id=<?php echo $row['id']; ?>" class="text-decoration-none">
                    <div class="row bg-light p-1 bg-opacity-50 border rounded mb-2 justify-content-around align-items-center">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <div>
                               
                                <?php if($row["type"]=="fis_Fatura"){echo '<i class="fa-regular fa-file-lines fs-1"></i>';}
                                else if ($row["type"]=="maas"){echo '<i class="fa-solid fa-user fs-1 text-danger"></i>';}
                               else{echo ' <i class="fa-solid fa-building-columns fs-1 text-danger"></i>';}
                                
                                ?>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-5 text-dark">  <?php if($row["type"]=="fis_Fatura"){echo $row["titleName"];}
                                else {echo $row["title"];}
                                
                                ?></h5>
                                <p class="mb-0 text-secondary">Banka</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php if($row["fisFaturaDate"]!=null){ echo $row["fisFaturaDate"];} if($row["issueDate"]!=null){ echo $row["issueDate"];} if($row["hakedisdate"]){ echo $row["hakedisdate"]; }if($row["type"]=="Vergi / SGK Primi"){echo "1 ".$row["vergiDonemiMonth"]." ".$row["vergiDonemiYear"];}  ?></p>
                            <p class="mb-0"><?php echo $row["type"] ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php if($row["totalCost"]!=null){ echo $row["totalCost"];} if($row["toplamtutar"]!=null){ echo $row["toplamtutar"];} if($row["geneltoplam"]){ echo $row["geneltoplam"]; } ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                            <p class="mb-0">Genel Toplam <?php 
                            if($row["totalCost"]!=null){ echo $row["totalCost"];}
                             if($row["toplamtutar"]!=null){ echo $row["toplamtutar"];}
                             if($row["geneltoplam"]){ echo $row["geneltoplam"]; }
                              ?>
                              <i class="fa-solid fa-turkish-lira-sign"></i></p>
                        </div>
                    </div>
                </a>
                                                   <?php 
                                                   }
                                                }else{
                                                   ?>
                                                   
                                                   <div class="card-body">
        <?php
        $sqlbank = $db->prepare("SELECT * FROM bankagiderler WHERE dueDate < CURDATE()");
        $sqlbank->execute();
        $bankgiderler = $sqlbank->fetchAll(PDO::FETCH_ASSOC);

        if ($bankgiderler != null) {
            foreach ($bankgiderler as $bankgider) {
        ?>
                <a href="bankDetails.php?id=<?php echo $bankgider['id']; ?>" class="text-decoration-none">
                    <div class="row bg-light p-1 bg-opacity-50 border rounded mb-2 justify-content-around align-items-center">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <div>
                                <i class="fa-solid fa-building-columns fs-1 text-danger"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-5 text-dark"><?php echo $bankgider["title"] ?></h5>
                                <p class="mb-0 text-secondary">Banka</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $bankgider["issueDate"] ?></p>
                            <p class="mb-0"><?php echo $bankgider["type"] ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $bankgider["totalCost"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                            <p class="mb-0">Genel Toplam <?php echo $bankgider["totalCost"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                        </div>
                    </div>
                </a>
        <?php
            }
        }

        $sqlsgk = $db->prepare("SELECT * FROM vergisgkpirimigiderler WHERE dueDate < CURDATE()");
        $sqlsgk->execute();
        $sgklar = $sqlsgk->fetchAll(PDO::FETCH_ASSOC);
        if ($sgklar != null) {
            foreach ($sgklar as $sgkgider) {
        ?>
                <a href="sgkDetails.php?id=<?php echo $sgkgider['id']; ?>" class="text-decoration-none">
                    <div class="row bg-light p-1 bg-opacity-50 border rounded mb-2 justify-content-around align-items-center">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <div>
                                <i class="fa-solid fa-building-columns fs-1 text-danger"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-5 text-dark"><?php echo $sgkgider["title"] ?></h5>
                                <p class="mb-0 text-secondary">Devlet Kurumu</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0">1 <?php echo $sgkgider["vergiDonemiMonth"]." ".$sgkgider["vergiDonemiYear"] ?></p>
                            <p class="mb-0"><?php echo $sgkgider["type"] ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $sgkgider["totalCost"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                            <p class="mb-0">Genel Toplam <?php echo $sgkgider["totalCost"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                        </div>
                    </div>
                </a>
        <?php
            }
        }

        $sqlmaas = $db->prepare("SELECT * FROM maas where lastPaymentDate < CURDATE()");
        $sqlmaas->execute();
        $maaslar = $sqlmaas->fetchAll(PDO::FETCH_ASSOC);
        if ($maaslar != null) {
            foreach ($maaslar as $maasgider) {
        ?>
                <a href="maasDetails.php?id=<?php echo $maasgider['id']; ?>" class="text-decoration-none">
                    <div class="row bg-light p-1 bg-opacity-50 border rounded mb-2 justify-content-around align-items-center">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <div>
                            <i class="fa-solid fa-user fs-1 text-danger"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-5 text-dark"><?php echo $maasgider["title"] ?></h5>
                                <p class="mb-0 text-secondary"><?php echo $maasgider["employeeName"] ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $maasgider["hakedisdate"] ?></p>
                            <p class="mb-0">Maaş</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $maasgider["toplamtutar"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                            <p class="mb-0">Genel Toplam <?php echo $maasgider["toplamtutar"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                        </div>
                    </div>
                </a>
        <?php
            }
        }
        $sqlfatura = $db->prepare("SELECT * FROM fisfaturagiderler WHERE dueDate < CURDATE()");
        $sqlfatura->execute();
        $fisFaturalar = $sqlfatura->fetchAll(PDO::FETCH_ASSOC);
        if ($fisFaturalar != null) {
            foreach ($fisFaturalar as $maasgider) {
        ?>
                <a href="fisFaturaDetails.php?id=<?php echo $maasgider['id']; ?>" class="text-decoration-none">
                    <div class="row bg-light p-1 bg-opacity-50 border rounded mb-2 justify-content-around align-items-center">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <div>
                            <i class="fa-regular fa-file-lines fs-1"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-5 text-dark"><?php echo $maasgider["titleName"] ?></h5>
                                <p class="mb-0 text-secondary"><?php echo $maasgider["vendor"] ?></p>
                             
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $maasgider["fisFaturaDate"] ?></p>
                            <p class="mb-0">Fiş/ Fatura</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0"><?php echo $maasgider["geneltoplam"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                            <p class="mb-0">Genel Toplam <?php echo $maasgider["geneltoplam"] ?> <i class="fa-solid fa-turkish-lira-sign"></i></p>
                        </div>
                    </div>
                </a>
        <?php
            }
        }
        ?>
    </div>
                                                   <?php 
                                                }
                                                ?>
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
</body>

</html>