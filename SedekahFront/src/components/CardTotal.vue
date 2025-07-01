<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <!-- Card Total Ruangan -->
    <div
      class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between transform transition duration-300 hover:-translate-y-1 hover:shadow-lg"
    >
      <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-1">Total Ruangan</h2>
        <p class="text-3xl font-bold text-indigo-600">{{ animatedRuang }}</p>
      </div>
      <div class="text-indigo-500 text-4xl">
        <i class="fas fa-door-open animate-pulse"></i>
      </div>
    </div>

    <!-- Card Total Anak Asuh -->
    <div
      class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between transform transition duration-300 hover:-translate-y-1 hover:shadow-lg"
    >
      <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-1">Total Anak Asuh</h2>
        <p class="text-3xl font-bold text-green-600">{{ animatedAnak }}</p>
      </div>
      <div class="text-green-500 text-4xl">
        <i class="fas fa-child animate-pulse"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue'

const props = defineProps({
  totalRuang: Number,
  totalAnak: Number,
})

const animatedRuang = ref(0)
const animatedAnak = ref(0)

const animateCount = (target, refVar) => {
  const speed = 1
  const step = () => {
    if (refVar.value < target) {
      refVar.value += 1
      setTimeout(step, speed)
    } else {
      refVar.value = target
    }
  }
  step()
}

watchEffect(() => {
  animateCount(props.totalRuang, animatedRuang)
  animateCount(props.totalAnak, animatedAnak)
})
</script>
