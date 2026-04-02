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
          <div v-for="(entry, index) in chatHistory" :key="index" class="bot-message">
            <strong>{{ entry.role === 'user' ? 'Tu' : 'Bot' }}:</strong> {{ entry.content }}
          </div>
        </div>
        <div class="chat-input-area">
          <input v-model="chatMessage" placeholder="Întreabă-mă ceva..." class="input-field" @keyup.enter="sendMessage" />
          <button @click="sendMessage" class="btn secondary" :disabled="!chatMessage || isChatLoading">
            {{ isChatLoading ? 'Se generează...' : 'Trimite' }}
          </button>
        </div>
      </section>

      <section class="card">
        <h2>🔐 Login</h2>
        <input
          v-model="loginEmail"
          type="email"
          placeholder="Email"
          class="input-field"
        />
        <input
          v-model="loginPassword"
          type="password"
          placeholder="Parolă"
          class="input-field"
          @keyup.enter="loginUser"
        />
        <button @click="loginUser" class="btn primary" :disabled="isLoggingIn || !loginEmail || !loginPassword">
          {{ isLoggingIn ? 'Se autentifică...' : 'Login' }}
        </button>
        <p v-if="loginStatus" class="status-message">{{ loginStatus }}</p>
        <p v-if="authTokenPreview" class="token-preview">Token: {{ authTokenPreview }}</p>
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
const chatHistory = ref([{ role: 'assistant', content: chatResponse.value }]);
const isChatLoading = ref(false);

// Stări autentificare
const loginEmail = ref('');
const loginPassword = ref('');
const loginStatus = ref('');
const authTokenPreview = ref('');
const isLoggingIn = ref(false);

// Stări pentru Tine (Media)
const selectedFile = ref(null);
const imageTags = ref([]);
const isUploading = ref(false);

// Funcții API
const fetchItems = async () => {
  try {
    const response = await axios.get('/api/items/');
    const payload = Array.isArray(response.data)
      ? response.data
      : Array.isArray(response.data?.data)
        ? response.data.data
        : [];
    items.value = payload.map((item) => ({
      ...item,
      quantity: Number(item.quantity ?? 0),
      price: Number(item.price ?? 0),
    }));
  } catch (error) {
    alert("Eroare la conectarea cu PhpApi. Asigură-te că serverul colegului rulează!");
    console.error(error);
  }
};

const sendMessage = async () => {
  const message = chatMessage.value.trim();
  if (!message) return;

  const historyForApi = chatHistory.value
    .filter((entry) => entry.role === 'user' || entry.role === 'assistant')
    .map((entry) => ({
      role: entry.role,
      content: entry.content,
    }))
    .slice(-8);

  chatHistory.value.push({ role: 'user', content: message });
  chatMessage.value = '';
  isChatLoading.value = true;

  try {
    const response = await axios.post('/ai/chat', { message, history: historyForApi });
    const answer =
      typeof response?.data?.response === 'string' && response.data.response.trim().length
        ? response.data.response
        : 'Nu am primit un răspuns valid de la asistent.';

    chatResponse.value = answer;
    chatHistory.value.push({ role: 'assistant', content: answer });
  } catch (error) {
    const fallback = 'Asistentul nu a răspuns. Te rog încearcă din nou.';
    chatResponse.value = fallback;
    chatHistory.value.push({ role: 'assistant', content: fallback });
    alert("Eroare Chatbot. Asigură-te că serviciul Python/.NET rulează!");
    console.error(error);
  } finally {
    isChatLoading.value = false;
  }
};

const loginUser = async () => {
  if (!loginEmail.value || !loginPassword.value) {
    loginStatus.value = 'Completează email și parolă.';
    return;
  }

  isLoggingIn.value = true;
  loginStatus.value = '';
  authTokenPreview.value = '';

  try {
    const response = await axios.post('/api/auth/login', {
      email: loginEmail.value.trim(),
      plainPassword: loginPassword.value,
    });

    let token = response.data;
    if (typeof response.data === 'object' && response.data?.token) {
      token = response.data.token;
    }

    const tokenString = typeof token === 'string' ? token : JSON.stringify(token);
    localStorage.setItem('token', tokenString);
    authTokenPreview.value = `${tokenString.slice(0, 24)}...`;
    loginStatus.value = 'Autentificare reușită.';
  } catch (error) {
    const backendMessage = error?.response?.data;
    const message =
      typeof backendMessage === 'string'
        ? backendMessage
        : backendMessage?.detail || backendMessage?.message || 'Autentificare eșuată.';
    loginStatus.value = message;
  } finally {
    isLoggingIn.value = false;
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
    const response = await axios.post('/api/media/upload', formData);
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
.input-field + .input-field { margin-top: 10px; }
.file-input { margin-bottom: 15px; width: 100%; }
.tags { display: flex; flex-wrap: wrap; gap: 8px; }
.tag { background: #9b59b6; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9em; }
.empty-state { color: #7f8c8d; font-style: italic; }
.status-message { margin: 8px 0 0 0; color: #2c3e50; }
.token-preview { margin: 6px 0 0 0; color: #16a085; font-size: 0.85em; word-break: break-all; }
</style>