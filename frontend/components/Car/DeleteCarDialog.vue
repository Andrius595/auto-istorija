<script setup lang="ts">
import backFetch from "~/utils/backFetch";
import {errorToast, successToast} from "~/utils/toast";

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
  deletePath: {
    type: String,
    required: false,
    default: '/cars',
  }
})

function closeDialog() {
  emit('close')
}

function confirmDelete() {
  backFetch('/cars/' + props.carId, {
    method: 'DELETE',
    headers: {'Accept': 'application/json'},
  }).then(() => {
    successToast('Car deleted successfully!')
    emit('confirm')
  }).catch((e) => {
    errorToast('Error deleting car')
    console.error(e)
  })
}
</script>

<template>
<v-dialog :model-value="visible" max-width="500px" @update:model-value="(value) => emit('update:visible', value)">
  <v-card>
    <v-card-title class="headline">Delete car</v-card-title>
    <v-card-text>
      Are you sure you want to delete this car?
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="blue darken-1" text @click="closeDialog">Cancel</v-btn>
      <v-btn color="blue darken-1" text @click="confirmDelete">Delete</v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
</template>

<style scoped>

</style>
