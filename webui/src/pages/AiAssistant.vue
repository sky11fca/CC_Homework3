<script setup>
import { ref, onMounted } from 'vue';
import { greet } from '../services/aiAssistant/greet';
import { chat } from '../services/aiAssistant/chat';
import ChatMessages from '../components/ChatMessages.vue';
import ChatPrompt from '../components/ChatPrompt.vue';

const messages = ref([]);
const isLoading = ref(false);

const getUsernameFromToken = () => {
    const token = localStorage.getItem('jwt_token');
    if (!token) return "User";
    try {
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const payload = JSON.parse(atob(base64));
        // Try 'username' or 'sub' claims, fallback to "User"
        return payload.name || payload.sub || "User";
    } catch (error) {
        console.error("Failed to parse JWT token:", error);
        return "User";
    }
};

onMounted(async () => {
    try {
        isLoading.value = true;
        const username = getUsernameFromToken();
        const response = await greet(username);
        // We safely attempt to grab response.message in case it's an object,
        // or just fallback to the response if it returns a plain string.
        messages.value.push({ role: 'ai', content: response.greeting });
    } catch (error) {
        console.error("Failed to fetch greeting:", error);
        messages.value.push({ role: 'ai', content: "Hello! I am your AI Assistant (Greeting failed)." });
    } finally {
        isLoading.value = false;
    }
});

const sendMessage = async (userText) => {
    if (isLoading.value) return;

    // Add user message to UI
    messages.value.push({ role: 'user', content: userText });
    
    // Prepare history payload for API (all messages before the newly added one)
    const historyPayload = messages.value.slice(0, -1).map(msg => ({ role: msg.role, content: msg.content }));
    isLoading.value = true;

    try {
        const apiResponse = await chat(userText, historyPayload);
        messages.value.push({ role: 'ai', content: apiResponse.response || apiResponse });
    } catch (error) {
        console.error("Chat request failed:", error);
        messages.value.push({ role: 'ai', content: "Sorry, I encountered an error while trying to process your request." });
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
  <div class="ai-assistant-container">
    <ChatMessages :messages="messages" :is-loading="isLoading" />
    <ChatPrompt :is-loading="isLoading" @send-message="sendMessage" />
  </div>
</template>

<style scoped>
.ai-assistant-container {
  display: flex;
  flex-direction: column;
  height: 80vh;
  max-width: 800px;
  margin: 0 auto;
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  background-color: #fff;
  font-family: Arial, sans-serif;
}
</style>