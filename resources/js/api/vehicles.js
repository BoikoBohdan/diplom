import request from './request'

export function getVehicles () {
  return request({
    url: `/api/admin/vehicle-types`,
    method: 'GET'
  })
}

export function saveVehicles (data) {
  return request({
    url: `/api/admin/vehicles`,
    method: 'POST',
    data: data
  })
}

export function updateVehicles (id, data) {
  return request({
    url: `/api/admin/vehicles/${id}`,
    method: 'PUT',
    data: data
  })
}

export function deleteVehicles (id) {
  return request({
    url: `/api/admin/vehicles/${id}`,
    method: 'DELETE'
  })
}

export function saveVehiclesDocuments (vehicle_id, data) {
  return request({
    url: `/api/admin/vehicles/${vehicle_id}/documents`,
    method: 'POST',
    data: data
  })
}

export function updateVehiclesDocuments (vehicle_id, document_id, data) {
  return request({
    url: `/api/admin/vehicles/${vehicle_id}/documents/${document_id}`,
    method: 'PUT',
    data: data
  })
}
