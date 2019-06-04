import request from "./request"

export function getCanceledReasons () {
    return request({
        url: `api/admin/cancel-reasons`,
        method: 'get'
    })
}
