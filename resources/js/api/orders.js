import request from './request';

export function getOrdersList (data) {
    return request({
        url: `/api/admin/gods-eye/orders`,
        method: 'get',
        params: data
    });
}

export function assignOrder (data) {
    return request({
        url: `/api/admin/gods-eye/assign-drivers`,
        method: 'post',
        data
    });
}

export function getOrdersByDriver (id) {
    return request({
        url: `/api/admin/gods-eye/driver/${id}/orders`,
        method: 'get',
    });
}

export function createOrders (data) {
    return request({
        url: `/api/admin/orders`,
        method: 'post',
        data
    });
}

