<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { fetchUser } from '@/shared/api/endpoints/user.js'
import { fetchAllTransactions } from '@/shared/api/endpoints/transactions.js'
import { typeBadgeClass, formatDate } from '@/shared/utils.js'

const user = ref(null)
const transactions = ref([])

const loadingTx = ref(false)
const txError = ref(null)

const search = ref('')
const searchDebounceMs = 400
let debounceId = null

// Клиентская сортировка по дате (created_at)
const sortDir = ref('desc') // 'asc' | 'desc'
const toggleSortByDate = () => {
  sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
}

const sortedTransactions = computed(() => {
  const dir = sortDir.value === 'asc' ? 1 : -1

  // Важно: сортируем копию, чтобы не мутировать исходный reactive-массив
  return [...transactions.value].sort((a, b) => {
    const at = a?.created_at ? new Date(a.created_at).getTime() : 0
    const bt = b?.created_at ? new Date(b.created_at).getTime() : 0
    return (at - bt) * dir
  })
})

const fetchTransactions = async () => {
  loadingTx.value = true
  txError.value = null

  try {
    transactions.value = await fetchAllTransactions(search.value)
  } catch (e) {
    txError.value = e
    throw e
  } finally {
    loadingTx.value = false
  }
}

const onSearchInput = () => {
  clearTimeout(debounceId)
  debounceId = setTimeout(() => {
    fetchTransactions().catch(() => {})
  }, searchDebounceMs)
}

onMounted(async () => {
  try {
    user.value = await fetchUser()
    await fetchTransactions()
  } catch (e) {
   // window.location.href = '/auth'
  }
})

onUnmounted(() => {
  clearTimeout(debounceId)
})
</script>

<template>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="mb-0">Все транзакции</h2>
    </div>

    <!-- Поиск -->
    <div class="input-group mb-3">
      <span class="input-group-text">Поиск</span>
      <input
        class="form-control"
        type="text"
        placeholder="Поиск транзакции..."
        v-model="search"
        @input="onSearchInput"
      />
    </div>

    <div v-if="txError" class="alert alert-danger">
      Не удалось загрузить транзакции.
    </div>

    <div class="card">
      <div class="card-body">
        <div class="text-muted mb-2">
          Найдено: {{ sortedTransactions.length }}
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

                <!-- Сортировка по дате только на клиенте -->
                <th
                  style="width: 220px; cursor: pointer; user-select: none;"
                  @click="toggleSortByDate"
                  title="Сортировать по дате"
                >
                  Дата
                  <span class="text-muted ms-1">
                    {{ sortDir === 'asc' ? '▲' : '▼' }}
                  </span>
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="sortedTransactions.length === 0">
                <td colspan="5" class="text-center text-muted py-4">
                  Ничего не найдено
                </td>
              </tr>

              <tr v-for="t in sortedTransactions" :key="t.id">
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
