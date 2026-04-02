<script setup>
import ItemTable from '../components/ItemTable.vue'
import { ref } from 'vue';
import axios from 'axios';

// --- LOGICA PENTRU ANALIZA DE IMAGINE (Partea ta) ---
const selectedFile = ref(null);
const imageTags = ref([]);
const isUploading = ref(false);

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
    alert("Eroare la procesarea imaginii. Ai pornit MediaService-ul tău local cu 'node server.js'?");
    console.error(error);
  } finally {
    isUploading.value = false;
  }
};
</script>

<template>
  <div class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <section>
      <h2>Items</h2>
      <ItemTable />
    </section>

    <section>
      <div style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
        <h2>📸 Analiză Imagine</h2>
        <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Încarcă o imagine pentru a extrage etichetele inteligente folosind Vision API.</p>
        
        <div style="margin-bottom: 15px;">
          <input type="file" @change="onFileChange" accept="image/*" />
          <button 
            @click="uploadImage" 
            :disabled="!selectedFile || isUploading" 
            style="margin-left: 10px; padding: 8px 15px; cursor: pointer; background-color: #2ecc71; color: white; border: none; border-radius: 4px; font-weight: bold;"
          >
            {{ isUploading ? 'Se analizează...' : 'Analizează Imaginea' }}
          </button>
        </div>
        
        <div v-if="imageTags.length">
          <h3 style="margin-bottom: 10px;">Etichete detectate:</h3>
          <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <span 
              v-for="tag in imageTags" 
              :key="tag" 
              style="background: #9b59b6; color: white; padding: 6px 12px; border-radius: 15px; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
            >
              {{ tag }}
            </span>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<style scoped>
.admin-dashboard {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  font-family: Arial, sans-serif;
}
</style>