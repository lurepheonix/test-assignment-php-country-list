<template>
  <template v-if="!isDataLoaded">
    <div class="h-full w-full flex align-content-center justify-content-center">
      <div>
        <ProgressSpinner />
        <p class="text-2xl font-medium">Loading regions...</p>
      </div>
    </div>
  </template>
  <template v-if="isDataLoaded">
    <DataTable :value="regions" stripedRows>
      <Column field="continentName" header="Continent" sortable></Column>
      <Column field="regionName" header="Region" sortable></Column>
      <Column field="countryCount" header="Countries" sortable></Column>
      <Column field="avgLifeExpectancy" header="Life expectancy" sortable></Column>
      <Column field="totalPopulation" header="Population" sortable></Column>
      <Column field="cityCount" header="Cities" sortable></Column>
      <Column field="languageCount" header="Languages" sortable></Column>
    </DataTable>
  </template>
</template>
<script setup lang="ts">
import { onMounted, ref, type Ref } from 'vue'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import ProgressSpinner from 'primevue/progressspinner'
import { fetchRegions, type IRegion } from '@/utils/RegionService'

const isDataLoaded = ref(false)
const regions: Ref<IRegion[]> = ref([])
onMounted(async () => {
  const data = await fetchRegions('http://192.168.100.115:8081/api/region')
  if (data !== false) {
    regions.value = data
    isDataLoaded.value = true
  }
})
</script>
