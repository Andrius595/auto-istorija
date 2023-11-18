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
import {VDataTableServer} from "vuetify/labs/VDataTable";

const itemsPerPage = ref(5)
const headers = ref([
  { text: 'Make', value: 'make' },
  { text: 'Model', value: 'model' },
])
const totalItems = ref(0)
const serverItems = ref([])
const loading = ref(true)


async function loadItems({ page, itemsPerPage, sortBy}: { page: number, itemsPerPage: number, sortBy: string }) {
  loading.value = true
  const query = {
    perPage: itemsPerPage,
    page,
  }


  const { data } = await backFetch('/cars', {
    method: 'GET',
    query,
    headers: {'Accept': 'application/json'},
  })

  serverItems.value = data.value.data
  totalItems.value = data.value.total
  loading.value = false
}

</script>

<style scoped>

</style>