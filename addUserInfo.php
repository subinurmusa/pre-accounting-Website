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

require "db.php";

$userinfosql = $db->prepare("SELECT  * FROM `users` where username=? ");
$userinfosql->execute([$_SESSION["username"]]);
$users = $userinfosql->fetch(PDO::FETCH_ASSOC);

?>
<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);

//phpinfo();



try {
    if (isset($_POST['submit'])) {
        // Your existing code here

        // Your database operations, form processing, etc.
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $lastname = isset($_POST['lastName']) ? $_POST['lastName'] : null;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
       
        var_dump($_POST);

        if (empty($name)|| empty($lastname)|| empty($username)|| empty($email)) {
            $error = "<div class='alert alert-danger'>tüm alanlar Zorunlu Alandır</div>";
        } else {
          

           
            $sql = $db->prepare("UPDATE `users` SET `name`=?,`lastname`=?,`username`=?,`email`=? WHERE username=?");

            $sql->execute([$name, $lastname, $username, $email, $_SESSION["username"]]);

            // Check if the SQL statement was executed successfully
            if ($sql) {

                session_start();
      $_SESSION["username"] = $username;
      $_SESSION["name"] = $name;
      $_SESSION["email"] = $email;
                // Redirect to urunvehizmet.php
                header("location: userinfo.php");
                // Make sure to exit after header to prevent further code execution
                exit();
            } else {
                $error = "<div class='alert alert-danger'>Veri kaydedilirken bir hata oluştu.</div>";
            }
        }
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
        <p class="text-primary d-flex align-items-center gap-2 fs-3 mt-5 text-dark">
            <i class="fa-solid fa-marker fs-3 "></i> kullanıcı Bilgilerini Güncelle
        </p>
    </div>
    <hr>
    <div class="">
        <form method="POST" id="form" enctype="multipart/form-data">

            <div class="container mb-5">
                <div class="row  d-flex justify-content-end align-items-center  ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center me-5 pe-5  ">
                        <?php echo $tt = empty($error) ? "" : $error;

                        ?>
                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="name" class="form-label w-25  me-5 pe-4 ps-4">Ad</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="name" name="name"
                                    value="<?php echo $users["name"] == null ? "" : $users["name"]; ?>">

                            </div>

                        </div>

                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="lastName" class="form-label w-25  me-5 pe-4 ps-4">Soyad</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="lastName" name="lastName"
                                    value="<?php echo $users["lastname"] == null ? "" : $users["lastname"]; ?>">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="username" class="form-label w-25  me-5 pe-4 ps-4">Kullanıcı Adı</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="text" id="username" name="username"
                                    value="<?php echo $users["username"] == null ? "" : $users["username"]; ?>">

                            </div>

                        </div>

                    </div>


                </div>
                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex justify-content-around align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-between w-100 ms-4">
                            <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                <i class="fas fa-coins fs-5"></i>
                                <label for="email" class="form-label w-25  me-5 pe-4 ps-4">Kullanıcı E-posta</label>
                                <!-- <input type="text" id="costomer" name="musteri" class="form-control w-100 ms-5 ps-5"> -->
                                <input class="form-control" type="email" id="email" name="email"
                                    value="<?php echo $users["email"] == null ? "" : $users["email"]; ?>">

                            </div>

                        </div>

                    </div>


                </div>

                <div class="row  d-flex justify-content-end align-items-center mt-3 ">
                    <div class="col-md-9  d-flex  align-items-center  me-5 pe-5">
                        <div class="d-flex align-items-center justify-content-end w-100 ms-4">
                            <div class="bottons">
                                <a href="userinfo.php" class="btn btn-secondary">Vazgeç</a>
                                <button type="submit" name="submit" id="submit" class="btn btn-primary opacity-75">
                                    Düzenle </button>

                            </div>

                        </div>

                    </div>


                </div>



        </form>
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
        $(document).ready(function () {

            // Your jQuery methods go here...

            // Function to generate a random product ID
            function generateRandomProductId() {
                var productId = "";
                for (var i = 0; i < 10; i++) {
                    productId += Math.floor(Math.random() * 10);
                }
                return productId;
            }

            // Get a random product ID
            var randomNumbers = generateRandomProductId();


            // Set the value of the element with ID "ordernumber"
            $("#uruncodu").val(randomNumbers);
            $("#uruncoduhidden").val(randomNumbers);
        });


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
                $("#addedDate").datepicker($.datepicker.regional["tr"]);

            });
        }) 
    </script>
    <script>
        // JavaScript
        function previewImage() {
            var fileInput = document.getElementById('photo');
            var imagePreview = document.getElementById('imagePreview');

            // Check if file is selected
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>

    <script>
        // Function to handle file input change event for the logo
        function handleFileInputChange(event) {
            const file = event.target.files[0];
            const logoPreview = document.getElementById('logoPreview');
            const eraseButton = document.getElementById('eraseButton');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('d-none');
                    eraseButton.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }

        // Function to handle erasing button click for the logo
        function eraseLogoImage(event) {
            event.preventDefault(); // Prevent default button behavior
            const logoPreview = document.getElementById('logoPreview');
            const eraseButton = document.getElementById('eraseButton');
            const logoInput = document.getElementById('logoInput');

            logoPreview.src = '';
            logoPreview.classList.add('d-none');
            eraseButton.classList.add('d-none');
            logoInput.value = '';
        }

        // Function to handle select image button click for the logo
        document.getElementById('selectImageButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default button behavior
            document.getElementById('logoInput').click(); // Trigger file input click
        });

        // Event listener for logo file input change
        document.getElementById('logoInput').addEventListener('change', handleFileInputChange);

        // Event listener for erasing button click for the logo
        document.getElementById('eraseButton').addEventListener('click', eraseLogoImage);

        // Function to handle file input change event for the firma imzasi
        function handleFirmaImzasiInputChange(event) {
            const file = event.target.files[0];
            const firmaImzasiPreview = document.getElementById('firmaImzasiPreview');
            const eraseFirmaImzasiButton = document.getElementById('eraseFirmaImzasiButton');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    firmaImzasiPreview.src = e.target.result;
                    firmaImzasiPreview.classList.remove('d-none');
                    eraseFirmaImzasiButton.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }

        // Function to handle erasing button click for the firma imzasi
        function eraseFirmaImzasi(event) {
            event.preventDefault(); // Prevent default button behavior
            const firmaImzasiPreview = document.getElementById('firmaImzasiPreview');
            const eraseFirmaImzasiButton = document.getElementById('eraseFirmaImzasiButton');
            const firmaImzasiInput = document.getElementById('firmaImzasiInput');

            firmaImzasiPreview.src = '';
            firmaImzasiPreview.classList.add('d-none');
            eraseFirmaImzasiButton.classList.add('d-none');
            firmaImzasiInput.value = '';
        }

        // Function to handle select image button click for the firma imzasi
        document.getElementById('selectFirmaImzasiButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default button behavior
            document.getElementById('firmaImzasiInput').click(); // Trigger file input click
        });

        // Event listener for firma imzasi file input change
        document.getElementById('firmaImzasiInput').addEventListener('change', handleFirmaImzasiInputChange);

        // Event listener for erasing button click for the firma imzasi
        document.getElementById('eraseFirmaImzasiButton').addEventListener('click', eraseFirmaImzasi);
    </script>

    <script>
        // Function to prevent default action on Enter key press for text input fields
        document.addEventListener('keydown', function (event) {
            const focusedElement = document.activeElement;
            if (event.key === 'Enter' && focusedElement.tagName === 'INPUT' && focusedElement.type === 'text') {
                event.preventDefault(); // Prevent default action
            }
        });
    </script>

    <script>
        // İl ve ilçe verileri
        const iller = ['İstanbul', 'Ankara', 'İzmir', 'Adana', 'Antalya', 'Bursa', 'Kocaeli'];
        const ilceler = {
            'İstanbul': ["Adalar",
                "Arnavutköy",
                "Ataşehir",
                "Avcılar",
                "Bağcılar",
                "Bahçelievler",
                "Bakırköy",
                "Başakşehir",
                "Bayrampaşa",
                "Beşiktaş",
                "Beykoz",
                "Beylikdüzü",
                "Beyoğlu",
                "Büyükçekmece",
                "Çatalca",
                "Çekmeköy",
                "Esenler",
                "Esenyurt",
                "Eyüpsultan",
                "Fatih",
                "Gaziosmanpaşa",
                "Güngören",
                "Kadıköy",
                "Kağıthane",
                "Kartal",
                "Küçükçekmece",
                "Maltepe",
                "Pendik",
                "Sancaktepe",
                "Sarıyer",
                "Silivri",
                "Sultanbeyli",
                "Sultangazi",
                "Şile",
                "Şişli",
                "Tuzla",
                "Ümraniye",
                "Üsküdar",
                "Zeytinburnu"],
            'Ankara': ["Akyurt",
                "Altındağ",
                "Ayaş",
                "Bala",
                "Beypazarı",
                "Çamlıdere",
                "Çankaya",
                "Çubuk",
                "Elmadağ",
                "Etimesgut",
                "Evren",
                "Gölbaşı",
                "Güdül",
                "Haymana",
                "Kalecik",
                "Kahramankazan",
                "Keçiören",
                "Kızılcahamam",
                "Mamak",
                "Nallıhan",
                "Polatlı",
                "Pursaklar",
                "Sincan",
                "Şereflikoçhisar",
                "Yenimahalle"],
            'İzmir': ["Aliağa",
                "Balçova",
                "Bayındır",
                "Bayraklı",
                "Bergama",
                "Beydağ",
                "Bornova",
                "Buca",
                "Çeşme",
                "Çiğli",
                "Dikili",
                "Foça",
                "Gaziemir",
                "Güzelbahçe",
                "Karabağlar",
                "Karaburun",
                "Karşıyaka",
                "Kemalpaşa",
                "Kınık",
                "Kiraz",
                "Konak",
                "Menderes",
                "Menemen",
                "Narlıdere",
                "Ödemiş",
                "Seferihisar",
                "Selçuk",
                "Tire",
                "Torbalı",
                "Urla"],
            'Adana': ["Aladağ",
                "Ceyhan",
                "Çukurova",
                "Feke",
                "İmamoğlu",
                "Karaisalı",
                "Karataş",
                "Kozan",
                "Pozantı",
                "Saimbeyli",
                "Sarıçam",
                "Seyhan",
                "Tufanbeyli",
                "Yumurtalık",
                "Yüreğir"],
            'Antalya': ["Aksu",
                "Alanya",
                "Demre",
                "Döşemealtı",
                "Elmalı",
                "Finike",
                "Gazipaşa",
                "Gündoğmuş",
                "Kaş",
                "Kemer",
                "Kepez",
                "Konyaaltı",
                "Korkuteli",
                "Kumluca",
                "Manavgat",
                "Muratpaşa",
                "Serik"],
            'Bursa': ["Büyükorhan",
                "Gemlik",
                "Gürsu",
                "Harmancık",
                "İnegöl",
                "İznik",
                "Karacabey",
                "Keles",
                "Kestel",
                "Mudanya",
                "Mustafakemalpaşa",
                "Nilüfer",
                "Orhaneli",
                "Orhangazi",
                "Osmangazi",
                "Yenişehir",
                "Yıldırım"],
            'Kocaeli': ["Başiskele",
                "Çayırova",
                "Darıca",
                "Derince",
                "Dilovası",
                "Gebze",
                "Gölcük",
                "İzmit",
                "Kandıra",
                "Karamürsel",
                "Kartepe",
                "Körfez"]
        };



        // İl ve ilçe inputlarını seçme
        const ilInput = document.getElementById('il');
        const ilceInput = document.getElementById('ilce');
        const ilSearchResults = document.getElementById('il-search-results');
        const ilceSearchResults = document.getElementById('ilce-search-results');

        // Click event listener to hide search results when clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.matches('#il, #ilce, .search-item')) {
                hideSearchResults();
            }
        });

        // İl inputuna yazıldıkça il araması yapma
        ilInput.addEventListener('input', function () {
            const searchText = this.value.trim();
            const filteredIller = iller.filter(function (il) {
                return new RegExp(searchText.replace('i', '[iıİİ]'), 'i').test(il);
            });
            displaySearchResults(filteredIller, ilSearchResults);
        });

        // İlçe inputuna yazıldıkça ilçe araması yapma
        ilceInput.addEventListener('input', function () {
            const secilenIl = ilInput.value.trim();
            if (!secilenIl) {
                // İl seçilmemişse ilçe araması yapma
                return;
            }
            const searchText = this.value.trim();
            const filteredIlceler = ilceler[secilenIl].filter(function (ilce) {
                return new RegExp(searchText.replace('i', '[iıİİ]'), 'i').test(ilce);
            });
            displaySearchResults(filteredIlceler, ilceSearchResults);
        });

        // Arama sonuçlarını gösterme
        function displaySearchResults(results, container) {
            container.innerHTML = '';
            results.forEach(function (result) {
                const item = document.createElement('div');
                item.textContent = result;
                item.classList.add('search-item');
                item.addEventListener('click', function () {
                    container.previousElementSibling.value = result;
                    hideSearchResults();
                });
                container.appendChild(item);
            });
            container.style.display = results.length > 0 ? 'block' : 'none'; // Show/hide results container
        }

        // Function to hide search results
        function hideSearchResults() {
            ilSearchResults.innerHTML = '';
            ilceSearchResults.innerHTML = '';
        }
    </script>

    <style>
        .search-results {
            max-height: 200px;
            /* Adjust the maximum height as needed */
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            position: absolute;
            z-index: 1000;
            width: 15%;
        }

        .search-item {
            cursor: pointer;
        }

        .search-item:hover {
            background-color: #f0f0f0;
        }
    </style>

</body>


</html>