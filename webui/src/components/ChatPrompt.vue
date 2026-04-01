<script setup>
import { ref } from 'vue';

defineProps({
  isLoading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['sendMessage']);
const promptInput = ref('');

const handleSubmit = () => {
  const text = promptInput.value.trim();
  if (!text) return;
  emit('sendMessage', text);
  promptInput.value = '';
};
</script>

<template>
  <div class="chat-prompt">
    <form @submit.prevent="handleSubmit">
      <input
        type="text"
        v-model="promptInput"
        placeholder="Type your message here..."
        :disabled="isLoading"
      />
      <button type="submit" :disabled="isLoading || !promptInput.trim()">Send</button>
    </form>
  </div>
</template>

<style scoped>
.chat-prompt {
  padding: 1rem;
  background-color: #fff;
  border-top: 1px solid #ccc;
  font-family: Arial, sans-serif;
}
.chat-prompt form {
  display: flex;
  gap: 0.5rem;
  font-family: Arial, sans-serif;
}
.chat-prompt input {
  flex: 1;
  padding: 0.8rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
  font-family: Arial, sans-serif;
}
.chat-prompt button {
  padding: 0.8rem 1.5rem;
  background-color: #0d6efd;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s;
  font-family: Arial, sans-serif;
}
.chat-prompt button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
  font-family: Arial, sans-serif;
}
.chat-prompt button:hover:not(:disabled) {
  background-color: #0b5ed7;
  font-family: Arial, sans-serif;
}
</style>