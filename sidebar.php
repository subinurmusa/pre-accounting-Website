

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-black bg-opacity-75" style="height: 100%; width: 200px; flex-grow: 1; overflow-y: auto;">

    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
       
        <span class="fs-4">FineLogic</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
        <a href="dashbaordPage.php" class="nav-link head_item  d-flex gap-3 align-items-center">
    <i class="fa-solid fa-house-user me-2 "></i> Ana Sayfa
</a>
        </li>
        <li class="nav-item">
            <a class="nav-link head_item  d-flex gap-3 align-items-center" data-bs-toggle="collapse" data-bs-target="#home-collapse2">
            <i class="fa-solid fa-arrow-down-short-wide  "></i> Satışlar
            </a>
            <div class="collapse" id="home-collapse2">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="satislar.php" class="nav-link text-white">Satışlar</a></li>
                    <li class="nav-item"><a href="satisfatura.php" class="nav-link text-white">Faturalar</a></li>
                    <li class="nav-item"><a href="musteriler.php" class="nav-link text-white">Müşteriler</a></li>
                    <li class="nav-item"><a href="satisRaporu.php" class="nav-link text-white">Satış Raporu</a></li>
                   <!--  <li class="nav-item"><a href="#" class="nav-link text-white">Tahsilatlar Raporu</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Gelir Gider Raporu</a></li> -->
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link head_item d-flex gap-3 align-items-center" data-bs-toggle="collapse" data-bs-target="#home-collapse3">
            <i class="fa-solid fa-arrow-up-from-bracket "></i>  Giderler
            </a>
            <div class="collapse" id="home-collapse3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="giderler.php" class="nav-link text-white">GiderListesi</a></li>
                    <li class="nav-item"><a href="vendors.php" class="nav-link text-white">Tedarikçiler</a></li>
                    <li class="nav-item"><a href="employees.php" class="nav-link text-white">Çalışanlar</a></li>
                    <li class="nav-item"><a href="giderRaporu.php" class="nav-link  text-nowrap text-white">Giderler Raporu</a></li>
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-white">Ödemeler Raporu</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">KDV Raporu</a></li> -->
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link head_item d-flex gap-3 align-items-center" data-bs-toggle="collapse" data-bs-target="#home-collapse4">
            <i class="fa-regular fa-money-bill-1 "></i> Nakit
            </a>
            <div class="collapse" id="home-collapse4">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="satislar.php" class="nav-link text-nowrap text-white">Kasa Ve Bankalar</a></li>
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-white">Çekler</a></li> -->
                   <!--  <li class="nav-item"><a href="musteriler.php" class="nav-link text-white">Kasa/Banka Raporu</a></li> -->
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-white">Nakit Akışı Raporu</a></li> -->
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link head_item d-flex gap-3 align-items-center" data-bs-toggle="collapse" data-bs-target="#home-collapse5">
            <i class="fa-solid fa-cubes "></i> Stok
            </a>
            <div class="collapse" id="home-collapse5">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="urunvehizmet.php" class="nav-link  text-nowrap text-white">Hizmet Ve Ürünler</a></li>
             <!--        <li class="nav-item"><a href="#" class="nav-link text-white">Depolar</a></li>
                    <li class="nav-item"><a href="musteriler.php" class="nav-link text-white">Depolar Arası Transfer</a> -->
                    </li>
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-white">Giden İrsaliyeler</a></li> -->
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link head_item d-flex gap-3 align-items-center" data-bs-toggle="collapse" data-bs-target="#home-collapse6">
            <i class="fa-solid fa-gears "></i> Ayarlar
            </a>
            <div class="collapse" id="home-collapse6">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="firmaInfo.php" class="nav-link text-white">Firma Bilgileri</a></li>
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-nowrap text-white">Kategori Ve etiketler</a></li> -->
                    <li class="nav-item"><a href="userinfo.php" class="nav-link text-nowrap text-white">Kullanıcı Bilgilerim</a></li>
                    <!-- <li class="nav-item"><a href="#" class="nav-link text-white">Yazdırma Şablonları</a></li> -->
                </ul>
            </div>
        </li>
        </ul>

<hr>

<div class="dropdown">
    <a href="logout.php" class="d-flex head_item align-items-center text-white text-decoration-none ">
        <i class="fa-solid fa-right-from-bracket text-white me-2"></i> Çıkış
    </a>
</div>
<style>
        .head_item {
            color: #e0f7c6; /* Set the default text color */
            fontsize: 20px;
            transition: background-color 0.3s; /* Add transition for smooth color change */
        }

      
        .head_item:hover {
            background-color: #343a40;
            color: #d0f7a6; 
            /* Change background color on hover */
        }
    </style>
</div>