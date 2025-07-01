<template>
  <div
    class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center text-center hover:shadow-xl transition duration-300"
  >
    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ kamar.nama }}</h3>
    <router-link
      :to="{ name: 'detailKamar', params: { id: kamar.id } }"
      class="bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 transition duration-300 mt-auto"
    >
      Lihat Detail
    </router-link>
  </div>
</template>

<script setup>
defineProps({
  kamar: Object,
})
</script>
