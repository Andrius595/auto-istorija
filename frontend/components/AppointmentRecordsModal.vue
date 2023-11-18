<template>
  <v-dialog :model-value="value">
    <v-card>
      <v-card-title>Service Records</v-card-title>
      <v-card-actions>
        <v-spacer />
        <v-btn color="primary" @click="addRecord">Add record</v-btn>
      </v-card-actions>
      <v-card-text v-if="appointmentId">
        <AppointmentRecordsTable :appointment-id="appointmentId" />
      </v-card-text>
    </v-card>
    <CreateRecordModal :appointment-id="appointmentId" :value="showCreateRecordModal" />
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

const showCreateRecordModal = ref(false)

function addRecord() {
  showCreateRecordModal.value = true
}

</script>