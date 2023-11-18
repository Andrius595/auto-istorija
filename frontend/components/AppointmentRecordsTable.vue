<template>
  <VDataTableServer
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items-length="totalItems"
      :items="serverItems"
      :loading="loading"
      class="elevation-1"
      item-value="name"
      @update:options="loadItems"
  />
</template>

<script setup lang="ts">
const itemsPerPage = ref(5)
const headers = ref([
  { title: 'Mileage', key: 'current_mileage' },
  { title: 'Description', key: 'description' },
])
const totalItems = ref(0)
const serverItems = ref([])
const loading = ref(true)

const props = defineProps({
  appointmentId: {
    type: Number,
    required: true,
  }
})

watch(() => props.appointmentId, (newVal) => {
  if (newVal) {
    loadItems({page: 1, itemsPerPage: itemsPerPage.value, sortBy: 'id'})
  }
})


async function loadItems({ page, itemsPerPage, sortBy}: { page: number, itemsPerPage: number, sortBy: string }) {
  loading.value = true

  const query = {
    perPage: itemsPerPage,
    page,
  }

  const {data} = await backFetch('/appointments/' + props.appointmentId + '/records', {
    method: 'GET',
    query,
    headers: {'Accept': 'application/json'},
  })

  serverItems.value = data.value.data
  totalItems.value = data.value.total
  loading.value = false
}
</script>