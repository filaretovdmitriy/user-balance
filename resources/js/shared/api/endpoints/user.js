import { http } from '@/shared/api/axios.js'

export async function fetchUser() {
  const { data } = await http.get('/api/user')
  return data
}
