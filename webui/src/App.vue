<template>
  <div class="dashboard">
    <header class="header">
      <h1>Enrique's Shop </h1>
    </header>

    <div class="grid-container">
      
      <section class="card">
        <h2>📦 Inventar </h2>
        <button @click="fetchItems" class="btn primary">Încarcă Articole</button>
        
        <div v-if="items.length" class="item-list">
          <div v-for="item in items" :key="item.id" class="item-box">
            <h3>{{ item.name }}</h3>
            <p><strong>Cantitate:</strong> {{ item.quantity }} buc</p>
            <p><strong>Calitate:</strong> <span class="badge">{{ item.quality }}</span></p>
            <p class="price">{{ item.price.toFixed(2) }} RON</p>
          </div>
        </div>
        <p v-else class="empty-state">Niciun articol încărcat.</p>
      </section>

      <section class="card">
        <h2>💬 Asistent Virtual </h2>
        <div class="chat-window">
          <div v-if="chatResponse" class="bot-message">
            <strong>Bot:</strong> {{ chatResponse }}
          </div>
        </div>
        <div class="chat-input-area">
          <input v-model="chatMessage" placeholder="Întreabă-mă ceva..." class="input-field" @keyup.enter="sendMessage" />
          <button @click="sendMessage" class="btn secondary" :disabled="!chatMessage">Trimite</button>
        </div>
      </section>

      <section class="card">
        <h2>📸 Analiză Imagine </h2>
        <div class="upload-area">
          <input type="file" @change="onFileChange" accept="image/*" class="file-input" />
          <button @click="uploadImage" class="btn primary" :disabled="!selectedFile || isUploading">
            {{ isUploading ? 'Se analizează...' : 'Analizează Imaginea' }}
          </button>
        </div>
        
        <div v-if="imageTags.length" class="tags-container">
          <h3>Etichete detectate:</h3>
          <div class="tags">
            <span v-for="tag in imageTags" :key="tag" class="tag">{{ tag }}</span>
          </div>
        </div>
      </section>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// Stări pentru Teammate B (Items)
const items = ref([]);

// Stări pentru Teammate C (Chat)
const chatMessage = ref('');
const chatResponse = ref('Salut! Cu ce te pot ajuta?');

// Stări pentru Tine (Media)
const selectedFile = ref(null);
const imageTags = ref([]);
const isUploading = ref(false);

// Funcții API
const fetchItems = async () => {
  try {
    const response = await axios.get('/api/items');
    items.value = response.data;
  } catch (error) {
    alert("Eroare la conectarea cu PhpApi. Asigură-te că serverul colegului rulează!");
    console.error(error);
  }
};

const sendMessage = async () => {
  if (!chatMessage.value) return;
  try {
    const response = await axios.post('/api/chat', { message: chatMessage.value });
    chatResponse.value = response.data.reply;
    chatMessage.value = '';
  } catch (error) {
    alert("Eroare Chatbot. Asigură-te că serviciul Python/.NET rulează!");
    console.error(error);
  }
};

const onFileChange = (event) => {
  selectedFile.value = event.target.files[0];
};

const uploadImage = async () => {
  if (!selectedFile.value) return;
  isUploading.value = true;
  const formData = new FormData();
  formData.append('image', selectedFile.value);

  try {
    const response = await axios.post('http://localhost:8081/api/media/upload', formData);
    imageTags.value = response.data.tags;
  } catch (error) {
    alert("Eroare la procesarea imaginii. Ai pornit MediaService-ul tău local?");
    console.error(error);
  } finally {
    isUploading.value = false;
  }
};
</script>

<style scoped>
.dashboard { padding: 20px; max-width: 1200px; margin: 0 auto; }
.header { text-align: center; margin-bottom: 30px; color: #2c3e50; }
.grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.btn { padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; margin-bottom: 15px;}
.primary { background: #3498db; color: white; }
.primary:hover { background: #2980b9; }
.secondary { background: #2ecc71; color: white; margin-top: 10px;}
.primary:disabled, .secondary:disabled { background: #bdc3c7; cursor: not-allowed; }
.item-box { border-left: 4px solid #3498db; padding: 10px; margin-bottom: 10px; background: #f8f9fa; }
.item-box h3 { margin: 0 0 5px 0; font-size: 1.1em; }
.price { color: #27ae60; font-weight: bold; font-size: 1.2em; margin: 5px 0 0 0; }
.badge { background: #e67e22; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; }
.chat-window { min-height: 100px; background: #ecf0f1; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
.bot-message { background: white; padding: 10px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.input-field { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; }
.file-input { margin-bottom: 15px; width: 100%; }
.tags { display: flex; flex-wrap: wrap; gap: 8px; }
.tag { background: #9b59b6; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9em; }
.empty-state { color: #7f8c8d; font-style: italic; }
</style>