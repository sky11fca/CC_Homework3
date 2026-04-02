<script setup>
import { ref } from 'vue';
import { login } from '@/services/authentication/login';
import { useRouter } from 'vue-router';
import { setAuth } from '@/services/authentication/authState';

const emit = defineEmits(['switch']);

const email = ref('');
const password = ref('');
const isSubmitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const debugMessage = ref('Idle');

const router = useRouter();

const submitLogin = async () => {
  console.log('[LoginForm] submit clicked', { email: email.value, passwordLength: password.value.length });
  debugMessage.value = 'Submit clicked. Preparing request...';

  errorMessage.value = '';
  successMessage.value = '';

  if (!email.value.trim()) {
    debugMessage.value = 'Validation failed: missing email.';
    errorMessage.value = 'Email is required.';
    return;
  }

  if (!password.value.trim()) {
    debugMessage.value = 'Validation failed: missing password.';
    errorMessage.value = 'Password is required.';
    return;
  }

  isSubmitting.value = true;

  try {
    debugMessage.value = 'Calling login API...';
    const token = await login({ email: email.value, plainPassword: password.value });
    console.log('[LoginForm] token received', `${token.slice(0, 20)}...`);
    debugMessage.value = 'Token received. Saving auth state...';
      
    setAuth(token);
    console.log('[LoginForm] token stored in localStorage');
    debugMessage.value = 'Auth saved. Redirecting to shop...';

    successMessage.value = 'Login successful. Redirecting...';
    await router.push({ name: 'ShopPage' });
    console.log('[LoginForm] router.push completed', { currentRoute: router.currentRoute.value.name });
    debugMessage.value = `router.push completed. Current route: ${String(router.currentRoute.value.name)}`;

    // Fallback for cases where SPA navigation does not visibly update the page.
    if (router.currentRoute.value.name !== 'ShopPage') {
      console.warn('[LoginForm] router did not land on ShopPage, forcing hard redirect');
      debugMessage.value = 'Router did not switch to ShopPage. Forcing hard redirect...';
      window.location.href = '/shop';
    }

  } catch (error) {
    console.error('[LoginForm] submit failed', error);
    debugMessage.value = 'Login failed. See error message below.';
    if (error instanceof Error) {
      errorMessage.value = error.message;
    } else {
      errorMessage.value = 'An unexpected error occurred during login.';
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="form-card">
    <h2>Login</h2>
    <form>
      <div class="input-group">
        <label for="email">Email</label>
        <input id="email" type="email" v-model="email" />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input id="password" type="password" v-model="password" />
      </div>
      <button type="button" class="submit-btn" :disabled="isSubmitting" @click="submitLogin">
        {{ isSubmitting ? 'Logging in...' : 'Login' }}
      </button>
    </form>
    <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>
    <p v-if="successMessage" class="success-msg">{{ successMessage }}</p>
    <p class="debug-msg">Debug: {{ debugMessage }}</p>
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

.debug-msg {
  color: #444;
  margin-top: 0.75rem;
  margin-bottom: 0;
  font-size: 0.85rem;
}
</style>