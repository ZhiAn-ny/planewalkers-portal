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
    <button class="fx-row btn-transparent btn-sm" onclick="toDashboard()">
        <i class="fa-solid fa-angles-left"></i>
        <p>Back to dashboard</p>
    </button>
    <main>
        <section>
            <form class="fx-row">
                <div class="search-container">
                    <input id="search-input" type="text" 
                    placeholder="Search user..."
                    oninput="searchUser(this.value)">
                    <button class="btn-transparent" onclick="searchUser()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </section>
        <section>
        </section>
    </main>
</body>
</html>
