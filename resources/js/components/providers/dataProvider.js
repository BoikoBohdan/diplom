import {
  GET_LIST,
  GET_ONE,
  GET_MANY,
  GET_MANY_REFERENCE,
  CREATE,
  UPDATE,
  DELETE,
  DELETE_MANY,
  UPDATE_MANY
} from 'react-admin'
import axios from './request'
import { stringify } from 'query-string'
const API_URL = process.env.MIX_PUBLIC_URL

/**
 * @param {String} type One of the constants appearing at the top if this file, e.g. 'UPDATE'
 * @param {String} resource Name of the resource to fetch, e.g. 'posts'
 * @param {Object} params The Data Provider request params, depending on the type
 * @returns {Object} { url, options } The HTTP request parameters
 */
const convertDataProviderRequestToHTTP = (type, resource, params) => {
  const options = {
    headers: {
      'content-type': `application/x-www-form-urlencoded`,
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  }
  switch (type) {
    case GET_LIST: {
      const { page, perPage } = params.pagination
      const { field, order } = params.sort
      const query = {
        sort: field,
        order: order,
        page: JSON.stringify(page),
        perPage: JSON.stringify(perPage),
        filter: params.filter.q,
        name: params.filter.name
      }
      if (params.order_status) {
        query['order_status'] = JSON.stringify(params.order_status)
      }
      return { url: `${API_URL}/${resource}?${stringify(query)}`, options }
    }
    case GET_ONE: {
      return { url: `${API_URL}/${resource}/${params.id}/edit`, options }
    }
    case 'GET_CUTOM_LINK': {
      return { url: `${resource}`, options }
    }
    case GET_MANY: {
      return { url: `${API_URL}/${resource}`, options }
    }
    case GET_MANY_REFERENCE: {
      const { page, perPage } = params.pagination
      const { field, order } = params.sort
      const query = {
        sort: JSON.stringify([field, order]),
        range: JSON.stringify([(page - 1) * perPage, page * perPage - 1]),
        filter: JSON.stringify({
          ...params.filter,
          [params.target]: params.id
        })
      }
      return { url: `${API_URL}/${resource}?${stringify(query)}`, options }
    }
    case UPDATE:
      const update_one = {
        headers: {
          'content-type': `application/json`,
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      }
      let update = JSON.stringify({ ...params.data })
      return {
        url: `${API_URL}/${resource}/${params.id}`,
        options: { ...update_one, method: 'PUT', data: update }
      }
    case UPDATE_MANY:
      let query_update = JSON.stringify(params)
      const update_options = {
        headers: {
          'content-type': `application/json`,
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      }
      return {
        url: `${API_URL}/${resource}`,
        options: { ...update_options, method: 'PUT', data: query_update }
      }
    case CREATE:
      let create = stringify({
        ...params.data,
        status: params.data.status ? 1 : 0
      })
      return {
        url: `${API_URL}/${resource}`,
        options: { ...options, method: 'POST', data: create }
      }
    case DELETE:
      return {
        url: `${API_URL}/${resource}/${params.id}`,
        options: { ...options, method: 'DELETE', data: stringify(params.data) }
      }
    case 'DELETE_CUTOM_LINK': {
      return { url: `${resource}`, options: { ...options, method: 'DELETE' } }
    }
    case DELETE_MANY:
      let delete_query = JSON.stringify(params)
      const delete_options = {
        headers: {
          'content-type': `application/json`,
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      }
      return {
        url: `${API_URL}/${resource}`,
        options: { ...delete_options, method: 'DELETE', data: delete_query }
      }
    default:
      throw new Error(`Unsupported fetch action type ${type}`)
  }
}

/**
 * @param {Object} response HTTP response from fetch()
 * @param {String} type One of the constants appearing at the top if this file, e.g. 'UPDATE'
 * @param {String} resource Name of the resource to fetch, e.g. 'posts'
 * @param {Object} params The Data Provider request params, depending on the type
 * @returns {Object} Data Provider response
 */
const convertHTTPResponseToDataProvider = (
  response,
  type,
  resource,
  params
) => {
  let { headers, data } = response
  switch (type) {
    case GET_LIST:
      return {
        data,
        total: parseInt(
          (headers['x-total-count'] ? headers['x-total-count'] : 12)
            .split('/')
            .pop()
        )
      }
    case GET_MANY:
      return data
    case GET_ONE:
      return { data: { ...data } }
    case 'GET_CUTOM_LINK':
      return { data: { ...data } }
    case CREATE:
      return { data: { ...params.data, id: data.id } }
    case UPDATE:
      return { data: { ...params.data, id: data.id } }
    case DELETE:
      return { data: { ...params.data, id: data.id } }
    case 'DELETE_CUTOM_LINK':
      return { data: { ...params.data, id: data.id } }
    case DELETE_MANY:
      return { data: { ...params.data, id: data.id } }
    default:
      return { data: { ...params.data, id: data.id } }
  }
}

/**
 * @param {string} type Request type, e.g GET_LIST
 * @param {string} resource Resource name, e.g. "posts"
 * @param {Object} payload Request parameters. Depends on the request type
 * @returns {Promise} the Promise for response
 */
export default (type, resource, params) => {
  const { url, options } = convertDataProviderRequestToHTTP(
    type,
    resource,
    params
  )
  return axios(url, options)
    .then(response =>
      convertHTTPResponseToDataProvider(response, type, resource, params)
    )
    .catch(params => {
      if (params.response.status == 401) {
        window.location.replace('#/login')
      }
      let errors = params.response.data.errors
      let first_error
      if (errors) {
        first_error = errors[Object.keys(errors)[0]]
      } else if (params.response.data.error) {
        let error = params.response.data.error
        let error_show = error[Object.keys(error)[0]]
        first_error = error_show[0]
      } else {
        first_error = 'Error'
      }
      return Promise.reject(first_error)
    })
}
