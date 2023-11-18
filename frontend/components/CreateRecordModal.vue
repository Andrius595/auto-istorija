<template>
  <v-dialog :model-value="value">
    <v-card>
      <v-card-title>Add record</v-card-title>

      <v-card-text v-if="appointmentId">
        <v-form>
          <v-text-field v-model="mileage" label="Current Mileage" type="number" />
          <v-textarea v-model="description" label="Description" />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn color="primary" @click="addRecord">Add record</v-btn>
        <v-btn color="warning" @click="value = false">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
const props = defineProps({
  value: {
    type: Boolean,
    required: true,
  },
  appointmentId: {
    type: Number,
    required: true,
  }
})

const mileage = ref(0)
const description = ref('')

async function addRecord() {
  const { data } = await backFetch(`appointments/${props.appointmentId}/records`, {
    method: 'POST',
    body: {
      current_mileage: mileage.value,
      description: description.value,
      mileage_type: 0,
    },
    headers: {'Accept': 'application/json'},
  })
}

</script>