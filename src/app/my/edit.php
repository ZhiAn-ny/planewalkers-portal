<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./userProfile.css" rel="stylesheet" type="text/css"/>
    <link href="../../assets/css/global.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://kit.fontawesome.com/93710f8f6f.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="userProfile.js" type="text/javascript"></script>
    <script src="../../assets/js/components/componentsModule.js" type="module"></script>
    <!--<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
    <title>Planeswalker's Portal</title>
    
</head>
<body>
    <pw-nav></pw-nav>
    <main>
        <button class="fx-row btn-transparent btn-sm" onclick="toProfile()">
            <i class="fa-solid fa-angles-left"></i>
            <p>Back to profile</p>
        </button>
        <form action="" method="post" autocomplete="off" class="fx-col">
            <section>
                <div class="h1-input-container">
                    <input type="text" name="username" id="username"
                    value=""
                    required>
                    <span>Username:</span>
                </div>
                <div>
                    <span>Name:</span>
                    <input type="text" name="name" id="name"
                    value="">
                </div>
                <div>
                    <span>Bio:</span>
                    <textarea name="bio" id="bio" rows="5"
                    ></textarea>
                </div>
                <span id="since"></span>
            </section>
        </form>
        <div  class="action-btn-container">
            <button type="submit" name="submit" onclick="saveUser()">Save Changes</button>
            <!-- <button class="delete-btn">Exit Edit</button> -->
        </div>
    </main>
</body>
</html>
