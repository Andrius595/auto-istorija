<script setup lang="ts">
import backFetch from "~/utils/backFetch";
import {errorToast, successToast} from "~/utils/toast";
import {Car} from "~/types/Car";
import {User} from "~/types/User";

const emit = defineEmits(['close', 'confirm', 'update:visible'])
const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  createPath: {
    type: String,
    required: false,
    default: '/cars',
  },
  registration: {
    type: Boolean,
    required: false,
    default: false,
  }
})

const errors = ref([])

const emptyCar = {
  owner_id: null,
  make: '',
  model: '',
  vin: '',
  year_of_manufacture: null,
}
const car = ref<Partial<Car>>({...emptyCar})
const vin = ref('')
const owners = ref<User[]>([])

const menu = ref(false)

if (!props.registration) {
  await loadOwners()
}

function closeDialog() {
  emit('close')
}

async function confirmCreate() {
  console.log('confirmCreate')
  car.value.vin = vin.value
  const { data, error } = await backFetch<Car>(props.createPath, {
    method: 'POST',
    body: car.value,
    headers: {'Accept': 'application/json'},
  })

  if (error.value) {
    errors.value = error.value.data.errors
    errorToast(error.value.data.message)
    return
  }

  successToast('Car created successfully!')
  emit('confirm')
}

async function loadOwners() {
  const { data, error } = await backFetch<User>('/users/index', {
    method: 'GET',
    headers: {'Accept': 'application/json'},
  })

  if (error.value) {
    errors.value = error.value.data.errors
    errorToast('Could not fetch owners')
    return
  }

  owners.value = data.value
}
</script>

<template>
  <v-dialog :model-value="visible" max-width="700px" @update:model-value="(value) => emit('update:visible', value)">
    <v-card>
      <v-card-title class="headline">Edit car</v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field label="Make" v-model="car.make" :error-messages="errors.make" />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field label="Model" v-model="car.model" :error-messages="errors.model" />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field label="Vin" v-model="vin" :error-messages="errors.vin" />
            </v-col>
            <v-col cols="12" md="6">
              <v-menu
                  v-model="menu"
                  :close-on-content-click="false"
                  location="end"
              >
                <template v-slot:activator="{ props }">
                  <v-text-field v-bind="props" label="Year" v-model="car.year_of_manufacture" :error-messages="errors.year_of_manufacture" />
                </template>

                <v-card min-width="300">
                  <v-date-picker-years v-model="car.year_of_manufacture" min="1980" max="2023"></v-date-picker-years>

                  <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="primary"
                        variant="text"
                        @click="menu = false"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>
            </v-col>
            <v-col v-if="!registration" cols="12">
              <v-select label="Owner" v-model="car.owner_id" :error-messages="errors.owner_id" :items="owners" item-value="id">
                <template v-slot:item="{ item, index, props}">
                  <v-list-item v-bind="props">{{ props.title.first_name + ' ' + props.title.last_name}}</v-list-item>
                </template>
                <template v-slot:selection="{ item, index }">
                  {{ item.props.title.first_name + ' ' + item.props.title.last_name}}
                </template>
              </v-select>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click.prevent="closeDialog">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click.once="confirmCreate">Create</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>

</style>