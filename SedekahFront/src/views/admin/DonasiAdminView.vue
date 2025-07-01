<script setup>
import SideBar from '@/components/admin/SideBar.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'

const activeTab = ref('menunggu')
const donations = ref({
  menunggu: [],
  diterima: [],
  ditolak: [],
})
const showImageModal = ref(false)
const currentImageUrl = ref('')
const BASE_URL = 'http://127.0.0.1:8000'

const getImageUrl = (path) => {
  return path ? `${BASE_URL}/storage/${path}` : ''
}

const openImageModal = (imageUrl) => {
  currentImageUrl.value = imageUrl
  showImageModal.value = true
}

const fetchDonations = async () => {
  try {
    const [menunggu, diterima, ditolak] = await Promise.all([
      axios.get(`${BASE_URL}/api/donasi/menunggu`),
      axios.get(`${BASE_URL}/api/donasi/menerima`),
      axios.get(`${BASE_URL}/api/donasi/ditolak`),
    ])

    donations.value = {
      menunggu: menunggu.data || [],
      diterima: diterima.data || [],
      ditolak: ditolak.data || [],
    }

    console.log('Hasil:', diterima.data)
  } catch (error) {
    console.error('Gagal memuat data donasi:', error)
  }
}

const updateStatus = async (id, status) => {
  try {
    await axios.put(`${BASE_URL}/api/donasi/${id}`, { status })
    fetchDonations()
  } catch (error) {
    console.error('Gagal mengubah status donasi:', error)
  }
}



onMounted(() => {
  fetchDonations()
})
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <SideBar />
    <div class="ml-64 flex-1 p-6">
      <h1 class="text-2xl font-bold mb-6">Admin Donasi</h1>

      <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
          <li class="mr-2" v-for="tab in ['menunggu', 'diterima', 'ditolak']" :key="tab">
            <button
              @click="activeTab = tab"
              :class="[
                'inline-block p-4 border-b-2 rounded-t-lg',
                activeTab === tab
                  ? tab === 'menunggu'
                    ? 'text-blue-600 border-blue-600 active'
                    : tab === 'diterima'
                      ? 'text-green-600 border-green-600 active'
                      : 'text-red-600 border-red-600 active'
                  : 'border-transparent hover:text-gray-600 hover:border-gray-300',
              ]"
            >
              {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
              <span
                :class="[
                  'ml-1 text-xs font-medium px-2 py-0.5 rounded-full',
                  tab === 'menunggu'
                    ? 'bg-blue-100 text-blue-800'
                    : tab === 'diterima'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800',
                ]"
              >
                {{ donations[tab].length }}
              </span>
            </button>
          </li>
        </ul>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table v-if="activeTab === 'menunggu'" class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Donatur
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Jenis
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Detail
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Deskripsi
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Tanggal
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(donasi, i) in donations[activeTab]"
              :key="donasi.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 text-sm text-gray-500">{{ i + 1}}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ donasi.nama_donatur }}</div>
                <div class="text-sm text-gray-500">{{ donasi.asal }}</div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                <span
                  :class="[
                    'px-2 py-1 rounded-full text-xs font-medium',
                    donasi.jenis === 'dana'
                      ? 'bg-blue-100 text-blue-800'
                      : 'bg-yellow-100 text-yellow-800',
                  ]"
                >
                  {{ donasi.jenis === 'dana' ? 'Dana' : 'Barang' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                <div v-if="donasi.jenis === 'dana'">
                  Rp {{ donasi.nominal?.toLocaleString('id-ID') || '0' }}
                </div>
                <div v-else>
                  {{ donasi.nama_barang }}
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500 w-100 ">
                <span v-if="donasi.keterangan">{{ donasi.keterangan }}</span>
                <span v-else class="text-gray-400"></span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ new Date(donasi.updated_at).toLocaleDateString('id-ID') }}
              </td>
              <td class="px-6 py-4 text-sm font-medium">
                <template v-if="activeTab === 'menunggu'">
                  <button
                    @click="() => updateStatus(donasi.id, 'diterima')"
                    class="px-3 py-1 mr-1 bg-green-600 text-white rounded-lg hover:bg-green-700"
                  >
                    Terima
                  </button>
                  <button
                    @click="() => updateStatus(donasi.id, 'ditolak')"
                    class="px-3 py-1 mr-1 bg-red-600 text-white rounded-lg hover:bg-red-700"
                  >
                    Tolak
                  </button>
                  <button
                    v-if="donasi.bukti_transfer"
                    @click="() => showPaymentProof(donasi.bukti_transfer)"
                    class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                  >
                    Bukti
                  </button>
                </template>
                <template v-else-if="activeTab === 'ditolak'">
                  <button
                    @click="() => updateStatus(donasi.id, 'menunggu')"
                    class="px-3 py-1 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                  >
                    Kembalikan
                  </button>
                </template>
              </td>
            </tr>
            <tr v-if="donations[activeTab].length === 0">
              <td :colspan="7" class="px-6 py-4 text-center text-gray-500">
                Tidak ada donasi di tab ini
              </td>
            </tr>
          </tbody>
        </table>

        <table v-if="activeTab === 'diterima'" class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Donatur
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Jumlah / Barang
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Kamar
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Gambar
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Tanggal
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(donasi, index) in donations.diterima"
              :key="donasi.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 text-sm text-gray-500">{{ index + 1 }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900 capitalize">
                  {{ donasi.nama_donatur }}
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900" v-if="donasi.jenis === 'dana'">
                Rp {{ donasi.nominal?.toLocaleString('id-ID') || '0' }}
              </td>
              <td v-else class="px-6 py-4 text-sm text-gray-900 capitalize">
                {{ donasi.nama_barang }}
              </td>

              <td class="px-6 py-4">
                <span
                  v-if="donasi.kamar"
                  class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded capitalize"
                >
                  {{ donasi.kamar.nama }}
                </span>
                <span v-else class="text-gray-500 text-sm">-</span>
              </td>
              <td class="px-6 py-4">
                  <div v-if="donasi.bukti"> 
                    <img
                      :src="getImageUrl(donasi.bukti)" 
                      alt="Bukti Transfer"
                      class="w-16 h-16 object-cover cursor-pointer rounded"
                      @click="openImageModal(getImageUrl(donasi.bukti))" 
                  </div>
                  <span v-else class="text-sm text-gray-500">-</span>
                </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ new Date(donasi.updated_at).toLocaleDateString('id-ID') }}
              </td>
            </tr>
            <tr v-if="donations.diterima.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                Tidak ada donasi yang diterima
              </td>
            </tr>
          </tbody>
        </table>

        <table v-if="activeTab === 'ditolak'" class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Donatur
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Jenis
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Detail
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Deskripsi
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Tanggal
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(donasi, i) in donations[activeTab]"
              :key="donasi.id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 text-sm text-gray-500">{{ i + 1 }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ donasi.nama_donatur }}</div>
                <div class="text-sm text-gray-500">{{ donasi.asal }}</div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                <span
                  :class="[
                    'px-2 py-1 rounded-full text-xs font-medium',
                    donasi.jenis === 'dana'
                      ? 'bg-blue-100 text-blue-800'
                      : 'bg-yellow-100 text-yellow-800',
                  ]"
                >
                  {{ donasi.jenis === 'dana' ? 'Dana' : 'Barang' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                <div v-if="donasi.jenis === 'dana'">
                  Rp {{ donasi.nominal?.toLocaleString('id-ID') || '0' }}
                </div>
                <div v-else>
                  {{ donasi.nama_barang }}
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500 w-100">
                <span v-if="donasi.keterangan">{{ donasi.keterangan }}</span>
                <span v-else class="text-gray-400">-</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ new Date(donasi.updated_at).toLocaleDateString('id-ID') }}
              </td>
              <td class="px-6 py-4 text-sm font-medium">
                <template v-if="activeTab === 'menunggu'">
                  <button
                    @click="() => updateStatus(donasi.id, 'diterima')"
                    class="px-3 py-1 mr-1 bg-green-600 text-white rounded-lg hover:bg-green-700"
                  >
                    Terima
                  </button>
                  <button
                    @click="() => updateStatus(donasi.id, 'ditolak')"
                    class="px-3 py-1 mr-1 bg-red-600 text-white rounded-lg hover:bg-red-700"
                  >
                    Tolak
                  </button>
                  <button
                    v-if="donasi.bukti_transfer"
                    @click="() => showPaymentProof(donasi.bukti_transfer)"
                    class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                  >
                    Bukti
                  </button>
                </template>
                <template v-else-if="activeTab === 'ditolak'">
                  <button
                    @click="() => updateStatus(donasi.id, 'menunggu')"
                    class="px-3 py-1 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                  >
                    Kembalikan
                  </button>
                </template>
              </td>
            </tr>
            <tr v-if="donations[activeTab].length === 0">
              <td :colspan="7" class="px-6 py-4 text-center text-gray-500">
                Tidak ada donasi di tab ini
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div
  v-if="showImageModal"
  class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
>
  <div class="bg-white p-4 rounded-lg max-w-3xl max-h-screen overflow-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Bukti Pembayaran / Barang</h2>
      <button
        @click="showImageModal = false"
        class="text-gray-500 hover:text-gray-700"
      >
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
    <img :src="currentImageUrl" class="max-w-full max-h-[80vh] mx-auto" alt="Bukti Pembayaran" />
    <div class="mt-4 text-center">
      <button
        @click="showImageModal = false" 
        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
      >
        Tutup
      </button>
    </div>
  </div>
</div>
</template>

<style scoped>
.active {
  position: relative;
}
.active::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 3px;
}
</style>
