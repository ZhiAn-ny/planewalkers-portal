export const NotificationTypeEnum = {
    friendRequest: 1,
    friendAccepted: 2,
    message: 3
}

export class BaseNotification {
    id = 0;
    type = 0;
    title = '';
    content = '';
    targetUser = 0;
    sender = '';
    readFlag = false;
    createdAt = null;
}