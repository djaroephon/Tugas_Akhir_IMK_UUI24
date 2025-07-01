import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import kamar from '../views/KamarView.vue'
import DetailKamarView from '@/views/DetailKamarView.vue'
import donasi from '@/views/DonasiView.vue'
import login from '@/views/LoginView.vue'

//admin
import AdminDashboard from '@/views/admin/DashboardView.vue'
import kamarAdmin from '@/views/admin/KamarAdminView.vue'
import AnakAdmin from '@/views/admin/AnakAdminView.vue'
import donasiAdmin from '@/views/admin/DonasiAdminView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component: login,
    },
    {
      path: '/kamar',
      name: 'kamar',
      component: kamar,
    },
    {
      path: '/kamar/:id',
      name: 'detailKamar',
      component: DetailKamarView,
    },
    {
      path: '/donasi',
      name: 'donasi',
      component: donasi,
    },

    {
      path: '/admin',
      children: [
        {
          path: 'dashboard',
          name: 'adminDashboard',
          component: AdminDashboard,
          meta: { requiresAuth: true },
        },
        {
          path: 'kamar',
          name: 'adminKamar',
          component: kamarAdmin,
          meta: { requiresAuth: true },
        },
        {
          path: 'anak',
          name: 'adminAnak',
          component: AnakAdmin,
          meta: { requiresAuth: true },
        },
        {
          path: 'donasi',
          name: 'adminDonasi',
          component: donasiAdmin,
          meta: { requiresAuth: true },
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem('token')
  if (to.meta.requiresAuth && !isLoggedIn) {
    next({ name: 'login' })
  } else {
    next()
  }
})

export default router
