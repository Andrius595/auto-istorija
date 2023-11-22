<template>
  <v-layout class="rounded rounded-md">
    <v-main class="d-flex align-center justify-center w-full" style="min-height: 300px">
      <slot />
    </v-main>
  </v-layout>
</template>
<script setup lang="ts">
import NavigationList from '@/components/NavigationList.vue';
</script>