<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { http } from '@/shared/api/axios.js'

const user = ref(null)
const transactions = ref([])
const loadingTx = ref(false)
const txError = ref(null)

let intervalId = null

const fetchUser = async () => {
  const { data } = await http.get('/api/user')
  user.value = data
}

const fetchTransactions = async () => {
  loadingTx.value = true
  txError.value = null
  try {
    const { data } = await http.get('/api/transactions')
    transactions.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch (e) {
    txError.value = e
    throw e
  } finally {
    loadingTx.value = false
  }
}

const typeBadgeClass = (type) => {
  if (type === 'debet') return 'bg-success'
  if (type === 'credit') return 'bg-danger'
  return 'bg-secondary'
}

const formatDate = (iso) => {
  if (!iso) return ''
  return new Date(iso).toLocaleString()
}

const totalAmount = computed(() =>
  transactions.value.reduce((sum, t) => sum + Number(t.amount ?? 0), 0)
)

onMounted(async () => {
  try {
    await fetchUser()
    await fetchTransactions()

    intervalId = setInterval(() => {
      fetchTransactions().catch(() => {
        // если нужно — можно остановить интервал/редиректнуть на /auth при 401
      })
    }, 10_000)
  } catch (e) {
    // window.location.href = '/auth'
    // console.log(e)
  }
})

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId)
})
</script>

<template>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="mb-0">Транзакции пользователя {{ user?.name }}</h2>
      <button class="btn btn-outline-primary btn-sm" @click="fetchTransactions">
        Архив транзакций
      </button>
    </div>

    <div v-if="txError" class="alert alert-danger">
      Не удалось загрузить транзакции.
    </div>

    <div class="card">
      <div class="card-body">
        <div class="text-muted mb-2">
          Текущий баланс: {{ user?.balance }}
        </div>

        <div v-if="loadingTx" class="py-3">
          <div class="spinner-border spinner-border-sm me-2" role="status" />
          Загрузка...
        </div>

        <div v-else class="table-responsive">
          <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 80px;">ID</th>
                <th style="width: 140px;">Сумма</th>
                <th style="width: 140px;">Тип</th>
                <th>Описание</th>
                <th style="width: 220px;">Дата</th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="transactions.length === 0">
                <td colspan="5" class="text-center text-muted py-4">
                  Транзакций пока нет
                </td>
              </tr>

              <tr v-for="t in transactions" :key="t.id">
                <td>{{ t.id }}</td>
                <td>{{ t.amount }}</td>
                <td>
                  <span class="badge" :class="typeBadgeClass(t.type)">
                    {{ t.type }}
                  </span>
                </td>
                <td>{{ t.description }}</td>
                <td>{{ formatDate(t.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
