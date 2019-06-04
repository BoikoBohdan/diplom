import request from "./request"

export function getStatusesList () {
    return request({
        url: `/api/admin/orders/statuses`,
        method: 'get'
    });
}

export function setNewStatus (id, status) {
    return request({
        url: `/api/admin/orders/${id}`,
        method: 'patch',
        data: {status}
    });
}

export function setStatus (id, status) {
    return request({
        url: `/api/admin/orders/${id}/set-status`,
        method: 'patch',
        data: {status}
    });
}

export function cancelStatusConfirm (id, reasons) {
    return request({
        url: `/api/admin/orders/${id}/cancel`,
        method: 'patch',
        data: {...reasons}
    });
}
