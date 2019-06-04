import axios from 'axios'

const API_URL = process.env.REACT_APP_DEV_API_URL

export default axios.create({
  baseURL: API_URL,
  headers: {
    'content-type': `application/x-www-form-urlencoded`
  }
})
