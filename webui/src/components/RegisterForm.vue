<script setup>
import { ref } from 'vue';
import {register} from "@/services/authentication/register.ts";

const emit = defineEmits(['switch']);

const username = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');

const submitRegister = async () => {
  if (password.value !== confirmPassword.value) {
    alert('Passwords do not match');
    return;
  }

  try{
     const response = await register({username: username.value, email: email.value, password: password.value});
     console.log(response);
     emit('switch');
  }
  catch(e)
  {
    console.error("something went wrong", e)
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
      <button type="submit" class="submit-btn">Register</button>
    </form>
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
</style>