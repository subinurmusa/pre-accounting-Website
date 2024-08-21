<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}

$visitcount = 7;
//$searchName =null;
require "db.php";

$sqluserid=$db->prepare("SELECT id FROM `users` WHERE username = ?;");
$sqluserid->execute([$_SESSION["username"]]);
$userId=$sqluserid->fetch(PDO::FETCH_ASSOC);
 
 

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

                            echo $_SESSION["username"];
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

    <div class="">
        <div class="div">
            <!-- header-->
            <div class="div d-flex ps-5 mt-3 gap-5 align-items-center"
                style="position:relative; height:40px;">
                <div class="div text-black fs-3" style="position:absolute;left:280px;"> <span><i class="fa-solid fa-users-line fs-3"></i> Müşteriler </span> </div>

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
                                        <div class="container-fluid d-flex align-items-center">
                                        <form class="d-flex m-0" method="GET">
    <select class="form-select w-75 me-1" name="category">
        <option value="name">Müşteri İsmi</option>
        <option value="musteriNumber">Müşteri Numarası</option>
        <option value="companyName">Şirket Adı</option>
    </select>
    <input class="form-control me-2 w-100" type="search" placeholder="Ara" aria-label="Search" name="search">
    <button class="btn btn-outline-success rounded-pill text-dark" type="submit">Ara</button>
</form>

                                          
                                            <div>
                                                <a href="addMusteri.php" class="btn btn-outline-success text-dark"> Yeni Müşteri Ekle</a>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                                <div class="card-content mt-3">

                                    <!-- table strip dark -->
                                    <div class="table-responsive">


                                        <table class="table table-striped table-dark mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Şirket Adı</th>
                                                    <th>Vergi Numarası</th>
                                                    <th>Email Adres</th>                                                                                                     
                                                    <th>Işlemler </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            require "db.php";
                                           
                                             
                                           $searchTerm = isset($_GET["search"]) ? $_GET["search"] : null;
                                           $category = isset($_GET["category"]) ? $_GET["category"] : null;
                                           
                                           if (!empty($searchTerm) && !empty($category)) {
                                               // Prepare the SQL query based on the selected category
                                               switch ($category) {
                                                case 'name':
                                                    $sql = "SELECT * FROM customers WHERE name LIKE :searchTerm AND userId = :userid";
                                                    break;
                                                case 'musteriNumber':
                                                    $sql = "SELECT * FROM customers WHERE musterinumara LIKE :searchTerm AND userId = :userid";
                                                    break;
                                                case 'companyName':
                                                    $sql = "SELECT * FROM customers WHERE companyName like :searchTerm AND userId = :userid";
                                                    break;
                                                default:
                                                    // Default to search by name
                                                    $sql = "SELECT * FROM customers WHERE name LIKE :searchTerm AND userId = :userid";
                                                    break;
                                            }
                                            
                                            $stmt = $db->prepare($sql);
                                            $stmt->execute(array(':searchTerm' => '%' . $searchTerm . '%', ':userid' => $userId["id"]));
                                            
                                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                                                     //   echo "222222end";
                                                       // print_r($row);

                                                ?>  
                                                        <tr>
                                                        <td> <?php echo $row["companyName"]; ?></td>
                                                      <td> <?php echo $row["vergiNumber"]; ?></td>
                                                      
                                                      <td class="text-bold-500"><?php  echo $row["email"]; ?></td>
                                                     
                                                      <td>
                                                      <div class="d-flex align-items-center gap-3">
                                                       <a href="musteriEdit.php? id=<?php  echo $row["id"] ;?>" ><i class="fa-regular fa-pen-to-square fs-3 text-success"></i></a>
                                                       <a href="musteriInfo.php? id=<?php  echo $row["id"] ;?>"> <i class="fa-solid fa-circle-info fs-3 detay text-primary"> </i> </a>
                                                       <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>);"> <i class="fa-solid fa-trash text-danger"></i></a>
                                                      
                                                      </div>
                                                      </td>
                                                      
                                                         </tr>   
                                                   <?php 
                                                   }
                                                }
                                                

                                                else{
                                                    $sql = $db->prepare("SELECT * FROM customers WHERE userId=? ;");                                               
                                                    $sql->execute([$userId["id"]]);
                                                   // echo "1111111111111111111";
                                                    while ($row =  $sql ->fetch(PDO::FETCH_ASSOC)) { 
    
                                                  ?>  
                                                   <tr>
                                                         <td> <?php echo $row["companyName"]; ?></td>
                                                       <td> <?php echo $row["vergiNumber"]; ?></td>
                                                       
                                                       <td class="text-bold-500"><?php  echo $row["email"]; ?></td>
                                                      
                                                       <td>
                                                       <div class="d-flex align-items-center gap-3">
                                                        <a href="musteriEdit.php? id=<?php  echo $row["id"] ;?>" ><i class="fa-regular fa-pen-to-square fs-3 text-success"></i></a>
                                                        <a href="musteriInfo.php? id=<?php  echo $row["id"] ;?>"> <i class="fa-solid fa-circle-info fs-3 detay text-primary"> </i> </a>
                                                        <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>);"> <i class="fa-solid fa-trash text-danger"></i></a>
                                                       
                                                      
    
                                                       </div>
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

            <!-- footer-->
        </div>
    </div>

    <script>
    function confirmDelete(customerId) {
        // Display confirmation popup
        if (confirm("Bu muşteriyi sistemden çıkarmak istediğinize eminmisiniz?")) {
            // If confirmed, redirect to the delete link
            window.location.href = "musteridelete.php?id=" + customerId;
        }
    }
</script>
<!-- <script>
    document.getElementById('searchButton').addEventListener('click', function() {
        document.querySelector('form').submit();
    });
</script> -->

</body>

</html>