// import { FriendshipService } from "./friendshipService";

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

    getNotificationDiv(notification) {
        const div = document.createElement('div');
        div.className = 'notification-item';
        this.#setDivStyle(div);
        div.appendChild(this.#getNotifDescriptionDiv(notification))
        div.appendChild(this.#getNotifActionsDiv(notification))
        return div;
    }

    #setDivStyle(div) {
        div.style.background = 'var(--accent)';
        div.style.color = 'var(--main)';
        div.style.borderRadius = '5px';
        div.style.padding = '3%';
    }

    #getNotifDescriptionDiv(notification) {
        const div = document.createElement('div');
        div.innerHTML = '<h2 class="notification-title">'+ notification.title +'</h2>'
            + '<p class="notification-text">'+ notification.content +'</p>';
        return div;
    }
    #getNotifActionsDiv(notification) {
        const div = document.createElement('div');
        div.className = 'action-container';
        div.innerHTML = '';
        switch (notification.type) {
            case 1: // NotificationTypeEnum.friendRequest
                div.innerHTML = '<button class="btn-notif-action btn-friend-accept"><i class="fa-lg fa-solid fa-check"></i></button>'
                    + '<button class="btn-notif-action btn-friend-decline"><i class="fa-lg fa-solid fa-xmark"></i></button>'
                    + '<button class="btn-notif-action btn-goto-user"><i class="fa-lg fa-solid fa-angles-right"></i></button>';
                div.querySelector('button.btn-notif-action.btn-friend-accept')
                    .addEventListener('click', () => {
                        console.log(notification);
                        console.log('---')
                        // FriendshipService.acceptFriendRequest(notification.sender, notification.target);
                    });
                div.querySelector('button.btn-notif-action.btn-friend-decline')
                .addEventListener('click', () => {
                    console.log(notification);
                    console.log('---')
                    // FriendshipService.rejectFriendRequest(notification.sender, notification.target);
                });
                div.querySelector('button.btn-notif-action.btn-goto-user')
                .addEventListener('click', () => {
                    console.log(notification);
                    console.log('---')
                });
                break;
            case 2: //NotificationTypeEnum.friendAccepted
                div.innerHTML = '<button class="button-primary" onclick="NotificationManager.openFriendProfile(this)">Open Profile</button>';
                break;
        }
        return div;
    }

}
