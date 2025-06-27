import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import { VueQueryPlugin, QueryClient } from '@tanstack/vue-query'

// ✅ Khởi tạo queryClient
const queryClient = new QueryClient()

const app = createApp(App)

app.use(createPinia())
app.use(router)

// ✅ Gắn queryClient vào plugin
app.use(VueQueryPlugin, {
  queryClient,
})

app.mount('#app')
