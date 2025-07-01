<template>
  <nav
    :class="[
      'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
      isScrolled ? 'bg-white shadow-md rounded-lg w-[65vh] mx-auto mt-2' : 'bg-white shadow-md',
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <div :class="[isScrolled ? 'text-green-500' : 'text-green-500']" class="text-xl font-bold">
        {{ isScrolled ? 'Panti Asuhan Aceh' : 'Donasi Anak Asuh' }}
      </div>
      <div class="space-x-6 hidden md:flex items-center">
        <router-link :class="[isScrolled ? 'hover:text-green-500' : 'hover:text-green-500']" to="/"
          >Home</router-link
        >
        <router-link
          :class="[isScrolled ? 'hover:text-green-500' : 'hover:text-green-500']"
          to="/kamar"
          >Kamar</router-link
        >
        <router-link
          :class="[isScrolled ? 'hover:text-green-500' : 'hover:text-green-500']"
          to="/donasi"
          >Donasi</router-link
        >
        <router-link
          :class="[isScrolled ? 'hover:text-green-500' : 'hover:text-green-500']"
          to="/login"
          class="bg-green-500 text-white px-4 py-1 rounded-md hover:bg-green-600 transition duration-300"
        >
          Login
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const isScrolled = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
nav {
  backdrop-filter: blur(10px);
}
</style>
