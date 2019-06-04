import request from './request'

export function getDriversList (data) {
  return request({
    url: `/api/admin/gods-eye/drivers`,
    method: 'get',
    params: data
  })
}

export function saveDriversList (data) {
  return request({
    url: `/api/admin/drivers`,
    method: 'post',
    data: data
  })
}

export function setWayPoints (user, data) {
    return request({
        url: `/api/admin/gods-eye/driver/${user}/set-waypoints`,
        method: 'post',
        data: {waypoints: data}
    })
}
