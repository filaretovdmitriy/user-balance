import { createApp } from 'vue'
import HelloIsland from './AuthForm.vue'

document.querySelectorAll('[data-vue="auth-form"]').forEach((el) => {
  createApp(HelloIsland).mount(el)
})