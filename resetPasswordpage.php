<?php
require 'db.php';

$token = $_GET['token'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $token = $_POST['token'];

    // Server-side validation for password criteria
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters long and include a mix of uppercase, lowercase, numbers, and special characters.";
    } else {
        $query = $db->prepare("SELECT * FROM users WHERE reset_token = :token AND token_expiry > NOW()");
        $query->execute(['token' => $token]);
        $user = $query->fetch();

        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = $db->prepare("UPDATE users SET password = :password, reset_token = NULL, token_expiry = NULL WHERE reset_token = :token");
            $query->execute(['password' => $hashedPassword, 'token' => $token]);

            $error= ' <div style="background-color: #dff0d8; padding: 15px; border-radius: 5px;">
            <p style="color: #3c763d; font-size: 16px;">Şifreniz Başarıyla Değiştirildi</p>
            <p>Artık yeni şifrenizle sitemize giriş yapabilirsiniz.</p>
            <p style="margin-top: 10px;"><a href="http://www.finelogicofficial.com/login.php" style="text-decoration: none; color: #31708f; font-weight: bold;">Buradan</a> Siteye Giriş Yapabilirsiniz</p>
        </div>';
        } else {
            $error = "Geçersiz veya süresi dolmuş token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Reset Password</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
    <link href="signin.css" rel="stylesheet">
    <script>
        function validatePassword() {
            const password = document.getElementById("password").value;
            const error = document.getElementById("error");
            const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!regex.test(password)) {
                error.textContent = "Şifreniz en az 8 karakter uzunluğunda olmalı, büyük harf, küçük harf, sayı ve özel karakter içermelidir.";
                return false;
            }

            error.textContent = "";
            return true;
        }
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <form action="resetPasswordpage.php" method="post" onsubmit="return validatePassword()">
            <div class="col-3-md justify-content-center d-flex">
                <div class="d-grid gap-3 justify-content-center align-items-center w-25 p-4 shadow shadow-5">
                    <div>
                        <h2>Şifre Sıfırlama İşlemi </h2>
                    </div>
                    <div>
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <label for="password">Yeni Şifre Ayarla:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div id="error" class="text-danger"><?php echo $error; ?></div>
                   
                    <div>
                        <button type="submit" class="btn btn-warning">Sıfırla</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
