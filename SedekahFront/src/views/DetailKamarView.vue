<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const BASE_URL = 'http://127.0.0.1:8000'
const FOTO_PATH = '/storage/'
const route = useRoute()
const kamar = ref(null)
const loading = ref(true)

const selectedAnak = ref(null)
const showModal = ref(false)

const openModal = (anak) => {
  selectedAnak.value = anak
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedAnak.value = null
}

onMounted(async () => {
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/kamar/${route.params.id}`)
    kamar.value = res.data.data
  } catch (err) {
    console.error('Gagal mengambil detail kamar:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="m-5 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div v-if="loading" class="text-center text-gray-600 text-lg">Memuat data...</div>

    <div v-else-if="kamar" class="bg-white shadow-lg rounded-2xl p-6">
      <h2 class="text-3xl font-bold text-green-700 mb-4">{{ kamar.nama }}</h2>
      <p class="text-gray-600 mb-6">Kapasitas: {{ kamar.kapasitas }}</p>

      <h3 class="text-xl font-semibold text-gray-800 mb-4">Penghuni:</h3>

      <div
        v-if="kamar.anak.length > 0"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
      >
        <div
          v-for="anak in kamar.anak"
          :key="anak.id"
          class="bg-white border rounded-xl shadow-md p-4 flex flex-col items-center text-center hover:shadow-lg transition"
        >
          <img
            v-if="anak.foto"
            :src="`${BASE_URL}${FOTO_PATH}${anak.foto}`"
            :alt="anak.nama"
            class="w-24 h-24 rounded-full object-cover mb-3"
          />
          <div
            v-else
            class="w-24 h-24 rounded-full bg-gray-200 mb-3 flex items-center justify-center"
          ></div>
          <span class="text-gray-500">Tidak ada foto</span>
          <p class="font-semibold text-lg">{{ anak.nama }}</p>
          <p class="text-sm text-gray-600 mb-3">Usia: {{ anak.usia }} tahun</p>
          <button
            @click="openModal(anak)"
            class="mt-auto bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md transition"
          >
            Detail
          </button>
        </div>
      </div>
      <p v-else class="text-gray-500">Belum ada anak di kamar ini.</p>
    </div>

    <div v-else class="text-center text-red-500">Data kamar tidak ditemukan.</div>

    <!-- Modal -->
    <transition name="fade">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm"
      >
        <div
          class="relative w-full max-w-md p-6 bg-white rounded-2xl shadow-xl transform transition-all duration-300 scale-100"
        >
          <button
            @click="closeModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold"
          >
            &times;
          </button>

          <div v-if="selectedAnak" class="text-center">
            <img
              :src="
                `${BASE_URL}${FOTO_PATH}${selectedAnak.foto}` ||
                'https://via.placeholder.com/150?text=No+Image'
              "
              alt="Foto Anak"
              class="w-28 h-28 mx-auto rounded-full object-cover shadow mb-4"
            />
            <h4 class="text-2xl font-semibold text-gray-800">{{ selectedAnak.nama }}</h4>
            <p class="text-sm text-gray-600 mb-4">Usia: {{ selectedAnak.usia }} tahun</p>
            <div class="text-left bg-gray-50 p-4 rounded-lg shadow-inner">
              <h5 class="font-semibold text-gray-700 mb-2">Latar Belakang</h5>
              <p class="text-sm text-gray-600">
                {{ selectedAnak.latar_belakang || 'Tidak tersedia' }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
