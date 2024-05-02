<?php
session_start();

if (empty($_SESSION["username"])) {
    header("location:login.php");
}
$visitcount=7;
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
<?php
require "db.php";
error_reporting(E_ALL);

// Get current week's start and end dates

$currentWeekStartDate = date('Y-m-d', strtotime('monday this week'));
$currentWeekEndDate = date('Y-m-d', strtotime('sunday this week'));

// Function to fetch expenses from bankagiderler table for the current week
function getBankaGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(issueDate, '%d.%m.%Y')) AS day, SUM(totalCost) AS total_expense FROM bankagiderler WHERE STR_TO_DATE(issueDate, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(issueDate, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);


    if($result!=null){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expenses[$row['day']] = $row['total_expense'];
        }
    }
   

    return $expenses;
}

// Function to fetch expenses from fisfaturagiderler table for the current week
function getFisFaturaGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(fisFaturaDate, '%d.%m.%Y')) AS day, SUM(geneltoplam) AS total_expense FROM fisfaturagiderler WHERE STR_TO_DATE(fisFaturaDate, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(fisFaturaDate, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    if($result!=null){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expenses[$row['day']] = $row['total_expense'];
        }
    }

    return $expenses;
}

// Function to fetch expenses from maas table for the current week
function getMaasExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(date) AS day, SUM(toplamtutar) AS total_expense FROM maas WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(date)";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    if($result!=null){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expenses[$row['day']] = $row['total_expense'];
        }
    }

    return $expenses;
}

// Function to fetch expenses from vergisgkpirimigiderler table for the current week
function getVergisGkPirimGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(date) AS day, SUM(totalCost) AS total_expense FROM vergisgkpirimigiderler WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(date)";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    if($result!=null){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expenses[$row['day']] = $row['total_expense'];
        }
    }

    return $expenses;
}

// Function to fetch income from selling table for the current week

function getIncome($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(`date-added`, '%d.%m.%Y')) AS day, SUM(totalPrice) AS total_income FROM selling WHERE STR_TO_DATE(`date-added`, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(`date-added`, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $income = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    if($result!=null){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expenses[$row['day']] = $row['total_expense'];
        }
    }

    return $income;
}

function generateWeekLabels($startOfWeek, $endOfWeek) {
    setlocale(LC_TIME, 'tr_TR.UTF-8'); // Set locale to Turkish

    // Define short week day names in Turkish
    $shortWeekDays = [
        'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cts', 'Paz'
    ];

    $labels = [];
    $currentDate = $startOfWeek;

    while ($currentDate <= $endOfWeek) {
        $weekDay = strftime('%a', strtotime($currentDate)); // Get short week day name
        $date = strftime('%d.%m.%y', strtotime($currentDate)); // Change format to day.month.year
        $labels[] = ['weekDay' => $shortWeekDays[date('N', strtotime($currentDate)) - 1], 'date' => $date];
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

    return $labels;
}

// Generate array of week labels


$weekData = generateWeekLabels($currentWeekStartDate, $currentWeekEndDate);

// Get current week's expenses and income
$bankaGiderlerExpenses = getBankaGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$fisFaturaGiderlerExpenses = getFisFaturaGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$maasExpenses = getMaasExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$vergisGkPirimGiderlerExpenses = getVergisGkPirimGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$income = getIncome($db, $currentWeekStartDate, $currentWeekEndDate);


// Close connection
$db = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <link href="css\sidebars.css" rel="stylesheet">
    <!-- <script src="css\canvasjs.min.js"> </script> -->
    <!-- chart  -->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sidebars/">
    <link href="css\app.css" rel="stylesheet">
    <link href="css\bootstrap.min.css" rel="stylesheet">
    <style>
       
     
    
        #content {
            margin-left: 200px; /* Adjust the margin to fit the sidebar */
            padding: 20px;
       
        }
        #myChart {
            width: 100%; /* Adjust the width of the chart */
            max-width: 1000px; /* Set maximum width */
            margin: 20px auto; /* Center the chart horizontally */
            display: block;
            background-color: white;
        }
    </style>
    <title>Document</title>
</head>
<!-- navbar css -->
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

    body {
        background-color: #f0fff0;
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



<body class="">

<div class=" d-flex justify-content-end ">
        <nav class=" navbar d-flex justify-content-end  p-2  w-100 pe-5 bg-black bg-opacity-75">


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

    <div id="sidebar" class="d-none d-md-block">
    <div class="sidebar-wrapper active shadow" style="height: 100%; width: 200px;">
        <?php
        try {
            require "sidebar.php";
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        ?>
    </div>
</div>

   
  

    <div class="container mt-3 d-grid justify-content-end align-items-end ">
        <div class="row mt-5 mb-5 d-flex  text-center" style="gap:120px">
            <div class="fs-3 text-dark"> Ön Muhasebe Programına Hoşgeldiniz</div>
        </div>
        <div class="container mt-5">
        <div class="row d-flex justify-content-center " style="gap:120px">
            <div class="col-md-2">
                <div class="card shadow bg-light  border-2 border border-secondary mb-3"
                    style="width: 300px; height: 300px">
                    <div class="card-header bg-info border border-secondary border-2 text-white fs-3 text-center p-0 ">
                        Günlük Tahsilatlar
                    </div>
                    <div class="card-body text-white d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam ciro</h5>
                        <?php

                        $dateToday = date("d.m.Y");
                        $dateToday = strval($dateToday);

                        require "db.php"; // Assuming db.php contains your database connection code
                        
                        $sql1 = $db->query("SELECT `id`, `productcode`, `costomer`, `date-added`, `totalPrice` FROM `selling` WHERE `date-added` = '$dateToday';");
                        $sql1->execute();
                        $count1 = 0;
                        while ($result = $sql1->fetch(PDO::FETCH_ASSOC)) {

                            $count1 += $result["totalPrice"];
                        }

                        if ($count1 > 0) {
                            $color = "blue";
                            $Mss = "Bol Kazançlar elde ediyorsunuz Haydi Devam";
                        } else {
                            $color = "lightgray";
                            $Mss = "Biraz daha çalışın";
                        }
                        ?>

                        <svg class="progress bg-transparent  " width="200" style="height: 120px;">
                            <circle class="progress-circle" cx="100" cy="60" stroke="<?php echo $color ?>" r="50"
                                fill="transparent" stroke-width="9" />
                            <text class="loading fs-3" fill="blue" x="100" y="60" alignment-baseline="middle"
                                text-anchor="middle">
                                <?php echo $count1 ?>
                            </text>
                        </svg>
                        <span class=" loading"></span>
                    </div>
                    <div class="card-footer text-white bg-info border-2 border-secondary align-items-center d-flex p-2">
                        <?php echo $Mss ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow  border-2 border border-secondary mb-3" style="width: 300px; height: 300px">
                    <div
                        class="card-header bg-danger border border-secondary border-2 text-white fs-3 text-center p-0 ">
                        Günlük Giderler</div>
                    <div class="card-body text-white bg-danger  bg-opacity-50 d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam Gider</h5>
                        <?php
                        $count = 0;
                        require "db.php"; // Assuming db.php contains your database connection code
                        //bank giderler
                        $sqlbank = $db->prepare("SELECT `totalCost`FROM `bankagiderler` WHERE `issueDate` = '" . date("d.m.Y") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sqlbank->execute();
                        while ($result = $sqlbank->fetch(PDO::FETCH_ASSOC)) {
                            $count += $result["totalCost"];
                        }
                        
                        //fisfaturagiderler
                        $sqlfatura = $db->prepare("SELECT `geneltoplam`FROM `fisfaturagiderler` WHERE `fisFaturaDate` = '" . date("d.m.Y") . "';");
                        // $sql->bindParam(':date_added', date("Y-m-d"));
                        $sqlfatura->execute();

                        
                        while ($result = $sqlfatura->fetch(PDO::FETCH_ASSOC)) {
                            $count += $result["geneltoplam"];
                        }
                          //maas
                          $sqlmaas = $db->prepare("SELECT `toplamtutar`FROM `maas` WHERE `date` = '" . date("y-m-d") . "';");
                          // $sql->bindParam(':date_added', date("Y-m-d"));
                          $sqlmaas->execute();
  
                          
                          while ($result = $sqlmaas->fetch(PDO::FETCH_ASSOC)) {
                              $count += $result["toplamtutar"];
                          }
                                   //vergisgkpirimigiderler
                                   $sqlsgk = $db->prepare("SELECT `totalCost`FROM `vergisgkpirimigiderler` WHERE `date` = '" . date("y-m-d") . "';");
                                   // $sql->bindParam(':date_added', date("Y-m-d"));
                                   $sqlsgk->execute();
           
                                   
                                   while ($result = $sqlsgk->fetch(PDO::FETCH_ASSOC)) {
                                       $count += $result["totalCost"];
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
                <div class="card shadow bg-success bg-opacity-50  border-2 border border-secondary mb-3"
                    style="width: 300px; height: 300px">
                    <div
                        class="card-header bg-success  border border-secondary border-2 text-white fs-3 text-center p-0 ">
                        Günlük Kazanç</div>
                    <div class="card-body text-white d-grid justify-content-center p-3 ">
                        <h5 class="card-title text-center">toplam Kazanç</h5>
                        <?php
                        $total = 0;

                 
                        $total = $count1 - $count;
                        if ($total > 0) {
                            $color = "green";
                            $cheeringMs = "aynen Böyle Devam Edin ";
                        } else {
                            $color = "lightgreen";
                            $cheeringMs = "biraz daha çabalayın Başarabilirsiniz ";
                        } ?>
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
                    <div
                        class="card-footer text-white bg-success border-2 border-secondary p-2 d-flex justify-content-center">
                        <?php echo $cheeringMs ?>
                    </div>
                </div>
            </div>
        </div>
</div>


    </div>
    <hr class=" mt-5  " style="height:10px ; ">
   <!--  <div class="container mt-2 d-grid justify-content-end pe-5 ms-5  ">
        <div id="chartContainer" style="height: 400px; width: 1000px;" class="ps-5 mt-5 mb-5 ms-3">

        </div>
    </div> -->

    <div id="content">
        <canvas id="myChart"></canvas>
    </div>
 
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_map(function($data) {
                return $data['weekDay'] . "\n" . $data['date']; // Combine week day and date with a line break
            }, $weekData)); ?>,
                datasets: [{
                    label: 'Banka Giderleri',
                    data: [<?php echo $bankaGiderlerExpenses['Monday']; ?>, <?php echo $bankaGiderlerExpenses['Tuesday']; ?>, <?php echo $bankaGiderlerExpenses['Wednesday']; ?>, <?php echo $bankaGiderlerExpenses['Thursday']; ?>, <?php echo $bankaGiderlerExpenses['Friday']; ?>, <?php echo $bankaGiderlerExpenses['Saturday']; ?>, <?php echo $bankaGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Fis Fatura Giderleri',
                    data: [<?php echo $fisFaturaGiderlerExpenses['Monday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Tuesday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Wednesday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Thursday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Friday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Saturday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Maaş Giderleri',
                    data: [<?php echo $maasExpenses['Monday']; ?>, <?php echo $maasExpenses['Tuesday']; ?>, <?php echo $maasExpenses['Wednesday']; ?>, <?php echo $maasExpenses['Thursday']; ?>, <?php echo $maasExpenses['Friday']; ?>, <?php echo $maasExpenses['Saturday']; ?>, <?php echo $maasExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }, {
                    label: 'Vergi ve G.K. Prim Giderleri',
                    data: [<?php echo $vergisGkPirimGiderlerExpenses['Monday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Tuesday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Wednesday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Thursday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Friday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Saturday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Satış Gelirleri',
                    data: [<?php echo $income['Monday']; ?>, <?php echo $income['Tuesday']; ?>, <?php echo $income['Wednesday']; ?>, <?php echo $income['Thursday']; ?>, <?php echo $income['Friday']; ?>, <?php echo $income['Saturday']; ?>, <?php echo $income['Sunday']; ?>],
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Şirketinizin haftalık Nakıt akışı',
                         font: {
            size: 20 // Adjust the font size here as needed
        }
                    },
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 17
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>  -->
    <script src="https://cdn.canvasjs.com/ga/canvasjs.stock.min.js"></script>


   <script src="css\sidebars.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script> 

     
</body>

</html>