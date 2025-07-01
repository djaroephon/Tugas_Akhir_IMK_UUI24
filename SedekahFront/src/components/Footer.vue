<template>
  <footer class="bg-green-700 text-white py-3 z-5">
    <div
      class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6"
    >
      <div class="text-center sm:text-left flex-1">
        <h3 class="text-xl font-bold mb-2">Panti Asuhan Aceh</h3>
        <p class="text-sm max-w-xs mx-auto sm:mx-0">
          Lembaga sosial peduli anak yatim piatu dan dhuafa.
        </p>
      </div>

      <div class="flex justify-center space-x-6 flex-1">
        <a
          href="mailto:Contact@pantiaceh.or.id"
          class="hover:underline break-words"
          aria-label="Email"
          >djaroephon@gmail.com</a
        >
      </div>

      <div class="text-sm text-center sm:text-right flex-1">
        &copy; {{ new Date().getFullYear() }} Panti Asuhan Aceh. All rights reserved.
      </div>
    </div>
  </footer>
</template>

<style scoped>
a {
  word-break: break-word;
}
</style>
