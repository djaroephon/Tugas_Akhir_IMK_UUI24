<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const form = ref({
  kamar_id: '',
  nama_donatur: '',
  asal: '',
  jenis: 'dana',
  keterangan: '',
  bukti: null,
})

const errors = ref({})
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const kamarList = ref([])
const rekeningList = [
  { bank: 'BSI', no: '1234567890', atas_nama: 'Panti Aceh' },
  { bank: 'Bank Aceh', no: '0987654321', atas_nama: 'Panti Aceh' },
]

const fetchKamar = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/kamar')
    kamarList.value = res.data.data
  } catch (err) {
    console.error('Gagal mengambil daftar kamar:', err)
  }
}

const handleFileChange = (e) => {
  form.value.bukti = e.target.files[0]
}

const resetMessages = () => {
  errors.value = {}
  successMessage.value = ''
  errorMessage.value = ''
}

const submitForm = async () => {
  resetMessages()
  loading.value = true

  const formData = new FormData()
  formData.append('kamar_id', form.value.kamar_id)
  formData.append('nama_donatur', form.value.nama_donatur)
  formData.append('asal', form.value.asal)
  formData.append('jenis', form.value.jenis)
  if (form.value.keterangan) formData.append('keterangan', form.value.keterangan)
  if (form.value.bukti) formData.append('bukti', form.value.bukti)
  if (form.value.jenis === 'barang' && form.value.nama_barang) {
    formData.append('nama_barang', form.value.nama_barang)
  }
  if (form.value.jenis === 'dana' && form.value.nominal) {
    formData.append('nominal', form.value.nominal)
  }

  try {
    const res = await axios.post('http://127.0.0.1:8000/api/donasi', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    successMessage.value = res.data.message || 'Donasi berhasil dikirim!'
    // Reset form
    form.value = {
      kamar_id: '',
      nama_donatur: '',
      asal: '',
      jenis: 'dana',
      keterangan: '',
      bukti: null,
      nama_barang: '',
      nominal: '',
    }
  } catch (err) {
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors || {}
    } else {
      errorMessage.value = 'Terjadi kesalahan, silakan coba lagi.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchKamar()
})
</script>

<template>
  <div class="max-w-3xl mx-auto p-6 sm:p-10 bg-white rounded-lg shadow-md my-10">
    <h1 class="text-4xl font-extrabold text-green-700 mb-6 text-center">Formulir Donasi</h1>
    <p class="text-center text-gray-700 mb-10">
      Terima kasih telah berdonasi. Donasi Anda sangat berarti untuk anak-anak di kamar asuhan.
    </p>

    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Pilih Kamar -->
      <div>
        <label for="kamar_id" class="block mb-1 font-semibold text-gray-700"
          >Pilih Kamar Tujuan <span class="text-red-500">*</span></label
        >
        <select
          id="kamar_id"
          v-model="form.kamar_id"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option disabled value="">-- Pilih Kamar --</option>
          <option v-for="k in kamarList" :key="k.id" :value="k.id">{{ k.nama }}</option>
        </select>
        <p v-if="errors.kamar_id" class="text-red-600 mt-1 text-sm">{{ errors.kamar_id[0] }}</p>
      </div>

      <div>
        <label for="nama_donatur" class="block mb-1 font-semibold text-gray-700"
          >Nama Donatur <span class="text-red-500">*</span></label
        >
        <input
          id="nama_donatur"
          type="text"
          v-model="form.nama_donatur"
          placeholder="Nama Anda"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
          autocomplete="off"
        />
        <p v-if="errors.nama_donatur" class="text-red-600 mt-1 text-sm">
          {{ errors.nama_donatur[0] }}
        </p>
      </div>

      <!-- Asal Donatur -->
      <div>
        <label for="asal" class="block mb-1 font-semibold text-gray-700"
          >Asal Donatur <span class="text-red-500">*</span></label
        >
        <input
          id="asal"
          type="text"
          v-model="form.asal"
          placeholder="Kota atau asal Anda"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
          autocomplete="off"
        />
        <p v-if="errors.asal" class="text-red-600 mt-1 text-sm">{{ errors.asal[0] }}</p>
      </div>

      <!-- Jenis Donasi -->
      <div>
        <label for="jenis" class="block mb-1 font-semibold text-gray-700"
          >Jenis Donasi <span class="text-red-500">*</span></label
        >
        <select
          id="jenis"
          v-model="form.jenis"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="dana">Dana</option>
          <option value="barang">Barang</option>
        </select>
        <p v-if="errors.jenis" class="text-red-600 mt-1 text-sm">{{ errors.jenis[0] }}</p>
      </div>

      <!-- Info Rekening jika jenis donasi dana -->
      <transition name="fade" mode="out-in">
        <div
          v-if="form.jenis === 'dana'"
          class="bg-green-50 border border-green-300 p-4 rounded text-green-800 text-sm"
        >
          <p class="font-semibold mb-2">Silakan transfer ke salah satu rekening berikut:</p>
          <ul class="space-y-1">
            <li v-for="rek in rekeningList" :key="rek.no">
              <span class="font-semibold">{{ rek.bank }}:</span> {{ rek.no }} a.n.
              {{ rek.atas_nama }}
            </li>
          </ul>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <div v-if="form.jenis === 'barang'">
          <label for="nama_barang" class="block mb-1 font-semibold text-gray-700">
            Nama Barang <span class="text-red-500">*</span>
          </label>
          <input
            id="nama_barang"
            type="text"
            v-model="form.nama_barang"
            placeholder="Contoh: Beras 5kg, Selimut, dll"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
          />
          <p v-if="errors.nama_barang" class="text-red-600 mt-1 text-sm">
            {{ errors.nama_barang[0] }}
          </p>
        </div>
      </transition>
      <transition name="fade" mode="out-in">
        <div v-if="form.jenis === 'dana'">
          <label for="nominal" class="block mb-1 font-semibold text-gray-700">
            Nominal <span class="text-red-500">*</span>
          </label>
          <input
            id="nominal"
            type="number"
            v-model="form.nominal"
            placeholder="Contoh: 1000000"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
          />
          <p v-if="errors.nominal" class="text-red-600 mt-1 text-sm">
            {{ errors.nominal[0] }}
          </p>
        </div>
      </transition>

      <!-- Keterangan -->
      <div>
        <label for="keterangan" class="block mb-1 font-semibold text-gray-700"
          >Keterangan (Opsional)</label
        >
        <textarea
          id="keterangan"
          v-model="form.keterangan"
          rows="3"
          placeholder="Tuliskan keterangan tambahan..."
          class="w-full border border-gray-300 rounded px-3 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-green-500"
        ></textarea>
        <p v-if="errors.keterangan" class="text-red-600 mt-1 text-sm">{{ errors.keterangan[0] }}</p>
      </div>

      <!-- Upload Bukti -->
      <div>
        <label class="block mb-1 font-semibold text-gray-700">Upload Bukti (Opsional)</label>
        <input
          type="file"
          accept="image/*"
          @change="handleFileChange"
          class="w-full file:border file:border-gray-300 file:rounded file:px-3 file:py-2 file:bg-white file:text-gray-700 hover:file:bg-green-100"
        />
        <p v-if="errors.bukti" class="text-red-600 mt-1 text-sm">{{ errors.bukti[0] }}</p>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button
          type="submit"
          :disabled="loading"
          class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded shadow transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span
            v-if="loading"
            class="inline-block animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5 mr-2"
          ></span>
          Kirim Donasi
        </button>
      </div>

      <!-- Feedback Message -->
      <div
        v-if="successMessage"
        class="mt-4 p-4 bg-green-100 text-green-800 rounded text-center font-semibold"
      >
        {{ successMessage }}
      </div>
      <div
        v-if="errorMessage"
        class="mt-4 p-4 bg-red-100 text-red-800 rounded text-center font-semibold"
      >
        {{ errorMessage }}
      </div>
    </form>
  </div>
</template>

<style scoped>
/* Optional fade transition for rekening info */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
