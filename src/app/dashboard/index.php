<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dashboard.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/global.css" rel="stylesheet" type="text/css"/>
    <script src="https://kit.fontawesome.com/93710f8f6f.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="../../assets/js/components/componentsModule.js" type="module"></script>
    <script src="dashboard.js" type="text/javascript"></script>
    <!--<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
    <title>Planeswalker's Portal</title>
</head>
<body>
    <pw-nav></pw-nav>
    <main>
        <h1 id="greetings"></h1>
        <section>
            <h2>My friends</h2>
            <ul class="fx-row">
                <li>A</li>
                <li>B</li>
                <li class="new" onclick="addFriend()">+</li>
            </ul>
        </section>
    </main>
</body>
</html>
