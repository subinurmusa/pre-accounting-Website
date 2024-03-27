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
  <title>register</title>
</head>
<style>
  body {
    background-color: cornflowerblue;
  }
</style>

<?php 
$name = isset($_POST["name"]) ? $_POST["name"] : null;
$lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
$username = isset($_POST["username"]) ? $_POST["username"] : null;
$email = isset($_POST["email"]) ? $_POST["email"] : null;
$password1 = isset($_POST["password1"]) ? $_POST["password1"] : null;
$password2 = isset($_POST["password2"]) ? $_POST["password2"] : null;
$error = "";

if (isset($_POST["submit"])) {
  include "db.php";

  $sql=$db->prepare("SELECT * FROM users WHERE  username='".$username."'");
  $sql->execute();
  $result=$sql->fetch(PDO::FETCH_ASSOC);
  echo "gel";
  if($result==null){
// if it's empty thers is no user like that so you can create that user 
if (empty($username) || empty($password1) || empty($password2) || empty($email)) {
  $error = "<div class='alert alert-danger'> tüm alanlar doldurulmalı</div>";

} else if ($password1 != $password2) {
  $error = "<div class='alert alert-danger'> şifreler birbiriyle uyyuşmuyor </div>";

}  else {
  echo $username."else box in side";
  //add 
  $sql1 = $db->prepare("INSERT INTO `users`(`username`, `name`, `lastname`, `email`, `password`) VALUES (?,?,?,?,?)");
  $sql1->execute([$username, $name, $lastname, $email, md5($password1)]);
  // read 
  $sql = $db->prepare("SELECT * FROM `users` WHERE PASSWORD= '".md5($password1)."' and username='".$username."';");

  $sql->execute();

  $result1 = $sql->fetch(PDO::FETCH_ASSOC);
  echo $result1["username"]."mmm";
  if ($result1) {
    echo $result1["username"]."mmm";
      // User authentication successful
     session_start();
      $_SESSION["username"] = $username;
      $_SESSION["name"] = $result1["name"];
      $_SESSION["email"] = $result1["email"];
      $_SESSION["name"] = $result1["name"];

      $error = "<div class='alert alert-success'>Kayıt Başarılı</div>";
     header("Location: dashbaordPage.php"); // Redirect user to another page

  } else {
      $error = "<div class='alert alert-danger'>bbbbbbbbbbb</div>";
  }
}

}else{

$error = "<div class='alert alert-danger'>Böle Bir kullanici zatenvar</div>";
}

 
}

?>

<body class="">
  <div class="container  d-flex justify-content-center align-items-center">

    <div class="col-md-3 m-4 d-flex justify-content-center align-items-center  " style="width:100%;">
      <div class=" card justify-content-center p-4 m-5 mt-5 shadow-lg align-items-center w-50" >
        <div class="card-title fs-3 text-primary d-flex gap-3 align-items-center">
          <p class="mb-0"> Bilgilerinizi Doldurun</p>
          <i class="fa-solid fa-file-invoice text-secondary fs-1"></i>
        </div>
        <form class="w-100 card-cody" method="POST">

          <div class="mb-3">
            <label class="form-label">İsim</label>
            <input name="name" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">SoyAd</label>
            <input name="lastname" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Kullanıcı Adi</label>
            <input  name="username" class="form-control">
          </div>
        
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" aria-describedby="emailHelp">

          </div>
          <div class="mb-3">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="password1" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Şifre Doğurulama</label>
            <input type="password" name="password2" class="form-control">
          </div>
          <div class="mb-3">
            <?php echo $error?>
          </div>
          <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary" name="submit" >Submit</button>
          <a href="login.php" class="btn btn-secondary"> Go Back</a>
          </div>
        </form>
      </div>


    </div>
  </div>
</body>

</html>