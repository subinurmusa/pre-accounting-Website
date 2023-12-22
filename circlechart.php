<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <div class="progress-bar">
        <div class="progress"></div>
    </div> -->
    <?php 
            
            require "db.php"; // Assuming db.php contains your database connection code

            $sql = $db->prepare("SELECT `id`, `itemName`, `category`, `date-added`, `price` FROM `selling` WHERE `date-added` = '".date("y-m-d")."';");
         // $sql->bindParam(':date_added', date("Y-m-d"));
            $sql->execute();

            $count = 0;
            while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
                $count += $result["price"];
            }
            echo date("y-m-d");
            if($count>0){
             $color="blue";
            }else{
             $color="lightgray";
            }

            $db = null; // Close the database connection
         

      

         ?>
    <svg class="progress" width="100" height="100">
        <circle class="progress-circle" cx="50" cy="50" stroke="<?php echo $color?>" r="30" fill="transparent" stroke-width="5" />
        <text class="loading" fill="blue" x="50" y="50" alignment-baseline="middle" text-anchor="middle">
        <?php echo $count?>
        </text>
    </svg>
    <span class=" loading"></span>
    <!-- <script src="app.js"></script> -->
</body>
</html>