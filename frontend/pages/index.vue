<template>
  <div>
    Dashboard
  </div>
</template>

<script setup lang="ts">
import {definePageMeta} from "#imports";

definePageMeta({
  middleware: ['auth'],
})
</script>