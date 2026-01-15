import axios from 'axios'

export const http = axios.create({
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

http.defaults.withXSRFToken = true