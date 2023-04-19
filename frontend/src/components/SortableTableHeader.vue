<template>
  <div 
    class="flex flex-no-wrap justify-content-between h-full w-full p-3 p-sortable-column"
    @click="changeDirection()"
  >
    <span>{{ props.title }}</span>
    <span v-if="direction === false" class="p-sortable-column-icon pi pi-fw pi-sort-alt"></span>
    <span v-if="direction === 'asc'" class="p-sortable-column-icon pi pi-fw pi-sort-amount-down"></span>
    <span v-if="direction === 'desc'" class="p-sortable-column-icon pi pi-fw pi-sort-amount-up-alt"></span>
  </div>
</template>
<script setup lang="ts">
import { camelToSnakeCase } from '@/utils/CaseChanger';
import { ref, watch, type Ref } from 'vue';

const props = defineProps({
  title: String,
  fieldCode: String,
  activeColumn: String
})
const emit = defineEmits(['applyFilters'])

const direction: Ref<'asc' | 'desc' | false> = ref(false);

watch(props, () => {
  if (props.activeColumn !== camelToSnakeCase(props.fieldCode as string) && direction.value !== false) {
    direction.value = false
  }

})


const changeDirection = () => {
  if (direction.value === false || direction.value === 'desc') {
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
