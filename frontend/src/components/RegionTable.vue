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
      <Column field="continentName">
        <template #header>
          <SortableTableHeader  
            title="Continent"
            fieldCode="continentName"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="regionName">
        <template #header>
          <SortableTableHeader 
            title="Region"
            fieldCode="regionName"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="countryCount">
        <template #header>
          <SortableTableHeader 
            title="Countries"
            fieldCode="countryCount"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="avgLifeExpectancy">
        <template #header>
          <SortableTableHeader
            title="Life expectancy"
            fieldCode="avgLifeExpectancy"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="totalPopulation">
        <template #header>
          <SortableTableHeader
            title="Population"
            fieldCode="totalPopulation"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="cityCount">
        <template #header>
          <SortableTableHeader
            title="Cities"
            fieldCode="cityCount"
            :activeColumn="activeColumn"
            @applyFilters="applyFilters"/>
        </template>
      </Column>
      <Column field="languageCount">
        <template #header>
          <SortableTableHeader 
            title="Languages"
            fieldCode="languageCount"
            :activeColumn="activeColumn" 
            @applyFilters="applyFilters"/>
        </template>
      </Column>
    </DataTable>
  </template>
</template>
<script setup lang="ts">
import { onMounted, ref, type Ref } from 'vue'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import ProgressSpinner from 'primevue/progressspinner'
import { fetchRegions, type IRegion } from '@/utils/RegionService'
import SortableTableHeader from '@/components/SortableTableHeader.vue'

type Direction = 'asc' | 'desc'
interface Filter {
  sort_field?: string
  sort_direction?: Direction
}

const isDataLoaded = ref(false)
const regions: Ref<IRegion[]> = ref([])
const filters: Ref<Filter> = ref({})
const activeColumn = ref('')

const refreshRegionList = async () => {
  const data = await fetchRegions(import.meta.env.VITE_REGION_API_URL, filters.value)
  if (data !== false) {
    regions.value = data
    isDataLoaded.value = true
  }
}

const applyFilters = async (properties: {
  field: string, direction: Direction
}) => {
  filters.value = {
    sort_field: properties.field,
    sort_direction: properties.direction
  }
  activeColumn.value = properties.field;
  await refreshRegionList()
}

onMounted(async () => {
  await refreshRegionList()
})
</script>

<style>
.p-datatable .p-datatable-thead > tr > th[role='columnheader'] {
  padding: 0;
}
</style>