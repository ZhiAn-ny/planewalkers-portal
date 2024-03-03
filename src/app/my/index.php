<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./userProfile.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/global.css" rel="stylesheet" type="text/css"/>
    <script src="userProfile.js" type="text/javascript"></script>
    <script src="../../assets/js/components/componentsModule.js" type="module"></script>
    <script src="https://kit.fontawesome.com/93710f8f6f.js" crossorigin="anonymous"></script>
    <!--<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
    <title>Planeswalker's Portal</title>
</head>
<body>
    <pw-nav></pw-nav>
    <main>
        <section class="fx-row based top10">
            <h1 id="username"></h1>
            <button class="edit-btn">
                <a href="./edit.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </button>
        </section>
        <section>
            <span id="userDesc"></span>
            <p id="bio" class="content-bio"></p>
        </section>
    </main>
</body>
</html>
