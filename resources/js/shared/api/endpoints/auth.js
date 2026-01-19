import { http } from '@/shared/api/axios.js'

export async function login(credentials) {
  await http.get('/sanctum/csrf-cookie')
  await http.post('/api/login', credentials)
}
