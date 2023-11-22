<template>
  <v-data-table-server
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
import type {AsyncData} from "nuxt/app";

const itemsPerPage = ref(5)
const headers = ref([
  {title: 'Make', key: 'make'},
  {title: 'Model', key: 'model'},
  {title: 'Vin', key: 'vin'},
])
const totalItems = ref(0)
const serverItems = ref([])
const loading = ref(true)


async function loadItems({page, itemsPerPage, sortBy}: { page: number, itemsPerPage: number, sortBy: string }) {
  loading.value = true
  const query = {
    perPage: itemsPerPage,
    page,
  }

  const {data}  = await backFetch('/cars', {
    method: 'GET',
    query,
    headers: {'Accept': 'application/json'},
  })

  // console.log('ddd', data.value)

  serverItems.value = data.value?.data || []
  totalItems.value = data.value?.total || 0
  loading.value = false
}

</script>

<style scoped>

</style>