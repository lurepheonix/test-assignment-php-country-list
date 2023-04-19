<template>
  <div 
    class="flex flex-no-wrap justify-content-between h-full w-full p-3 p-sortable-column"
    @click="changeDirection()"
  >
    <span>{{ props.title }}</span>
    <span v-if="direction === ''" class="p-sortable-column-icon pi pi-fw pi-sort-alt"></span>
    <span v-if="direction === 'asc'" class="p-sortable-column-icon pi pi-fw pi-sort-amount-down"></span>
    <span v-if="direction === 'desc'" class="p-sortable-column-icon pi pi-fw pi-sort-amount-up-alt"></span>
  </div>
</template>
<script setup lang="ts">
import { camelToSnakeCase } from '@/utils/CaseChanger';
import { ref, watch } from 'vue';

const props = defineProps({
  title: String,
  fieldCode: String,
  activeColumn: String
})
const emit = defineEmits(['applyFilters'])

const direction = ref('');

watch(props, () => {
  if (props.activeColumn !== props.fieldCode) {
    direction.value = ''
  }
})


const changeDirection = () => {
  if (direction.value === '' || direction.value === 'desc') {
    direction.value = 'asc'
  } else if (direction.value === 'asc') {
    direction.value = 'desc'
  }
  emit('applyFilters', {
    field: camelToSnakeCase(props.fieldCode as string),
    direction: direction.value
  })
}
</script>
