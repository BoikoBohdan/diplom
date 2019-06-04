import {
  AUTH_LOGIN,
  AUTH_LOGOUT,
  AUTH_ERROR,
  AUTH_CHECK,
  AUTH_GET_PERMISSIONS
} from 'react-admin'

export default (type, params) => {
  if (type === AUTH_LOGIN) {
    const { username, password } = params
    const request = new Request('api/login', {
      method: 'POST',
      body: JSON.stringify({ email: username, password }),
      headers: new Headers({ 'Content-Type': 'application/json' })
    })
    return fetch(request)
      .then(async response => {
        if (response.status < 200 || response.status >= 300) {
          await response.text().then(show_err => {
            let error = JSON.parse(show_err)
            if (error.errors) {
              let errors = error.errors
              let firt_error = errors[Object.keys(errors)[0]]
              return Promise.reject(`${firt_error}`)
            } else {
              return Promise.reject(`${error.message}`)
            }
          })
        }
        return response.json(response.token)
      })
      .then(({ token, role, id }) => {
        localStorage.setItem('token', token)
        // localStorage.setItem('role', role)
        localStorage.setItem('id', id)
        return Promise.resolve({ redirectTo: '/login' })
      })
  }
  if (type === AUTH_ERROR) {
    return Promise.resolve('Unknown error')
  }
  if (type === AUTH_GET_PERMISSIONS) {
    var urlParams = new URLSearchParams(window.location.search)
    let params = urlParams.getAll('token')
    const role = localStorage.getItem('role')
    if (params.length > 0 && !role) {
      localStorage.setItem('token', params[0])
      localStorage.setItem('role', 'new_user')
    }
    return role ? Promise.resolve(role) : Promise.reject()
  }
  if (type === AUTH_CHECK) {
    return localStorage.getItem('token') ? Promise.resolve() : Promise.reject()
  }
  if (type === AUTH_LOGOUT) {
    let token = localStorage.getItem('token')
    if (token) {
      const request = new Request('api/logout', {
        method: 'GET',
        headers: new Headers({
          Authorization: `Bearer ${localStorage.getItem('token')}`
        })
      })
      localStorage.removeItem('token')
      // localStorage.removeItem('role')
      return fetch(request)
        .then(response => {
          if (response.status < 200 || response.status >= 300) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .then(() => {
          return Promise.resolve()
        })
        .catch(error => {
          return Promise.resolve()
        })
    } else {
      return Promise.resolve()
    }
  }
  return Promise.reject('Unkown method')
}
