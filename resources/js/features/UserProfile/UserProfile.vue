<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { http } from '@/shared/api/axios.js'

const user = ref(null)
let transactionsIntervalId = null

const fetchUser = async () => {
  const { data } = await http.get('/api/user')
  user.value = data
}

const fetchTransactions = async () => {
  const { data } = await http.get('/api/transactions')
  // например: сохранить в ref, если нужно
  // transactions.value = data
}

onMounted(async () => {
  try {
    await fetchUser()
    await fetchTransactions() // первый раз сразу

    transactionsIntervalId = setInterval(async () => {
      try {
        await fetchTransactions()
      } catch (e) {
        console.log(e)
      }
    }, 10_000)
  } catch (e) {
    window.location.href = '/auth'
    
  }
})

onUnmounted(() => {
  if (transactionsIntervalId) clearInterval(transactionsIntervalId)
})
</script>

<template>
  <div>asdasd</div>
</template>
