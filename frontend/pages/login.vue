<template>
  <v-container class="d-flex flex-column justify-center align-center flex-grow-1">
      <h1 class="mb-4 site-name text-h2 text-primary">CarHorizontal</h1>
      <v-form v-model="valid" style="width: 50%" @submit.prevent="login">
        <div>
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
                  type="password"
                  :counter="10"
                  label="Password"
                  hide-details
                  required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-btn type="submit" class="mt-4" color="primary">Login</v-btn>
        </div>
      </v-form>
  </v-container>
</template>

<script setup lang="ts">
const valid = ref(false)
const email = ref('')
const password = ref('')


definePageMeta({
  layout: 'guest',
  middleware: ['guest'],
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

<style scoped>
.site-name {
  font-family: Afacad, Helvetica, sans-serif;
  font-weight: 500
}
</style>