import { useUserStore } from '@/stores/userStore'
import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/',
      name: 'login',
      component: LoginView,
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()
  userStore.getUser()

  if(!userStore.user && !userStore.loading){
    await userStore.getUser();
  }

  const isAuthenticated = !!userStore.user

  const publicPages = ['/']
  const authRequired = !publicPages.includes(to.path)

  if (authRequired && !isAuthenticated){
    return next('/')
  }

  if(to.path === '/' && isAuthenticated){
    return next('/home')
  }

  next()
})
export default router
