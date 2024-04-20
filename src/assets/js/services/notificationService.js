import { FriendshipService } from "./friendshipService.js";

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

    /* Functions regarding notifications' appearance */

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

    #addGoToUserBtn(div, notification) {
        const btn = document.createElement('button');
        btn.className = 'btn-notif-action btn-goto-user';
        btn.innerHTML = '<i class="fa-lg fa-solid fa-angles-right"></i>';
        btn.addEventListener('click', () => {
            fetch('http://localhost/pwp/src/app/lib/user_functions.php?a=0&uid=' + notification.sender, {
                method: 'GET',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(response => response.ok ? response.json() : null)
            .then(response => JSON.parse(response.message))
            .then(user => window.location.href = 'http://localhost/pwp/src/app/users/?s=' + user.username)
            .catch(error => console.error(error));
        });
        div.appendChild(btn);
    }

    #addMarkReadBtn(div, notification) {
        const btn = document.createElement('button');
        btn.className = 'btn-notif-action btn-mark-read';
        btn.innerHTML = '<i class="fa-lg fa-solid fa-envelope-open"></i>';
        btn.addEventListener('click', () => {
            fetch('http://localhost/pwp/src/app/lib/notifications_functions.php?', {
                method: 'PATCH',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: JSON.stringify({nid: notification.id})
            }).then(response => response.ok ? response.json() : null)
            .catch(error => console.error(error));
        });
        div.appendChild(btn);
    }

    #getNotifActionsDiv(notification) {
        const div = document.createElement('div');
        div.className = 'action-container';
        div.innerHTML = '';
        switch (notification.type) {
            case 1: // NotificationTypeEnum.friendRequest
                div.innerHTML = '<button class="btn-notif-action btn-friend-accept"><i class="fa-lg fa-solid fa-check"></i></button>'
                    + '<button class="btn-notif-action btn-friend-decline"><i class="fa-lg fa-solid fa-xmark"></i></button>';
                div.querySelector('button.btn-notif-action.btn-friend-accept')
                    .addEventListener('click', () => FriendshipService
                        .acceptFriendRequest(notification.id, notification.sender, notification.targetUser)
                        .then((r) => div.hidden = r.ok));
                div.querySelector('button.btn-notif-action.btn-friend-decline')
                .addEventListener('click', () => FriendshipService
                    .rejectFriendRequest(notification.id, notification.sender, notification.targetUser));
                this.#addGoToUserBtn(div, notification);
                break;
            case 2: //NotificationTypeEnum.friendAccepted
                this.#addGoToUserBtn(div, notification);
                this.#addMarkReadBtn(div, notification);
                break;
        }
        return div;
    }

}
