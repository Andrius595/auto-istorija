<template>
  <div>
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="headers"
        :items-length="totalItems"
        :items="serverItems"
        :loading="loading"
        class="elevation-1"
        item-value="name"
        @update:options="loadItems"
    >
      <template v-slot:top>
        <div class="pa-2 d-flex justify-space-between align-center bg-secondary">
          <h2>Cars list</h2>
          <v-btn color="primary" @click="createItem">Create car</v-btn>
        </div>
        <v-divider />
      </template>
      <template v-slot:item.owner="{ item }">
        <span>{{ item.owner.first_name + ' ' + item.owner.last_name }}</span>
      </template>
      <template v-slot:item.actions="{ item }">
        <div class="d-flex flex-nowrap justify-end">
          <v-icon
              color="secondary"
              class="mr-2"
              small
              @click="editItem(item.id)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
              color="red-darken-1"
              small
              @click="deleteItem(item.id)"
          >
            mdi-delete
          </v-icon>
        </div>
      </template>
    </v-data-table-server>
    <DeleteCarDialog :car-id="carId" :visible="deleteDialogVisible" @close="deleteDialogVisible = false" @confirm="carDeleted" />
    <EditCarDialog :car-id="carId" :visible="editDialogVisible" @close="editDialogVisible = false" @confirm="carEdited" />
    <CreateCarDialog :visible="createDialogVisible" @close="createDialogVisible = false" @confirm="carCreated" />
  </div>
</template>

<script setup lang="ts">
import {PaginatedResponse} from "~/types/Responses";
import {Car} from "~/types/Car";
import DeleteCarDialog from "~/components/Car/DeleteCarDialog.vue";
import EditCarDialog from "~/components/Car/EditCarDialog.vue";
import CreateCarDialog from "~/components/Car/CreateCarDialog.vue";

const itemsPerPage = ref(5)
const headers = ref([
  {title: 'Make', key: 'make'},
  {title: 'Model', key: 'model'},
  {title: 'Vin', key: 'vin'},
  {title: 'Owner', key: 'owner'},
  {title: 'Actions', key: 'actions', sortable: false, align: 'end' },
])
const totalItems = ref(0)
const serverItems = ref<Car[]>([])
const loading = ref(true)

const deleteDialogVisible = ref(false)
const editDialogVisible = ref(false)
const createDialogVisible = ref(false)
const carId = ref<number|undefined>(undefined)


async function loadItems({page, itemsPerPage, sortBy}: { page: number, itemsPerPage: number, sortBy: { key: string, order: 'asc'|'desc'}[] }) {
  loading.value = true
  const query = {
    perPage: itemsPerPage,
    page,
    sortBy: sortBy[0]?.key,
    sortDirection: sortBy[0]?.order,
  }

  const { data }  = await backFetch<PaginatedResponse<Car>>('/cars', {
    method: 'GET',
    query,
    headers: {'Accept': 'application/json'},
  })

  serverItems.value = data.value?.data || []
  totalItems.value = data.value?.total || 0
  loading.value = false
}


function createItem() {
  createDialogVisible.value = true
}
function editItem(id: number) {
  carId.value = id
  editDialogVisible.value = true
}

function deleteItem(id: number) {
  carId.value = id
  deleteDialogVisible.value = true
}

async function carDeleted() {
  deleteDialogVisible.value = false
  await loadItems({page: 1, itemsPerPage: itemsPerPage.value, sortBy: []})
}

async function carEdited() {
  editDialogVisible.value = false
  await loadItems({page: 1, itemsPerPage: itemsPerPage.value, sortBy: []})
}

async function carCreated() {
  createDialogVisible.value = false
  await loadItems({page: 1, itemsPerPage: itemsPerPage.value, sortBy: []})
}

</script>

<style scoped>

</style>