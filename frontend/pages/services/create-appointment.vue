<template>
  <div class="w-100">
    <h1>Create appointment</h1>
    <v-row>
      <v-col cols="12">
        <v-text-field
            v-model="vin"
            label="VIN number"
            required
            hide-details
        ></v-text-field>
      </v-col>
      <v-btn @click="checkVin">Check VIN</v-btn>
    </v-row>
    <v-row v-if="checkPerformed">
      <v-col cols="12">
        <span v-if="!carWasFound">Car is not registered in the system. Please, register enter it's make and model manually.</span>
        <span v-else>Car was found in the system. Please, make sure data below is correct.</span>
      </v-col>
      <v-col cols="6">
        <v-text-field
            v-model="make"
            label="Make"
            required
            hide-details
            :disabled="carWasFound"
        ></v-text-field>
      </v-col>
      <v-col cols="6">
        <v-text-field
            v-model="model"
            label="Model"
            required
            hide-details
            :disabled="carWasFound"
        ></v-text-field>
      </v-col>
      <v-btn @click="createAppointment">Create appointment</v-btn>
    </v-row>

  </div>
</template>

<script setup lang="ts">

import {_AsyncData} from "nuxt/dist/app/composables/asyncData";
import {GetCarByVinResponse} from "~/types/Responses";

const auth = useAuth()

const vin = ref('')
const make = ref('')
const model = ref('')
const checkPerformed = ref<boolean>(false)
const carWasFound = ref<boolean>(false)
const carId = ref<number|null>(null)

function resetSearch() {
  make.value = ''
  model.value = ''
  carWasFound.value = false
  carId.value = null
}

async function checkVin() {
  resetSearch()
  const { data }: _AsyncData<GetCarByVinResponse, any> = await backFetch('/cars/vin/'+vin.value, {
    method: 'GET',
  })
  checkPerformed.value = true

  if ('id' in data.value) {
    carWasFound.value = true
    carId.value = data.value.id
    make.value = data.value.make
    model.value = data.value.model
  }
}

async function createAppointment() {
  console.log(auth.data)
  const { data } = await backFetch('/services/'+auth.data.value?.service_id+'/appointments', {
    method: 'POST',
    body: {
      car_id: carId.value,
      make: make.value,
      model: model.value,
      vin: vin.value,
    }
  })
}
</script>