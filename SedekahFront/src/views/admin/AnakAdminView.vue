<script setup>
import Sidebar from '@/components/admin/SideBar.vue'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const BASE_URL = 'http://127.0.0.1:8000'
const FOTO_PATH = '/storage/'
const kamarData = ref([])
const selectedKamar = ref('all')
const showModal = ref(false)
const isEditMode = ref(false) // Tambahkan mode edit
const currentAnakId = ref(null) // Untuk menyimpan ID anak yang sedang diedit
const fotoPreview = ref(null) // Untuk preview foto saat edit

const form = ref({
  kamar_id: '',
  nama: '',
  usia: '',
  latar_belakang: '',
  foto: null,
})

const fetchKamar = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/kamar')
    kamarData.value = response.data.data
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const filteredAnak = computed(() => {
  if (selectedKamar.value === 'all') {
    return kamarData.value.flatMap((kamar) =>
      kamar.anak.map((anak) => ({ ...anak, nama_kamar: kamar.nama })),
    )
  }

  const kamar = kamarData.value.find((k) => k.id == selectedKamar.value)
  return kamar.anak.map((anak) => ({
    ...anak,
    nama_kamar: kamar.nama,
  }))
})

const handleFileUpload = (e) => {
  const file = e.target.files[0]
  form.value.foto = file

  // Buat preview jika di mode edit
  if (file && isEditMode.value) {
    const reader = new FileReader()
    reader.onload = (e) => {
      fotoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// Buka modal untuk tambah data
const openAddModal = () => {
  isEditMode.value = false
  resetForm()
  showModal.value = true
}

// Buka modal untuk edit data
const openEditModal = (anak) => {
  isEditMode.value = true
  currentAnakId.value = anak.id

  // Isi form dengan data anak
  form.value = {
    kamar_id: anak.kamar_id,
    nama: anak.nama,
    usia: anak.usia,
    latar_belakang: anak.latar_belakang,
    foto: null, // Kita reset foto karena akan diisi ulang jika diubah
  }

  // Set preview foto jika ada
  fotoPreview.value = anak.foto ? `${BASE_URL}${FOTO_PATH}${anak.foto}` : null
  showModal.value = true
}

const submitForm = async () => {
  try {
    const formData = new FormData()
    formData.append('kamar_id', form.value.kamar_id)
    formData.append('nama', form.value.nama)
    formData.append('usia', form.value.usia)
    formData.append('latar_belakang', form.value.latar_belakang)

    if (form.value.foto) {
      formData.append('foto', form.value.foto)
    }

    // Tentukan endpoint berdasarkan mode
    let endpoint = 'http://127.0.0.1:8000/api/anak'
    let method = 'post'

    if (isEditMode.value) {
      endpoint = `http://127.0.0.1:8000/api/anak/${currentAnakId.value}`
      method = 'put'
      formData.append('_method', 'PUT') // Untuk override method di Laravel
    }

    await axios.post(endpoint, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    showModal.value = false
    resetForm()
    fetchKamar() // Refresh data
  } catch (error) {
    console.error('Error:', error)
    alert(`Terjadi kesalahan: ${error.response?.data?.message || error.message}`)
  }
}

const deleteAnak = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus data anak ini?')) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/anak/${id}`)
    fetchKamar() // Refresh data
  } catch (error) {
    console.error('Error deleting anak:', error)
    alert(`Gagal menghapus: ${error.response?.data?.message || error.message}`)
  }
}

const resetForm = () => {
  form.value = {
    kamar_id: '',
    nama: '',
    usia: '',
    latar_belakang: '',
    foto: null,
  }
  currentAnakId.value = null
  fotoPreview.value = null
  isEditMode.value = false
}

onMounted(() => {
  fetchKamar()
})
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <Sidebar />
    <div class="ml-64 flex-1 p-6">
      <h1 class="text-2xl font-bold mb-6">Admin Anak</h1>

      <!-- Filter & Add Button -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
          <label for="kamar-filter" class="font-medium">Filter Kamar:</label>
          <select id="kamar-filter" v-model="selectedKamar" class="border rounded px-3 py-2 w-48">
            <option value="all">Semua Kamar</option>
            <option v-for="kamar in kamarData" :key="kamar.id" :value="kamar.id">
              {{ kamar.nama }} ({{ kamar.anak.length }})
            </option>
          </select>
        </div>

        <button
          @click="openAddModal"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 mr-1"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
              clip-rule="evenodd"
            />
          </svg>
          Tambah Anak
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Nama
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Usia
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Latar Belakang
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Kamar
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="filteredAnak.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data anak</td>
            </tr>
            <tr v-for="anak in filteredAnak" :key="anak.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="relative" style="padding-bottom: 100%">
                      <img
                        v-if="anak.foto"
                        :src="`${BASE_URL}${FOTO_PATH}${anak.foto}`"
                        :alt="anak.nama"
                        class="absolute h-full w-full rounded-full object-cover"
                      />
                      <div
                        v-else
                        class="absolute inset-0 bg-gray-200 border-2 border-dashed rounded-xl"
                      ></div>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ anak.nama }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ anak.usia }} tahun</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900 truncate max-w-xs">
                  {{ anak.latar_belakang || '-' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ anak.nama_kamar }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <button
                    @click="openEditModal(anak)"
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteAnak(anak.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal untuk Tambah/Edit -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50"
      >
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
          <div class="border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold">
              {{ isEditMode ? 'Edit Data Anak' : 'Tambah Anak Baru' }}
            </h3>
            <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
          <form @submit.prevent="submitForm" class="p-6 space-y-4">
            <!-- Preview foto saat edit -->
            <div v-if="isEditMode && fotoPreview" class="flex justify-center">
              <img
                :src="fotoPreview"
                alt="Preview Foto"
                class="h-32 w-32 rounded-full object-cover border-2 border-gray-300"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kamar</label>
              <select
                v-model="form.kamar_id"
                required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="" disabled>Pilih Kamar</option>
                <option v-for="kamar in kamarData" :key="kamar.id" :value="kamar.id">
                  {{ kamar.nama }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
              <input
                v-model="form.nama"
                type="text"
                required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Usia</label>
              <input
                v-model="form.usia"
                type="number"
                min="0"
                required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Latar Belakang</label>
              <textarea
                v-model="form.latar_belakang"
                rows="3"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ isEditMode ? 'Ganti Foto' : 'Foto' }} (Opsional)
              </label>
              <input
                type="file"
                accept="image/*"
                @change="handleFileUpload"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
              />
            </div>

            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="showModal = false"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Batal
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                {{ isEditMode ? 'Update' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
