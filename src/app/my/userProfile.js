let user;
toastr.options = {
    positionClass: 'toast-top-center',
    closeButton: true,
    progressBar: true,
    stopOnFocus: true,
};

function displayUserData() {
    const username = document.getElementById("username");
    const desc = document.getElementById("userDesc");
    const achs = document.getElementById("userAchievements");
    const bio = document.getElementById("bio");
    username.innerText = user.username;
    bio.innerText = user.bio;
    desc.innerText = getUserDesc(user);
    achs.innerHTML = getAchsHtml();
}

function getUserDesc() {
    let desc = "";
    if (user.name !== "") {
        desc = user.name + " | ";
    }
    desc += "LV. " + user.lv + " (" + user.xp + " XP)\n" 
        + "User since: " + user.since;
    return desc;
}

function getAchsHtml() {
    let innerHTML = '';
    if (user.achievements) {
        for (var i = 0; i < user.achievements.length; i++) {
            const html = '<i class="' + user.achievements[i].faClass + '"></i>'
            innerHTML += html + '\n';
        }
    }
    return innerHTML;
}

function initUserForm() {
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const bio = document.getElementById("bio");
    const since = document.getElementById("since");
    username.value = user.username;
    name.value = user.name;
    bio.value = user.bio;
    since.innerText = "User since: " + user.since;
}

function saveUser() {
    user.username = document.getElementById("username").value;
    user.name = document.getElementById("name").value;
    user.bio = document.getElementById("bio").value;
    update();
}

function update() {
    const params = { action: 'update', user: JSON.stringify(user) };
    fetch('http://localhost/pwp/src/app/lib/user_functions.php', {
        method: 'PATCH',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: JSON.stringify(params)
    }).then(response => {
        if (!response.ok) {
            console.error(response);
            throw new Error("UpdateUser() failed");
        }
        return response.json();
    }).then(response => {
        if (response.ok) {
            toastr.success("Update successful");
            return JSON.parse(response.message).achievements;
        } else {
            throw new Error("UpdateUser() failed");
        }
    }).then(achievements => {
        achievements.forEach(ach => {
            let text = ach.desc;
            text += (ach.xp) ? ' (' + ach.xp + ' XP)' : '';
            const innerHTML =  `<div class="fx-row">
                <i class="notification-icon fa-xl `+ ach.faClass +`" style="margin-top: 4%;"></i>
                <div class="notification-content">
                    <h2 class="notification-title">`+ ach.name +`</h2>
                    <p class="notification-text">`+ text +`</p>
                </div>
            </div>`;
            toastr.info(innerHTML);
        })
    }).catch(error => {
        toastr.error("Something went wrong!");
        console.error(error);
    });
}

function toProfile() {
    const params = { page: 3 };
    fetch('http://localhost/pwp/src/app/lib/routing_functions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: JSON.stringify(params)
    }).then(response => {
        if (!response.ok) {
            throw new Error("Redirect failed");
        }
        console.log('Redirect ok');
        return response.json();
    }).then(response => {
        window.location.href = response.url;
    }).catch(error => console.error(error));
}

fetch('http://localhost/pwp/src/app/lib/user_functions.php?username=', {
    method: 'GET',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
}).then(response => {
    if (!response.ok) {
        throw new Error("GetCurrentUser() failed");
    }
    return response.json();
}).then(response => {
    user = JSON.parse(response.message);
    if (location.pathname.includes("my/edit.php")) {
        initUserForm();
    } else {
        displayUserData();
    }
}).catch(error => console.error(error));
