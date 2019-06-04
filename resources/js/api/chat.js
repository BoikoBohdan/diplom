import request from './request'

export function getUserList () {
    return request({
        url: `/api/chat/user-list`,
        method: 'get'
    })
}

export function getRoomList () {
    return request({
        url: `/api/chat/room-list`,
        method: 'get'
    })
}

export function createRoom (payload) {
    return request({
        url: `/api/chat/create-room`,
        method: 'post',
        data: payload
    })
}

export function getMessageList (room) {
    return request({
        url: `/api/chat/history/${room}`,
        method: 'get'
    })
}

export function createMessage (message) {
    return request({
        url: `/api/chat/create-message`,
        method: 'post',
        data: message
    })
}

export function createMessageWithFile (image) {
    return request({
        url: `/api/chat/create-message-with-file`,
        method: 'post',
        data: image
    })
}
