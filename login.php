<?php
require "db.php";
session_start();

$username = isset($_POST["username"]) ? $_POST["username"] : null;
$org_password = isset($_POST["password"]) ? $_POST["password"] : null;
$error = "";

if (isset($_POST["submit"])) {
    if (empty($username) || empty($org_password)) {
        $error = "<div class='alert alert-danger mt-1'>Bütün alanlar doldurulmalı</div>";
    } else {
        // Prepare the SQL query to retrieve the user with the given username
        $sql = $db->prepare("SELECT * FROM users WHERE username = :username");
    
        // Execute the query
        $sql->execute(['username' => $username]);
        
        // Fetch the result
        $result = $sql->fetch(PDO::FETCH_ASSOC);


        if ($result && password_verify($org_password, $result['password'])) {
            // User authentication is successful
            $_SESSION["username"] = $result["username"];
            $_SESSION["name"] = $result["name"];
            $_SESSION["email"] = $result["email"];

            $error = "";
            header("Location: dashbaordPage.php");
            exit(); // Ensure no further code is executed
        } else {
            $error = "<div class='alert alert-danger mt-1'>Kullanıcı adı veya şifre hatalıdır </div>";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Login</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <link href="signin.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-between p-0 m-0">
<div class="form-signin w-50">
    <form method="POST">
        <h1 class="h3 mb-3 fw-normal">Giriş Bilgilerinizi Doldurun</h1>
        <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" name="username" required>
            <label for="floatingInput">Kullanıcı Adı</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
            <label for="floatingPassword">Şifre</label>
        </div>
        <div class="checkbox mb-3">
            <label>
                <a href="resetPassword.php" class="mt-1">Şifremi Unuttum</a>
            </label>
        </div>
        <?php echo $error ?>
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Giriş Yap</button>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="text-white btn btn-secondary mt-1 gap-1"><i class="fa-solid fa-arrow-left pe-1"></i>Geri</a>
            <a href="register.php" class="mt-1">Üye değilmisin? üye Ol</a>
        </div>
        <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
    </form>
</div>
<div class="w-50 h-100 p-3 bg-primary">
    <div class="d-grid gap-5">
        <div class="p-4">
            <p class="p-3 pb-0 mb-0 fs-4 text-white">CogniLedger</p>
            <p class="text-secondary">Şirket yönetim sistemi</p>
        </div>
        <div class="main-text shadow p-4 mb-5 rounded">
            <p class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti rem velit et, dolorum, recusandae expedita mollitia necessitatibus perspiciatis pariatur beatae ullam voluptatum fugiat dicta alias minima possimus veniam quod nobis. A eveniet maiores veritatis sint. Neque soluta ipsum cum ipsa tempore? Repellat ut deserunt velit odit distinctio consectetur inventore temporibus.</p>
        </div>
        <div class="d-flex justify-content-center mt-5">
            <p class="text-white">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugit, iure?</p>
        </div>
    </div>
</div>
</body>
</html>
