import { createApp } from 'vue'
import UserTransactions from './UserTransactions.vue'

document.querySelectorAll('[data-vue="user-transactions"]').forEach((el) => {
  createApp(UserTransactions).mount(el)
})