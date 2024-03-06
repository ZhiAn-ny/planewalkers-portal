
function setGreetings(user) {
    const greetingsList = [
        "Hi", "Hello", "Greetings", "May the light shine upon you,",
        "Long time no see,", "Nice to see you,"
    ];
    const greet = greetingsList[Math.floor(Math.random()*greetingsList.length)];
    const name = user.name ? user.name : user.username;
    const greetUser = document.getElementById("greetings");
    greetUser.innerText = greet + " " + name;
}

function addFriend() {
    const params = { page: 4 };
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
    setGreetings(user);
}).catch(error => console.error(error));

