<template>
  <v-container>
    <div></div>
<!--    <template v-if="true">-->
<!--      <template v-if="isClient()">-->
<!--&lt;!&ndash;        <UserCarsTable :user-id="auth.data.value.user.id" />&ndash;&gt;-->
<!--      </template>-->
<!--      <template v-else-if="isServiceEmployee()">-->
<!--&lt;!&ndash;        <ServiceCarsTable :service-id="auth.data.value.user.service_id" />&ndash;&gt;-->
<!--      </template>-->
<!--      <template v-else-if="isSystemAdmin()">-->
        <CarsTable />
<!--      </template>-->
<!--    </template>-->

  </v-container>
</template>

<script setup lang="ts">
import {useJWT} from "~/composables/useJWT";
import {definePageMeta} from "#imports";


// const token = useCookie('token')
// const {isClient, isServiceEmployee, isSystemAdmin} = useRoles()

definePageMeta({
  middleware: ['auth'],
})
</script>