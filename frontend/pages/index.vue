<template>
  <div>
    {{ data }}
  </div>
</template>

<script setup lang="ts">
const { status, data, getSession } = useAuth()
</script>