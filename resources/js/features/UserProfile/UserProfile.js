import { createApp } from 'vue'
import UserProfile from './UserProfile.vue'

document.querySelectorAll('[data-vue="user-profile"]').forEach((el) => {
  createApp(UserProfile).mount(el)
})