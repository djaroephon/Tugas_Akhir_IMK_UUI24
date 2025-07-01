<script setup>
import Sidebar from '@/components/admin/SideBar.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'

const newKamar = ref({
  nama: '',
  kapasitas: '',
})

const kamarList = ref([])
const showModal = ref(false)
const isEdit = ref(false) // Tambahkan state untuk mode edit
const editingId = ref(null) // ID kamar yang sedang diedit

onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/kamar')
    kamarList.value = res.data.data
  } catch (err) {
    console.error('Gagal memuat data kamar:', err)
  }
})

const openAddModal = () => {
  isEdit.value = false
  newKamar.value = { nama: '', kapasitas: '' }
  showModal.value = true
}

const openEditModal = (kamar) => {
  isEdit.value = true
  editingId.value = kamar.id
  newKamar.value = { nama: kamar.nama, kapasitas: kamar.kapasitas }
  showModal.value = true
}

const saveKamar = async () => {
  try {
    if (isEdit.value) {
      // Mode edit
      const res = await axios.put(
        `http://127.0.0.1:8000/api/kamar/${editingId.value}`,
        newKamar.value,
      )

      // Update data di frontend
      const index = kamarList.value.findIndex((k) => k.id === editingId.value)
      if (index !== -1) {
        kamarList.value[index] = res.data.data
      }
    } else {
      // Mode tambah
      const res = await axios.post('http://127.0.0.1:8000/api/kamar', newKamar.value)
      kamarList.value.push(res.data.data)
    }

    // Reset form dan tutup modal
    newKamar.value = { nama: '', kapasitas: '' }
    showModal.value = false
  } catch (err) {
    console.error(isEdit.value ? 'Gagal mengedit kamar:' : 'Gagal menambahkan kamar:', err)
    alert('Terjadi kesalahan. Silakan coba lagi.')
  }
}

const deleteKamar = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus kamar ini?')) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/kamar/${id}`)
    kamarList.value = kamarList.value.filter((kamar) => kamar.id !== id)
  } catch (err) {
    console.error('Gagal menghapus kamar:', err)
    alert('Gagal menghapus kamar. Silakan coba lagi.')
  }
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <Sidebar />
    <div class="ml-64 flex-1 p-6">
      <h1 class="text-2xl font-bold mb-6">Admin Kamar</h1>
      <div class="bg-white p-6 rounded-2xl shadow">
        <button
          @click="openAddModal"
          class="mb-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
        >
          Tambah Kamar
        </button>

        <div class="overflow-x-auto rounded-lg shadow">
          <table class="min-w-full table-auto">
            <thead class="bg-gray-200 text-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                <th class="px-6 py-3 text-left text-sm font-medium">Nama Kamar</th>
                <th class="px-6 py-3 text-left text-sm font-medium">Kapasitas</th>
                <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(kamar, index) in kamarList"
                :key="kamar.id"
                class="bg-white border-b hover:bg-gray-50"
              >
                <td class="px-6 py-4">{{ index + 1 }}</td>
                <td class="px-6 py-4">{{ kamar.nama }}</td>
                <td class="px-6 py-4">{{ kamar.kapasitas }}</td>
                <td class="px-6 py-4 space-x-2">
                  <button
                    @click="openEditModal(kamar)"
                    class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteKamar(kamar.id)"
                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal untuk Tambah/Edit -->
  <div
    v-if="showModal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">
      <h2 class="text-xl font-bold mb-4">
        {{ isEdit ? 'Edit Kamar' : 'Tambah Kamar' }}
      </h2>
      <form @submit.prevent="saveKamar">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Nama Kamar</label>
          <input
            v-model="newKamar.nama"
            type="text"
            class="w-full px-3 py-2 border rounded-lg"
            placeholder="Contoh: Kamar 1"
            required
          />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Kapasitas</label>
          <input
            v-model="newKamar.kapasitas"
            type="number"
            min="1"
            class="w-full px-3 py-2 border rounded-lg"
            placeholder="Contoh: 4"
            required
          />
        </div>
        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="showModal = false"
            class="px-4 py-2 bg-gray-300 text-black rounded-lg hover:bg-gray-400"
          >
            Batal
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
          >
            {{ isEdit ? 'Simpan Perubahan' : 'Tambah' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
