<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
  <title>Register</title>
</head>
<style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      max-width: 500px;
      width: 100%;
      margin-top: 50px;
      margin-bottom: 50px;
    }

    .form-text {
      color: #6c757d;
    }
</style>

<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$name = isset($_POST["name"]) ? $_POST["name"] : null;
$lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
$username = isset($_POST["username"]) ? $_POST["username"] : null;
$email = isset($_POST["email"]) ? $_POST["email"] : null;
$password1 = isset($_POST["password1"]) ? $_POST["password1"] : null;
$password2 = isset($_POST["password2"]) ? $_POST["password2"] : null;
$companyName = isset($_POST["companyName"]) ? $_POST["companyName"] : null;
$passwordHelp = isset($_POST["passwordHelp"]) ? $_POST["passwordHelp"] : null;
$passwordMatch = isset($_POST["passwordMatch"]) ? $_POST["passwordMatch"] : null;

if (isset($_POST["submit"])) {
  include "db.php";

  $sql = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR companyName = ?");
  $sql->execute([$username, $email, $companyName]);
  $result = $sql->fetch(PDO::FETCH_ASSOC);

  $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);
  if ($result == null) {
    if (empty($username) || empty($password1) || empty($password2) || empty($email) || empty($companyName)) {
      $error = "<div class='alert alert-danger'>Tüm alanlar doldurulmalı</div>";
    } else if ($password1 != $password2 || $passwordHelp != "Şifreniz geçerli.") {
      $error = "<div class='alert alert-danger'>Şifreniz geçerli değil / Şifreler birbiriyle uyuşmuyor</div>";
    } else {
      $sql1 = $db->prepare("INSERT INTO `users`(`username`, `name`, `lastname`, `email`, `password`, `companyName`) VALUES (?,?,?,?,?,?)");
      $sql1->execute([$username, $name, $lastname, $email, $hashedPassword, $companyName]);
      
      $sql = $db->prepare("SELECT * FROM `users` WHERE password = ? AND username = ?");
      $sql->execute([$hashedPassword, $username]);
      $sqluser = $sql->fetch(PDO::FETCH_ASSOC);

      $sqlcom = $db->prepare("INSERT INTO `companyinfo` (`companyName`, `userId`) VALUES (?, ?)");
      $sqlcom->execute([$companyName, $sqluser["id"]]);

      if ($sqluser) {
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["name"] = $sqluser["name"];
        $_SESSION["email"] = $sqluser["email"];

        $error = "<div class='alert alert-success'>Kayıt Başarılı</div>";
        header("Location: dashbaordPage.php");
        exit();
      }
    }
  } else {
    $error = "<div class='alert alert-danger'>Böyle Bir <b>kullanıcı adı</b>, <b>email</b>, veya <b>Şirket adı</b> zaten kayıtlı</div>";
  }
}
?>

<body>
  <div class="container d-flex justify-content-center mt-5 mb-5 align-items-center vh-100">
    <div class="card p-4 shadow-lg">
      <div class="card-title d-flex gap-3 text-center mb-4">
        <h3 class="text-primary">Bilgilerinizi Doldurun</h3>
        <i class="fa-solid fa-file-invoice text-secondary fs-1"></i>
      </div>
      <form class="w-100" method="POST" action="register.php" onsubmit="return validateForm()">
        <div class="mb-3">
          <label class="form-label">İsim</label>
          <input name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">SoyAd</label>
          <input name="lastname" class="form-control" value="<?php echo htmlspecialchars($lastname); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Firma Adı</label>
          <input name="companyName" class="form-control" value="<?php echo htmlspecialchars($companyName); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Kullanıcı Adı</label>
          <input name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Yeni Şifre</label>
          <input type="password" id="password1" name="password1" class="form-control">
          <small id="passwordHelpText" class="form-text">
            Şifreniz en az 8 karakter uzunluğunda olmalı, büyük harf, küçük harf, sayı ve özel karakter içermelidir.
          </small>
        </div>
        <div class="mb-3">
          <label class="form-label">Şifre Doğrulama</label>
          <input type="password" id="password2" name="password2" class="form-control">
          <small id="passwordMatchText" class="form-text"></small>
        </div>
        <div class="mb-3">
          <?php echo isset($error) ? $error : ""; ?>
        </div>
        <input type="hidden" id="passwordHelp" name="passwordHelp" value="">
        <input type="hidden" id="passwordMatch" name="passwordMatch" value="">
        <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary" name="submit">Submit</button>
          <a href="login.php" class="btn btn-secondary">Go Back</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const password1 = document.getElementById('password1');
      const password2 = document.getElementById('password2');
      const passwordHelpText = document.getElementById('passwordHelpText');
      const passwordMatchText = document.getElementById('passwordMatchText');
      const passwordHelp = document.getElementById('passwordHelp');
      const passwordMatch = document.getElementById('passwordMatch');

      function validatePassword() {
        const password = password1.value;
        const criteria = [
          { regex: /.{8,}/, message: "en az 8 karakter" },
          { regex: /[A-Z]/, message: "bir büyük harf" },
          { regex: /[a-z]/, message: "bir küçük harf" },
          { regex: /[0-9]/, message: "bir sayı" },
          { regex: /[^A-Za-z0-9]/, message: "bir özel karakter" }
        ];

        const failedCriteria = criteria.filter(c => !c.regex.test(password)).map(c => c.message);
        if (failedCriteria.length > 0) {
          passwordHelpText.textContent = `Şifreniz ${failedCriteria.join(', ')} içermelidir.`;
          passwordHelpText.style.color = 'red';
          passwordHelp.value = `Şifreniz ${failedCriteria.join(', ')} içermelidir.`;
        } else {
          passwordHelpText.textContent = "Şifreniz geçerli.";
          passwordHelpText.style.color = 'green';
          passwordHelp.value = "Şifreniz geçerli.";
        }
      }

      function validatePasswordMatch() {
        if (password1.value !== password2.value) {
          passwordMatchText.textContent = "Şifreler uyuşmuyor.";
          passwordMatchText.style.color = 'red';
          passwordMatch.value = "Şifreler uyuşmuyor.";
        } else {
          passwordMatchText.textContent = "";
          passwordMatch.value = "";
        }
      }

      function validateForm() {
        validatePassword();
        validatePasswordMatch();
        return passwordHelp.value === "Şifreniz geçerli." && passwordMatch.value === "";
      }

      password1.addEventListener('input', validatePassword);
      password2.addEventListener('input', validatePasswordMatch);
      window.validateForm = validateForm;// for making  the validateForm function accessible globally. 
      //This means you can call validateForm from anywhere in your code or even from the browser's console by just typing validateForm()
    });
  </script>
</body>

</html>
