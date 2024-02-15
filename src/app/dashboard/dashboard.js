const greetUser = document.getElementById("greetings");
const greetingsList = ["Hi", "Hello", "May the light shine upon you,", "Long time no see,"];

let greet = greetingsList[Math.floor(Math.random()*greetingsList.length)];
let user;

const params = { action: 'get', username: '' };
fetch('http://localhost/pwp/src/app/lib/user_functions.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: JSON.stringify(params)
}).then(response => {
    if (!response.ok) {
        throw new Error("GetCurrentUser() failed");
    }
    console.log('Redirect ok');
    console.log(response);
    user = JSON.parse(response.message);
}).catch(error => console.error(error));

//greetUser.innerText = greet + 
