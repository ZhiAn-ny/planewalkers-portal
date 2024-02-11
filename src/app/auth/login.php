<?php
    require "../lib/auth_functions.php";
    if (isset($_POST['submit'])) {
        $resp = login($_POST['email'], $_POST['password']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./auth.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/global.css" rel="stylesheet" type="text/css"/>
    <script src="https://kit.fontawesome.com/93710f8f6f.js" crossorigin="anonymous"></script>
    <!--<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
    <title>Planeswalker's Portal - Login</title>
</head>
<body>
    <main>
        <section class="fx-col">
            <h1>Welcome back, adventurer!</h1>
            <form action="" method="post" autocomplete="off" class="fx-col">
                <div>
                    <span>Email or username:</span>
                    <input type="text" name="email" 
                        value="<?php echo @$_POST['email']; ?>"
                        required>
                </div>
                <div>
                    <span>Password:</span>
                    <input type="password" name="password"
                        value="<?php echo @$_POST['password']; ?>"
                        required>
                </div>
                <button type="submit" name="submit">Log in</button>
            </form>
        </section>
        <section class="form-footer">
            <p>
                New here?
                <a href="./signin.php">Register Now!</a>
            </p> 

            <?php if (@$resp == "") { ?>
            <?php } else if (@$resp == "0") { ?>
                <p class="success">Success!</p>
            <?php } else { ?>
                <span class="error fx-row">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p><?php echo @$resp ?></p>
                </span>
            <?php } ?>
        </section>
    </main>
</body>
</html>
