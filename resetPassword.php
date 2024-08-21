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
                        <h2>Reset Password</h2>
                    </div>
                    <div>
                       
                            <label for="email">Email:</label>
                            <input type="email" id="email" class="form-control" name="email" required>
                    
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-warning">Send Reset Link</button>

                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>

</body>

</html>