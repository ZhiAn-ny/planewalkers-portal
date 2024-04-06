export class NotificationService {
    /** Reads the notifications for the current user. 
     * @param {boolean} [onlyPending=true] incates if only pending notifications should be read 
     */
    static getNotifications(onlyPending = true) {
        return fetch('http://localhost/pwp/src/app/lib/notifications_functions.php?'
        + 'p=' + onlyPending, {
            method: 'GET',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(response => {
            if (!response.ok) {
                throw new Error("Couldn't read the notifications.");
            }
            return response.json();
        }).catch(error => console.error(error));
    }

}
