<template>
  <v-container>
    <template v-if="true">
      <template v-if="isClient">
        <UserCarsTable :user-id="user.id" />
      </template>
      <template v-else-if="isServiceEmployee">
        <ServiceCarsTable :service-id="user.service_id as number" />
      </template>
      <template v-else-if="isSystemAdmin">
        <CarsTable />
      </template>
    </template>
  </v-container>
</template>

<script setup lang="ts">
import UserCarsTable from "~/components/Car/Tables/UserCarsTable.vue";
import ServiceCarsTable from "~/components/Car/Tables/ServiceCarsTable.vue";
import CarsTable from "~/components/Car/Tables/CarsTable.vue";

const { isClient, isServiceEmployee, isSystemAdmin } = useRoles()
const auth = useAuth()

const user = await auth.getUser()


definePageMeta({
  middleware: ['auth'],
})
</script>