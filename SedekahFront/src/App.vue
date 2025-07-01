<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'
import navbar from './components/Navbar.vue'
import futer from '@/components/Footer.vue'
import { computed } from 'vue'

const route = useRoute()

const isAdminPage = computed(() => route.path.startsWith('/admin'))
const showFooter = computed(() => {
  if (isAdminPage.value) return false
  else if (route.path === '/' || route.path === '/donasi') return false
  return true
})

const showFooterIsHome = computed(() => {
  if (isAdminPage.value) return false
  else if (route.path === '/') return true
  return false
})
</script>

<template>
  <header>
    <div class="wrapper">
      <navbar v-if="!isAdminPage" />
    </div>
  </header>

  <RouterView />

  <futer v-if="showFooterIsHome" class="bottom-0 left-0 right-0" />
  <futer v-if="showFooter" class="fixed bottom-0 left-0 right-0" />
</template>

<style scoped></style>
