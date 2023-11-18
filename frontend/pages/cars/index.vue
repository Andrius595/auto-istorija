<template>
  <v-container>
    <template v-if="auth !== null">
      <template v-if="isClient()">
        <UserCarsTable :user-id="auth.data.value.user.id" />
      </template>
      <template v-else-if="isSystemAdmin()">
        <ServiceCarsTable :service-id="auth.data.value.user.service_id" />
      </template>
      <template v-else-if="isSystemAdmin()">
        <CarsTable />
      </template>
    </template>

  </v-container>
</template>

<script setup lang="ts">
const auth = useAuth()
const {isClient, isServiceEmployee, isSystemAdmin} = useRoles()
</script>