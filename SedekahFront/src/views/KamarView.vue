<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const kamarList = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/kamar')
    kamarList.value = res.data.data
    console.log(kamarList.value)
  } catch (err) {
    console.error('Gagal ambil data kamar:', err)
  }
})
</script>

<template>
  <div class="m-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h2 class="text-3xl font-bold text-center mb-10 text-green-700">Daftar Ruangan</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="kamar in kamarList"
        :key="kamar.id"
        class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center text-center hover:shadow-xl transition duration-300"
      >
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ kamar.nama }}</h3>
        <p class="text-gray-500 mb-4">Kapasitas: {{ kamar.kapasitas }}</p>

        <router-link
          :to="{ name: 'detailKamar', params: { id: kamar.id } }"
          class="bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition duration-300 mt-auto"
        >
          Lihat Detail
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
