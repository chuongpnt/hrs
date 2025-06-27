import { createRouter, createWebHistory } from 'vue-router'
import StaffList from '../components/StaffList.vue'

const routes = [
  {
    path: '/staff',
    name: 'staff.index',
    component: StaffList,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router