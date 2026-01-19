import { http } from '@/shared/api/axios.js'

export async function fetchLatestTransactions() {
  const { data } = await http.get('/api/transactions/latest')
  return Array.isArray(data) ? data : (data.data ?? [])
}

export async function fetchAllTransactions(search = '') {
  const params = {}
  if (search.trim() !== '') {
    params.search = search.trim()
  }
  const { data } = await http.get('/api/transactions', { params })
  return Array.isArray(data) ? data : (data.data ?? [])
}
