import request from './request'

export function setWalletAmount (id, data) {
    return request({
      url: `api/admin/wallets/${id}`,
      method: 'patch',
      data
    })
  }