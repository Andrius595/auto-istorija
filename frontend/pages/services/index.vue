<template>
  <div style="width: 100%">
    Services list
<!--    <v-data-table-server-->
<!--      v-model:items-per-page="itemsPerPage"-->
<!--      :headers="headers"-->
<!--      :items-length="totalItems"-->
<!--      :items="serverItems"-->
<!--      :loading="loading"-->
<!--      class="elevation-1"-->
<!--      item-value="name"-->
<!--      @update:options="loadItems"-->
<!--    />-->
  </div>
</template>

<script setup lang="ts">
import {definePageMeta} from "#imports";

const itemsPerPage = ref(5)
const headers = ref([
  { text: 'Title', value: 'title' },
])
const totalItems = ref(0)
const serverItems = ref([])
const loading = ref(true)

// async function loadItems({ page, itemsPerPage, sortBy}) {
//   loading.value = true
//   const query = {
//     perPage: itemsPerPage,
//     page,
//   }
//
//   const { data } = await backFetch('/services', {
//     method: 'GET',
//     query,
//     headers: {'Accept': 'application/json'},
//   })
//
//   console.log(data)
//
//
//   serverItems.value = data.value.data
//   totalItems.value = data.value.total
//   loading.value = false

// }

definePageMeta({
  middleware: ['auth'],
})
</script>