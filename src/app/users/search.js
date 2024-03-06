function toDashboard() {
    const params = { page: 1 };
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
    }).catch(error => console.error(error));
}