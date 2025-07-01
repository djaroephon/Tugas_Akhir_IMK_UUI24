<script setup>
import SideBar from '@/components/admin/SideBar.vue'
import { onMounted, ref } from 'vue'
import ApexChart from 'vue3-apexcharts'
import axios from 'axios'

const totalAnak = ref(0)
const totalKamar = ref(0)
const totalBarang = ref(0)
const totalDana = ref(0)

const chartSeries = ref([
  {
    name: 'Donasi Dana',
    data: [],
  },
])

const chartOptions = ref({
  chart: {
    type: 'line',
    height: 350,
    toolbar: { show: false },
  },
  xaxis: {
    categories: [],
  },
  colors: ['#22c55e'],
  stroke: {
    curve: 'smooth',
  },
})

onMounted(async () => {
  try {
    const [statsRes, danaRes, barangRes] = await Promise.all([
      axios.get('http://127.0.0.1:8000/api/stats'),
      axios.get('http://127.0.0.1:8000/api/total-dana'),
      axios.get('http://127.0.0.1:8000/api/total-barang'),
    ])

    totalKamar.value = statsRes.data.kamar
    totalAnak.value = statsRes.data.anak
    totalDana.value = danaRes.data || 0
    totalBarang.value = barangRes.data.total || 0

    totalDana.value = danaRes.data.reduce((sum, item) => sum + parseInt(item.total), 0)

    const monthNames = [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'Mei',
      'Jun',
      'Jul',
      'Agu',
      'Sep',
      'Okt',
      'Nov',
      'Des',
    ]
    console.log('Total Dana:', totalDana.value)

    const dataDana = []
    const categoriesDana = []

    danaRes.data.forEach((item) => {
      dataDana.push(parseInt(item.total))
      categoriesDana.push(monthNames[item.bulan - 1])
    })

    console.log('Data Dana:', totalDana.value)

    chartSeries.value = [
      {
        name: 'Donasi Dana',
        data: dataDana,
      },
    ]
    chartOptions.value.xaxis.categories = categoriesDana
  } catch (error) {
    console.error('Gagal mengambil data:', error)
  }
})
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <SideBar />
    <div class="ml-64 flex-1 p-6">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-sm font-medium text-gray-500 mb-2">Total Dana Masuk</h2>
          <p class="text-2xl font-bold text-green-600">
            Rp {{ totalDana.toLocaleString('id-ID') }}
          </p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-sm font-medium text-gray-500 mb-2">Total Donasi Barang</h2>
          <p class="text-2xl font-bold text-yellow-600">{{ totalBarang }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-sm font-medium text-gray-500 mb-2">Total Anak</h2>
          <p class="text-2xl font-bold text-blue-600">{{ totalAnak }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-sm font-medium text-gray-500 mb-2">Total Kamar</h2>
          <p class="text-2xl font-bold text-purple-600">{{ totalKamar }}</p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Donasi Dana (per Bulan)</h2>
        <ApexChart type="line" height="350" :options="chartOptions" :series="chartSeries" />
      </div>
    </div>
  </div>
</template>
