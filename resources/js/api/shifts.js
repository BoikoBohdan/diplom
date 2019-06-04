import request from "./request"

export function createShift (data) {
    return request({
        url: `/api/admin/shifts`,
        method: 'post',
        data
    });
}

export function getShiftById (id) {
    return request({
        url: `/api/admin/shifts/${id}/edit`,
        method: 'get',
    });
}

export function editShiftById (id, data) {
    return request({
        url: `/api/admin/shifts/${id}`,
        method: 'patch',
        data
    });
}

export function getMealsandCities () {
    return request({
        url: `/api/admin/shifts/create`,
        method: 'get',
    });
}
