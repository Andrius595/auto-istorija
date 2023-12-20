<script setup lang="ts">
import backFetch from "~/utils/backFetch";
import {errorToast, successToast} from "~/utils/toast";
import {Car} from "~/types/Car";

const emit = defineEmits(['close', 'confirm', 'update:visible'])
const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  carId: {
    type: Number,
    required: false,
  },
  editPath: {
    type: String,
    required: false,
    default: '/cars/',
  }
})

const errors = ref([])

watch(() => props.carId, (carId) => {
  if (carId && props.visible) {
    loadCar(carId)
  }
})

const car = ref<Partial<Car>>({
  owner_id: null,
  make: '',
  model: '',
  vin: '',
  year_of_manufacture: null,
})


function closeDialog() {
  emit('close')
}

async function loadCar(carId: number) {
  const { data } = await backFetch<Car>(props.editPath + props.carId, {
    method: 'GET',
    headers: {'Accept': 'application/json'},
  })
  if (data.value) {
    car.value = data.value
  }
}

async function confirmEdit() {
  const { data, error } = await backFetch('/cars/' + props.carId, {
    method: 'PUT',
    body: car.value,
    headers: {'Accept': 'application/json'},
  })

  if (error.value) {
    errors.value = error.value.data.errors
    errorToast(error.value.data.message)
    console.error(error)
    return
  }

  if (data.value) {
    car.value = data.value
  }

}
</script>

<template>
  <v-dialog :model-value="visible" max-width="700px" @update:model-value="(value) => emit('update:visible', value)">
    <v-card>
      <v-card-title class="headline">Edit car</v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" md="4">
              <v-text-field label="Make" v-model="car.make" :error-messages="errors.make" />
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field label="Model" v-model="car.model" :error-messages="errors.make" />
            </v-col>
            <v-col cols="12" md="4">
              <v-text-field label="Year" v-model="car.year_of_manufacture" :error-messages="errors.year_of_manufacture" />
<!--              <v-date-picker-years v-model="car.year_of_manufacture" :error-messages="errors.year_of_manufacture" />-->
<!--              <v-date-picker v-model="car.year_of_manufacture" :error-messages="errors.year_of_manufacture" />-->
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="closeDialog">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="confirmEdit">Update</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>

</style>