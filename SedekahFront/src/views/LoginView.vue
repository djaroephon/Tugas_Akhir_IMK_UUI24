<template>
  <div class="min-h-screen bg-gradient-to-b from-white to-slate-100 flex flex-col justify-between">
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-10">
      <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <h1 class="text-xl font-bold text-green-600">Donasi Anak Asuh</h1>
        <nav class="space-x-4 text-slate-700">
          <a href="#" class="hover:text-green-600">Home</a>
          <a href="#" class="hover:text-green-600">Kamar</a>
          <a href="#" class="hover:text-green-600">Donasi</a>
          <a
            href="#"
            class="bg-green-500 text-white px-4 py-1.5 rounded-md hover:bg-green-600 transition"
            >Login</a
          >
        </nav>
      </div>
    </header>

    <!-- FORM LOGIN -->
    <main class="flex-1 flex items-center justify-center pt-24 pb-12 px-4">
      <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl transition duration-300">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-slate-800 mb-6">
          Masuk ke Akun
        </h2>
        <form @submit.prevent="login" class="space-y-5">
          <div>
            <label class="text-sm font-medium text-slate-700 mb-1 block">Email</label>
            <input
              v-model="email"
              type="email"
              required
              class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
              placeholder="you@example.com"
            />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700 mb-1 block">Password</label>
            <input
              v-model="password"
              type="password"
              required
              class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
              placeholder="••••••••"
            />
          </div>
          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition"
          >
            Masuk
          </button>
          <p v-if="error" class="text-sm text-center text-red-600 mt-2">{{ error }}</p>
        </form>
      </div>
    </main>

    <footer class="bg-green-700 text-white py-5 px-4">
      <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-3">
        <div>
          <h3 class="font-semibold text-lg">Panti Asuhan Aceh</h3>
          <p class="text-sm">Lembaga sosial peduli anak yatim piatu dan dhuafa.</p>
        </div>
        <div class="text-sm">Contact@panticeh.or.id</div>
        <div class="text-sm">© 2025 Panti Asuhan Aceh. All rights reserved.</div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  try {
    const res = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value,
    })
    const token = res.data.token
    console.log('login Success')
    localStorage.setItem('token', token)
    window.location.href = '/admin/dashboard'
  } catch (err) {
    error.value = err.response?.data?.message || 'Email atau password salah.'
  }
}
</script>
