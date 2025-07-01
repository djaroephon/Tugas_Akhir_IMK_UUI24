<template>
  <div class="h-screen bg-white shadow-lg w-64 flex flex-col fixed z-1">
    <div class="p-6 text-2xl font-bold text-green-600 border-b border-gray-200">Admin Panti</div>

    <nav class="flex-1 p-4 space-y-2">
      <RouterLink
        to="/admin/dashboard"
        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition"
      >
        Dashboard
      </RouterLink>

      <RouterLink
        to="/admin/kamar"
        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition"
      >
        Kamar
      </RouterLink>
      <RouterLink
        to="/admin/anak"
        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition"
      >
        Anak
      </RouterLink>
      <RouterLink
        to="/admin/donasi"
        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition"
      >
        Donasi
      </RouterLink>

      <div class="p-4 border-t border-gray-200">
        <button
          @click="logout"
          class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100 rounded-lg transition"
        >
          Logout
        </button>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const logout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>

<style scoped></style>
