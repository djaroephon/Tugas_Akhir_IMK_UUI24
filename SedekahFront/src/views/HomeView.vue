<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import CardTotal from '@/components/CardTotal.vue'

const totalKamar = ref(0)
const totalAnak = ref(0)

onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/stats')
    totalKamar.value = res.data.kamar
    totalAnak.value = res.data.anak
  } catch (err) {
    console.error('Gagal ambil data statistik:', err)
  }
})
</script>

<template>
  <div class="relative w-full overflow-hidden">
    <img
      src="../../public/img/bg-putri.jpg"
      alt="Background Hero"
      class="w-full h-full object-cover brightness-75"
    />
    <div
      class="absolute inset-0 flex flex-col justify-center items-center text-center px-4 sm:px-6 lg:px-8"
    >
      <h1 class="text-white text-3xl sm:text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-md">
        Selamat Datang di Panti Asuhan Aceh
      </h1>
      <p
        class="text-white text-base sm:text-lg md:text-xl max-w-3xl font-semibold mb-8 drop-shadow-md"
      >
        Kami adalah lembaga sosial yang peduli terhadap anak-anak yatim piatu dan dhuafa. Mari
        bersama-sama memberikan kasih sayang dan perhatian kepada mereka.
      </p>
      <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
        <router-link
          to="/kamar"
          class="bg-green-500 text-white px-6 py-3 rounded-md hover:bg-green-600 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-400"
          >Jelajahi Kamar</router-link
        >
        <router-link
          to="/donasi"
          class="bg-green-500 text-white px-6 py-3 rounded-md hover:bg-green-600 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-400"
          >Mari Donasi</router-link
        >
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <CardTotal :totalRuang="totalKamar" :totalAnak="totalAnak" />
  </div>

  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Tentang Kami</h2>
        <p class="text-gray-700 mb-6 leading-relaxed">
          Panti Asuhan Aceh adalah lembaga sosial yang didirikan untuk memberikan perlindungan dan
          pendidikan kepada anak-anak yatim piatu dan dhuafa. Kami berkomitmen untuk memberikan
          kasih sayang, perhatian, dan pendidikan yang layak bagi mereka.
        </p>
        <p class="text-gray-700 mb-6 leading-relaxed">
          Kami percaya bahwa setiap anak berhak mendapatkan kesempatan untuk tumbuh dan berkembang
          dalam lingkungan yang aman dan penuh kasih. Mari bersama-sama memberikan dukungan kepada
          mereka.
        </p>
        <p class="text-gray-700 leading-relaxed">
          Terima kasih atas dukungan Anda untuk anak-anak kami. Bersama kita bisa membuat perbedaan!
        </p>
      </div>
      <img
        src="../../public/img/hr.jpeg"
        alt="Foto Panti"
        class="w-full h-72 sm:h-80 md:h-96 object-cover rounded-xl shadow-lg"
      />
    </div>
  </section>

  <!-- Visi dan Misi + Surah Section -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <div>
      <h2 class="text-3xl font-bold mb-6 text-gray-900">Visi dan Misi</h2>
      <p class="text-gray-700 mb-4 leading-relaxed">
        <strong>Visi:</strong> Menciptakan lingkungan yang aman dan mendukung bagi anak-anak yatim
        piatu dan dhuafa, di mana mereka dapat tumbuh dan berkembang dengan baik.
      </p>
      <p class="text-gray-700 mb-4 leading-relaxed">
        <strong>Misi:</strong> Memberikan pendidikan, perawatan, dan dukungan yang dibutuhkan oleh
        anak-anak tersebut untuk mencapai potensi penuh mereka.
      </p>
      <p class="text-gray-700 leading-relaxed">
        Kami juga berkomitmen untuk meningkatkan kesadaran masyarakat tentang pentingnya memberikan
        perhatian kepada anak-anak yang membutuhkan.
      </p>
    </div>

    <!-- Surah Al-Ma'un  -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold text-green-700 mb-2">Surah Al-Ma'un</h3>
      <p class="italic text-gray-800 mb-4">
        "Tahukah kamu (orang) yang mendustakan agama? Itulah orang yang menghardik anak yatim, dan
        tidak mendorong memberi makan orang miskin. Maka celakalah orang-orang yang shalat, (yaitu)
        orang-orang yang lalai dari shalatnya, yang berbuat riya', dan enggan (menolong dengan)
        barang berguna."
      </p>
      <p class="text-sm text-gray-600">— QS. Al-Ma'un</p>
    </div>
  </section>
</template>
