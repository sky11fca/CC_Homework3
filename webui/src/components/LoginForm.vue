<script setup>
import { ref } from 'vue';
import { login } from '@/services/authentication/login';
import { useRouter } from 'vue-router';
import { setAuth } from '@/services/authentication/authState';

const emit = defineEmits(['switch']);

const email = ref('');
const password = ref('');

const router = useRouter();

const submitLogin = async () => {
  try {
    const response = await login({ email: email.value, plainPassword: password.value });

    const data = await response.json();
      
      // Assuming your API returns the token in a standard JSON wrapper like { token: "eyJ..." }
      // If your API returns raw text instead, you can fall back to using `data` directly
    const token = data.token || data;
      
    setAuth(token);
    console.log('Login successful, redirecting...');
    await router.push({ name: 'ShopPage' });

  } catch (error) {
    console.error('An error occurred during login', error);
  }
};
</script>

<template>
  <div class="form-card">
    <h2>Login</h2>
    <form @submit.prevent="submitLogin">
      <div class="input-group">
        <label for="email">Email</label>
        <input id="email" type="email" v-model="email" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input id="password" type="password" v-model="password" required />
      </div>
      <button type="submit" class="submit-btn">Login</button>
    </form>
    <button type="button" class="switch-btn" @click="emit('switch')">
      Need an account? Register
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
  font-family: Arial, sans-serif;
}
.input-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
  font-family: Arial, sans-serif;
}
.submit-btn {
  padding: 0.5rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-family: Arial, sans-serif;
}
.switch-btn {
  background: none;
  border: none;
  color: #007bff;
  text-decoration: underline;
  cursor: pointer;
  margin-top: 1rem;
  font-family: Arial, sans-serif;
}
</style>