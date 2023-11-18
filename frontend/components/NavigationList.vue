<template>
  <v-list>
    <v-list-item to="/cars">
      <v-list-item-title>Cars list</v-list-item-title>
    </v-list-item>
    <v-list-item to="/services">
      <v-list-item-title>Services list</v-list-item-title>
    </v-list-item>
    <v-list-item v-if="true" to="/services/create-appointment">
      <v-list-item-title>Create Appointment</v-list-item-title>
    </v-list-item>
  </v-list>
</template>

<script setup lang="ts">
// const { isClient, isServiceEmployee, isSystemAdmin } = useRoles()
</script>