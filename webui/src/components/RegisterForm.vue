<script setup>
import { ref } from 'vue';
import {register} from "@/services/authentication/register.ts";

const emit = defineEmits(['switch']);

const username = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const isSubmitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const submitRegister = async () => {
  errorMessage.value = '';
  successMessage.value = '';

  if (password.value !== confirmPassword.value) {
    errorMessage.value = 'Passwords do not match.';
    return;
  }

  isSubmitting.value = true;

  try{
     await register({username: username.value, email: email.value, password: password.value});
     successMessage.value = 'Account created. You can now log in.';
     emit('switch');
  }
  catch(e)
  {
    if (e instanceof Error) {
      errorMessage.value = e.message;
    } else {
      errorMessage.value = 'An unexpected error occurred during registration.';
    }
  }
  finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="form-card">
    <h2>Register</h2>
    <form @submit.prevent="submitRegister">
      <div class="input-group">
        <label for="username">Username</label>
        <input id="username" type="text" v-model="username" required />
      </div>
      <div class="input-group">
        <label for="email">Email</label>
        <input id="email" type="email" v-model="email" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input id="password" type="password" v-model="password" required />
      </div>
      <div class="input-group">
        <label for="confirmPassword">Confirm Password</label>
        <input id="confirmPassword" type="password" v-model="confirmPassword" required />
      </div>
      <button type="submit" class="submit-btn" :disabled="isSubmitting">
        {{ isSubmitting ? 'Registering...' : 'Register' }}
      </button>
    </form>
    <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>
    <p v-if="successMessage" class="success-msg">{{ successMessage }}</p>
    <button type="button" class="switch-btn" @click="emit('switch')">
      Already have an account? Login
    </button>
  </div>
</template>

<style scoped>
.form-card {
  border: 1px solid #ddd;
  padding: 2rem;
  border-radius: 8px;
  width: 300px;
  display: flex;
  flex-direction: column;
}
.input-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}
.submit-btn {
  padding: 0.5rem;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.switch-btn {
  background: none;
  border: none;
  color: #007bff;
  text-decoration: underline;
  cursor: pointer;
  margin-top: 1rem;
}

.error-msg {
  color: #b00020;
  margin-top: 0.75rem;
  margin-bottom: 0;
  font-size: 0.9rem;
}

.success-msg {
  color: #0a7a35;
  margin-top: 0.75rem;
  margin-bottom: 0;
  font-size: 0.9rem;
}
</style>