<script setup lang="ts">
import {ref} from "vue"
import RegisterForm from "@/components/RegisterForm.vue";
import LoginForm from "@/components/LoginForm.vue";

const showLogin = ref(true);

</script>

<template>
  <LoginForm v-if="showLogin" @switch="showLogin = false"/>
  <RegisterForm v-else @switch="showLogin = true"/>
</template>

<style scoped>

</style>