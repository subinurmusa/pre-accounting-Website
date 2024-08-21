<?php
// Include database connection file
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = $db->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $query = $db->prepare("UPDATE users SET reset_token = :token, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $query->execute(['token' => $token, 'email' => $email]);

        $resetLink = "http://www.finelogicofficial.com/resetPasswordpage.php?token=" . $token;

        // Email subject
        $subject = "Password Reset Request";

        // Email body
        $message = '
            <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <p>Merhaba,</p>
                <p>Hesabınız için bir şifre sıfırlama isteği yaptınız.</p>
                <p>Lütfen şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:</p>
                <p><a href="' . $resetLink . '">Şifreyi Sıfırla</a></p>
                <p>Eğer bu sıfırlamayı talep etmediyseniz, bu e-postayı görmezden gelebilirsiniz.</p>
                <p>Teşekkür ederiz!</p>
            </body>
            </html>
        ';

        // Headers for HTML email
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';

        // Additional headers
        $headers[] = 'From: Finelogicofficial <mail.finelogicofficial.com>'; // Change this to your website name and email
        $headers[] = 'Reply-To: Finelogicofficial <mail.finelogicofficial.com>'; // Change this to your website name and email

        // Send email
        mail($email, $subject, $message, implode("\r\n", $headers));

        echo '
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>

    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.88.1">


        <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

        <link rel="stylesheet" href="css/login.css">

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/0a431f04f0.js" crossorigin="anonymous"></script>
        <link href="signin.css" rel="stylesheet">
    </head>
</head>

<body>
    <div class="container">
        <div class="row">
        <form action="send_reset_link.php" method="post">
            <div class="col-3-md justify-content-center d-flex">
                <div class="d-grid gap-3 justify-content-center align-items-center w-25 p-4 shadow shadow-5">


                    <div>
                        <h2>Şifreyi Sıfırla</h2>
                    </div>
                   
                    
                    <div>
                        <h5>Şifre sıfırlama işlemi başarıyla tamamlandı. Lütfen e-posta hesabınızı kontrol edin. </5>

                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>

</body>

</html>
        ';
    } else {
        echo "Bu e-posta adresi ile ilişkili kullanıcı bulunamadı.";
    }
}
?>
