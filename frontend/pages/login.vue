<template>
  <v-form v-model="valid" style="width: 100%">
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
      <v-btn @click="login">Login</v-btn>
    </v-container>
  </v-form>
</template>

<script setup lang="ts">
const valid = ref(false)
const email = ref('')
const password = ref('')

const { signIn } = useAuth()

definePageMeta({
  auth: {
    unauthenticatedOnly: true,
    navigateAuthenticatedTo: '/',
  }
})

useFetch('asd', {})

async function login() {
  await signIn('credentials', {email: email.value, password: password.value, callbackUrl: '/'})
}
</script>