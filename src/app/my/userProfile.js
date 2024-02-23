let user;

function displayUserData() {
    const username = document.getElementById("username");
    const desc = document.getElementById("userDesc");
    const bio = document.getElementById("bio");
    username.innerText = user.username;
    bio.innerText = user.bio;
    desc.innerText = getUserDesc(user);
}

function getUserDesc() {
    let desc = "";
    if (user.name !== "") {
        desc = user.name + " | ";
    }
    desc += "LV. " + user.lv + "\n" 
        + "User since: " + user.since;
    return desc;
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
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: JSON.stringify(params)
    }).then(response => {
        if (!response.ok) {
            console.error(response);
            throw new Error("UpdateUser() failed");
        }
    }).then(response => {
        console.log(response);
    }).catch(error => console.error(error));
}

const params = { action: 'get', username: '' };
fetch('http://localhost/pwp/src/app/lib/user_functions.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: JSON.stringify(params)
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
