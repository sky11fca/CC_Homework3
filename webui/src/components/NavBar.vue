<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const username = ref('User');

// Watch route changes to update the username dynamically after login
watch(() => route.path, () => {
  const token = localStorage.getItem('jwt_token'); // Adjust if you use a specific getAuth() function
  if (token) {
    try {
      // Decode the JWT payload
      const payload = JSON.parse(atob(token.split('.')[1]));
      username.value = payload.name  || payload.email || 'User';
    } catch (e) {
      console.error('Failed to parse token');
    }
  }
}, { immediate: true });

const logout = () => {
  localStorage.removeItem('jwt_token');
  localStorage.removeItem("username");// Adjust if you use a specific clearAuth() function
  router.push({ name: 'LoginOrRegister' });
};
</script>

<template>
  <nav class="navbar" v-if="$route.name !== 'LoginOrRegister'">
    <div class="nav-links">
      <router-link :to="{ name: 'ShopPage' }" class="nav-link">Shop Page</router-link>
      <router-link :to="{ name: 'AdminDashboard' }" class="nav-link">Admin Dashboard</router-link>
      <router-link :to="{ name: 'AiAssistant' }" class="nav-link">AI Assistant</router-link>
    </div>
    <div class="user-actions">
      <span class="username">Welcome, {{ username }}</span>
      <button @click="logout" class="logout-btn">Logout</button>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
  font-family: Arial, sans-serif;
}
.nav-links {
  display: flex;
  gap: 1rem;
  font-family: Arial, sans-serif;
}
.user-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
  font-family: Arial, sans-serif;
}
.username {
  font-weight: bold;
  color: #333;
  font-family: Arial, sans-serif;
}
.logout-btn {
  padding: 0.5rem 1rem;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  font-family: Arial, sans-serif;
}
.logout-btn:hover {
  background-color: #c82333;
}
.nav-link {
  background: none;
  border: none;
  padding: 0.5rem 1rem;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 4px;
  font-weight: bold;
  text-decoration: none;
  color: #333;
  font-family: Arial, sans-serif;
}
.nav-link:hover {
  background-color: #e9ecef;
}
/* Vue Router automatically applies this class to the active link */
.nav-link.router-link-active {
  background-color: #008CBA;
  color: white;
}
</style>