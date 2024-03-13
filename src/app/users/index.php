<?php
    if ($_GET['s'] == '') {
        header('Location: search.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="search.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/global.css" rel="stylesheet" type="text/css"/>
    <script src="../../assets/js/components/componentsModule.js" type="module"></script>
    <script src="search.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/93710f8f6f.js" crossorigin="anonymous"></script>
    <!--<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
    <title>Planeswalker's Portal</title>
</head>
<body>
    <pw-nav></pw-nav>
    <button class="fx-row btn-transparent btn-sm" onclick="toSearch()">
        <i class="fa-solid fa-angles-left"></i>
        <p>Back to search</p>
    </button>
    <main>
        <section class="fx-row based spaced top10">
            <h1 id="username"></h1>
            <button class="btn-sm btn-outline btn-transparent">
                <i id="btn-friend"></i>
            </button>
        </section>
        <section class="fx-col">
            <span id="userDesc"></span>
            <span id="userAchievements"></span>
        </section>
        <section>
            <p id="bio" class="content-bio"></p>
        </section>
    </main>
</body>
<script> displayUserData(<?php echo '"'.$_GET['s'].'"'; ?>) </script>
</html>
