<script setup>
defineProps({
  messages: {
    type: Array,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  }
});
</script>

<template>
  <div class="chat-messages">
    <div
      v-for="(msg, index) in messages"
      :key="index"
      :class="['message', msg.role === 'ai' ? 'ai-message' : 'user-message']"
    >
      <span class="message-role">{{ msg.role === 'ai' ? 'AI' : 'You' }}:</span>
      <span class="message-text">{{ msg.content }}</span>
    </div>
    
    <div v-if="isLoading" class="message ai-message loading">
      <span class="message-role">AI:</span>
      <span class="message-text">Thinking...</span>
    </div>
  </div>
</template>

<style scoped>
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  background-color: #f9f9f9;
  font-family: Arial, sans-serif;
}
.message {
  padding: 0.8rem 1rem;
  border-radius: 8px;
  max-width: 80%;
  word-wrap: break-word;
  font-family: Arial, sans-serif;
}
.user-message {
  align-self: flex-end;
  background-color: #d1e7dd;
  color: #0f5132;
  font-family: Arial, sans-serif;
}
.ai-message {
  align-self: flex-start;
  background-color: #e2e3e5;
  color: #41464b;
  font-family: Arial, sans-serif;
}
.message-role {
  font-weight: bold;
  margin-right: 0.5rem;
  font-family: Arial, sans-serif;
}
.loading {
  font-style: italic;
  opacity: 0.7;
  font-family: Arial, sans-serif;
}
</style>