<template>
  <v-list class="navigation">
    <v-list-item active-color="secondary" to="/cars">
      <v-list-item-title>Cars list</v-list-item-title>
    </v-list-item>
    <v-list-item active-color="secondary" to="/services">
      <v-list-item-title>Services list</v-list-item-title>
    </v-list-item>
    <v-list-item active-color="secondary" v-if="isServiceEmployee" to="/services/create-appointment">
      <v-list-item-title>Create Appointment</v-list-item-title>
    </v-list-item>
  </v-list>
</template>

<script setup lang="ts">
const { isServiceEmployee } = useRoles()
</script>

<style scoped>

</style>