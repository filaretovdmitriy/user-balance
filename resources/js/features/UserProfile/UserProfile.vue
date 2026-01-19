<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { fetchUser } from '@/shared/api/endpoints/user.js'
import { fetchLatestTransactions } from '@/shared/api/endpoints/transactions.js'
import { typeBadgeClass, formatDate } from '@/shared/utils.js'

const user = ref(null)
const transactions = ref([])
const loadingTx = ref(false)
const txError = ref(null)

let intervalId = null

const fetchTransactions = async () => {
  loadingTx.value = true
  txError.value = null
  try {
    transactions.value = await fetchLatestTransactions()
  } catch (e) {
    txError.value = e
    throw e
  } finally {
    loadingTx.value = false
  }
}


const gotoTransactions = () => {
    document.location.href='/transactions'
}

onMounted(async () => {
  try {
    user.value = await fetchUser()
    await fetchTransactions()

    intervalId = setInterval(() => {
      fetchTransactions().catch(() => {

      })
    }, 10_000)
  } catch (e) {
    console.log(e)
    window.location.href = '/auth'
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
      <button class="btn btn-outline-primary btn-sm" @click="gotoTransactions">
        Архив транзакций
      </button>
    </div>

    <div v-if="txError" class="alert alert-danger">
      Не удалось загрузить транзакции.
    </div>

    <div class="card">
      <div class="card-body">
        <div class="text-muted mb-2">
          Текущий баланс: {{ user?.balance.amount }}
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
