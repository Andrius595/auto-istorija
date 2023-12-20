<template>
  <v-container>
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
    </v-row>
    <v-btn color="primary" class="mt-4" @click="checkVin">Check VIN</v-btn>
    <template v-if="checkPerformed">
    <v-divider class="my-4" />
    <v-row >
      <v-col cols="12">
        <v-alert type="success" v-if="carWasFound">Car was found in the system. Please, make sure data below is correct.</v-alert>
        <v-alert type="error" v-else>Car is not registered in the system. Please, enter it's make and model manually.</v-alert>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
            v-model="make"
            label="Make"
            required
            hide-details
            :disabled="carWasFound"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
            v-model="model"
            label="Model"
            required
            hide-details
            :disabled="carWasFound"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-btn color="primary" class="mt-4" @click="createAppointment">Create appointment</v-btn>
    </template>


  </v-container>
</template>

<script setup lang="ts">
import {GetCarByVinResponse} from "~/types/Responses";
import {errorToast, successToast} from "~/utils/toast";
import {definePageMeta} from "#imports";

const auth = useAuth()

const user = await auth.getUser()

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
  const { data } = await backFetch<GetCarByVinResponse>('/cars/vin/'+vin.value, {
    method: 'get',
  })
  checkPerformed.value = true

  if (data.value && 'id' in data.value) {
    carWasFound.value = true
    carId.value = data.value.id
    make.value = data.value.make
    model.value = data.value.model
  }
}

async function createAppointment() {
  const { data, error } = await backFetch('/services/'+user.service_id+'/appointments', {
    method: 'post',
    body: {
      car_id: carId.value,
      make: make.value,
      model: model.value,
      vin: vin.value,
    }
  })

  if (error.value) {
    errorToast(error.value.data.message)
    console.error(error.value)
    return
  }

  successToast('Appointment created successfully!')
}

definePageMeta({
  middleware: ['auth', 'service-employee']
})
</script>