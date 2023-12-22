<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <title>Document</title>

</head>

<body>
    <div class="card shadow-lg d-flex justify-contens-center align-items-center " style="with: 300px; height: 700px">
        <canvas id="mychart"
            class="container h-50 w-50 card shadow-lg d-flex justify-contens-center align-items-center "></canvas>
    </div>
    <script>
        let label = ['11.ara', '12.ara', '12.ara', '12.ara', '12.ara', '12.ara', '12.ara', '12.ara'];
        let bb= ["fff","ggg","llll"];
        let itemdata = [ bb, '6000', '6000', '6000', '6000', '6000', '6000', '6000'];
        const data = {
            labels: label,
            datasets: [{
                // data: null,
                backgroundColor: 'rgb(39, 68, 115)'
            }]

        }

        const config = {
            type: "bar",
            data: data,
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'bu ayın Nakıt akışı '
                }
            }
        }
        const chart = new Chart(
            "mychart",
            config
        );
    </script>
</body>

</html> -->



<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Cash Flow Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <style>
        canvas {
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <canvas id="cashFlowChart" class="p-1" width="800" height="300"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var canvas = document.getElementById('cashFlowChart');
            var context = canvas.getContext('2d');

            // Sample weekly cash flow data
            var weeklyCashFlow = [200, 300, 150, 400, 250, 350, 180];

            // Calculate the maximum value for scaling
            var maxValue = Math.max(...weeklyCashFlow);

            // Canvas dimensions
            var canvasWidth = canvas.width;
            var canvasHeight = canvas.height;

            // Bar chart dimensions
            var barWidth = canvasWidth / weeklyCashFlow.length;
            var barSpacing = 20;

            // Function to draw a bar
            function drawBar(x, height) {
                context.fillStyle = '#3498db'; // Bar color
                context.fillRect(x, canvasHeight - height, barWidth - barSpacing, height);
            }

            // Draw the bars
            for (var i = 0; i < weeklyCashFlow.length; i++) {
                var x = i * (barWidth + barSpacing);
                var height = (weeklyCashFlow[i] / maxValue) * (canvasHeight - 20);
                drawBar(x, height);
            }
        });
    </script>
</body>
</html>
 -->

<div class="container">
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
 
 $day=  date('d');
 $sMonth=  date('M');
$one=1;
$dataPoints1 = array(
	array("label"=>  "$day-$sMonth", "y"=> "$countbuy"),
	array("label"=>  $day+(1)."-".$sMonth, "y"=> 70.55),
	array("label"=> $day+(2)."-".$sMonth, "y"=> 72.50),
	array("label"=> $day+(3)."-".$sMonth, "y"=> 81.30),
	array("label"=> $day+(4)."-".$sMonth, "y"=> 63.60),
	array("label"=> $day+(5)."-".$sMonth, "y"=> 69.38),
	array("label"=> $day+(6)."-".$sMonth, "y"=> 98.70)
);
$dataPoints2 = array(
	array("label"=>  "$day-$sMonth", "y"=> "$count"),
	array("label"=>  $day+(1)."-".$sMonth, "y"=> 70.55),
	array("label"=> $day+(2)."-".$sMonth, "y"=> 72.50),
	array("label"=> $day+(3)."-".$sMonth, "y"=> 81.30),
	array("label"=> $day+(4)."-".$sMonth, "y"=> 63.60),
	array("label"=> $day+(5)."-".$sMonth, "y"=> 69.38),
	array("label"=> $day+(6)."-".$sMonth, "y"=> 98.70)
);
	
?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Şirketinizin haftalık Nakıt akışı"
	},
	axisY:{
		includeZero: true,
        title: "Para Akışı",
       valueFormatString: "*",
      interval: 0
	},
	legend:{
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
	},{
		type: "column",
		name: "Kazançlar",
		indexLabel: "{y}",
		yValueFormatString: "",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;">

</div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>                              
</div>