<template>
  <v-form v-model="valid" style="width: 100%" @submit.prevent="login">
    <v-container>
      <v-row>
        <v-col
            cols="12"
        >
          <v-text-field
              v-model="email"
              label="E-mail"
              hide-details
              required
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
        >
          <v-text-field
              v-model="password"
              :counter="10"
              label="Password"
              hide-details
              required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-btn type="submit">Login</v-btn>
    </v-container>
  </v-form>
</template>

<script setup lang="ts">
const valid = ref(false)
const email = ref('')
const password = ref('')


definePageMeta({
  layout: 'guest',
})

async function login() {
  if (valid.value) {
    const response = await useFetch('/api/auth/login', {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value,
      },
    })

    await navigateTo('/')


    // if (status.value === 'success') {
    //   console.log(data.value.token)
    //   const token = useCookie('token')
    //   token.value = data.value.token
    // }
  }
}
</script>