import { createApp } from 'vue'
import AuthForm from './AuthForm.vue'

document.querySelectorAll('[data-vue="auth-form"]').forEach((el) => {
  createApp(AuthForm).mount(el)
})