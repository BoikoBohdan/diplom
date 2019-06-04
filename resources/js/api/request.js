import axios from 'axios'
const API_URL = process.env.MIX_PUBLIC_URL
const TYPE = process.env.MIX_PUBLIC_TYPE
import * as R from 'ramda'

const service = axios.create({
  baseURL: API_URL, // api base_url
  timeout: 10000 // Request timeout
})
// request
service.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}` // Let each request carry a custom token, please modify it according to the actual situation.
    }
    return config
  },
  error => {
    Promise.reject(error)
  }
)

service.interceptors.response.use(
  response => response,
  error => {
    const { status, statusText } = error.response
      if(TYPE !== 'dev'){
          switch (status) {
              case 500:
                  window.location.replace('#/login')
                  break;
              case 403:
                  console.log('Forbidden')
                  break;
              case 401:
                  window.location.replace('#/login')
                  break;
              default:
                  console.log(status)
          }

      }
    // if (status < 403 || R.equals(status, 500)) {
    //   window.location.replace('#/login')
    // }
    return Promise.reject(error)
  }
)

export default service


