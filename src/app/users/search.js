let user;

function toDashboard() {
    redirect(1);
}

function toSearch() {
    redirect(4);
}

function redirect(pageId) {
    const params = { page: pageId };
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

function searchUser(username) {
    console.log('searching: ', username);
    let url = 'http://localhost/pwp/src/app/lib/user_functions.php';
    url += '?ssu=1&username=' + username;
    fetch(url, {
        method: 'GET',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(response => {
        if (!response.ok) {
            throw new Error("GetUsernameLike() failed");
        }
        return response.json();
    }).then(response => {
        users = JSON.parse(response.message);
        console.log('users: ', users);
        showResults(users);
    }).catch(error => console.error(error));
}

function createResultItem(user) {
    const div = document.createElement('div');
    div.className = 'search-item';
    div.innerHTML = '<p>'+user+'</p>';
    div.addEventListener('click', () => getUserPage(user))
    return div;
}

function showResults(users) {
    const container = document.getElementById('results-container');
    while (container.children.length > 0) {
        container.removeChild(container.firstChild);
    }
    if (users.length === 0) {
        let notFound = document.createElement('div');
        notFound.innerText = 'No user found with this username';
        container.appendChild(notFound);
        return;
    }
    for (let i = 0; i < users.length; i++) {
        let searchItem = createResultItem(users[i]);
        container.appendChild(searchItem);
    }
}

function getUserPage(username) {
    window.location.href = 'http://localhost/pwp/src/app/users?s=' + username;
}

async function displayUserData(toDisplay) {
    user = await fetch('http://localhost/pwp/src/app/lib/user_functions.php?username=' + toDisplay, {
        method: 'GET',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(response => response.json())
    .then(response => JSON.parse(response.message));

    let friendshipStatus = await fetch('http://localhost/pwp/src/app/lib/friends_functions.php?t=' + toDisplay, {
        method: 'GET',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(response => response.json())
    .then(response => response.message);

    handleFriendRequestBtn(friendshipStatus);
    displayData();
}

function displayData() {
    const username = document.getElementById("username");
    const desc = document.getElementById("userDesc");
    const achs = document.getElementById("userAchievements");
    const bio = document.getElementById("bio");
    username.innerText = user.username;
    bio.innerText = user.bio;
    desc.innerText = getUserDesc(user);
    achs.innerHTML = getAchsHtml();
}

function handleFriendRequestBtn(friendshipStatus) {
    const btn = document.getElementById("btn-friend");
    let iconClass;
    switch (friendshipStatus) {
        case "accepted":
            iconClass = ["fa-solid", "fa-user-check"]
            break;
        case "pending":
            iconClass = ["fa-solid", "fa-user-clock"]
            break;
        default:
            iconClass = ["fa-solid", "fa-user-plus"]
    }
    const icon = btn.getElementsByTagName("i")[0];
    iconClass.forEach(className => icon.classList.add(className));
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
