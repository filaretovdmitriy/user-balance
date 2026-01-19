export function typeBadgeClass(type) {
  if (type === 'debit') return 'bg-success'
  if (type === 'credit') return 'bg-danger'
  return 'bg-secondary'
}

export function formatDate(iso) {
  if (!iso) return ''
  return new Date(iso).toLocaleString()
}
