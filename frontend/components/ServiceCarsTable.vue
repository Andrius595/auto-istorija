<template>
  <div>
    <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        :headers="headers"
        :items-length="totalItems"
        :items="serverItems"
        :loading="loading"
        class="elevation-1"
        item-value="id"
        @update:options="loadItems"
    >
      <template #item.actions="{ item }">
        <div class="d-flex justify-end gap-x-2">
          <v-icon icon="mdi-eye" @click="viewInfo(item.id)"/>
          <v-icon icon="mdi-wrench" @click="viewRecords(item.active_appointment.id)"/>
          <v-icon icon="mdi-check-circle" @click="completeAppointment(item.id)"/>
        </div>
      </template>
    </VDataTableServer>
    <AppointmentRecordsModal :value="showRecordsModal" :appointment-id="actionItemId" />
  </div>
</template>

<script setup lang="ts">
import {VDataTableServer} from "vuetify/labs/VDataTable";
import {PaginatedResponse} from "#build/types/Responses";
import {Car} from "#build/types/Car";

const props = defineProps({
  serviceId: {
    type: Number,
    required: true,
  }
})

const itemsPerPage = ref(5)
const headers = ref([
  {title: 'Make', key: 'make', width: '32%'},
  {title: 'Model', key: 'model', width: '32%'},
  {title: 'VIN', key: 'vin', width: '30%'},
  {title: 'Actions', key: 'actions', sortable: false, align: 'end', width: '6%'},
])
const totalItems = ref(0)
const serverItems = ref([])
const loading = ref(true)

const showRecordsModal = ref(false)
const actionItemId = ref<number|null>(null)

async function loadItems({page, itemsPerPage, sortBy}: { page: number, itemsPerPage: number, sortBy: string }) {
  loading.value = true
  const query = {
    perPage: itemsPerPage,
    page,
  }

  const response: PaginatedResponse<Car> = await useServerFetch('/cars', {
    method: 'GET',
    query,
    headers: {'Accept': 'application/json'},
  })

  console.log('data', response)



  // const {data} = await backFetch('/services/' + props.serviceId + '/cars', {
  //   method: 'GET',
  //   query,
  //   headers: {'Accept': 'application/json'},
  // })

  serverItems.value = response.data
  totalItems.value = response.total
  loading.value = false
}

function viewInfo() {
  console.log('view info')
}

function viewRecords(itemId: number) {
  actionItemId.value = itemId
  showRecordsModal.value = true
}

function completeAppointment(itemId: number) {
  console.log('complete appointment')
}

</script>

<style scoped>
.gap-x-2 {
  column-gap: 1rem;
}
</style>