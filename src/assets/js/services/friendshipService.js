export class FriendshipService {
    /**
     * Sends a friend request to the specified user.
     * @param {number} targetId the id of the user that will receive the friend request
     * @returns promise indicating when the action has ended
     */
    static sendFriendRequest(targetId) {
        const params = { t: targetId };
        return fetch('http://localhost/pwp/src/app/lib/friends_functions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: JSON.stringify(params)
        });
    }
    
    /**
     * Revokes the friend request to the specified user.
     * @param {number} targetId 
     * @returns promise indicating when the action has ended
     */
    static revokeFriendRequest(targetId) {
        const params = { t: targetId};
        return fetch('http://localhost/pwp/src/app/lib/friends_functions.php', {
            method: 'DELETE',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: JSON.stringify(params)
        });
    }

    /**
     * Check the friendship status between the current user and another
     * @param {number} otherUserId the id of the other user
     * @returns promise of an object indicating the sender of the request,
     * the target and the friendship status
     */
    static getFriendshipStatus(otherUserId) {
        return fetch('http://localhost/pwp/src/app/lib/friends_functions.php?t=' + otherUserId, {
            method: 'GET',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(response => response.json())
        .then(response => JSON.parse(response.message));
    }

    static acceptFriendRequest(notificationID, sender, target) {
        const params = { sender: sender, t: target, accepted: 'true', nid: notificationID };
        return fetch('http://localhost/pwp/src/app/lib/friends_functions.php', {
            method: 'PATCH',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: JSON.stringify(params)
        });
    }

    static rejectFriendRequest(notificationID, sender, target) {
        const params = { sender: sender, t: target, accepted: 'false', nid: notificationID };
        return fetch('http://localhost/pwp/src/app/lib/friends_functions.php', {
            method: 'PATCH',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: JSON.stringify(params)
        });
    }

}