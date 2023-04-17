<template>
  <header class="container my-2">
    <h2 class="text-xl font-semibold">List of regions</h2>
  </header>

  <main class="container">
    <RegionTable />
  </main>
</template>
<script setup lang="ts">
import RegionTable from './components/RegionTable.vue'
</script>
