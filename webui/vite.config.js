import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    port: 3000, // Forțăm Vue să pornească pe portul 3000
    proxy: {
      // Redirecționăm automat cererile către microservicii pentru a evita erorile CORS
      '/api/media': { target: 'http://localhost:8080', changeOrigin: true }, // MediaService-ul tău
      '/api/items': { target: 'http://localhost:8000', changeOrigin: true }, // PhpApi (Colegul B)
      '/api/chat': { target: 'http://localhost:5000', changeOrigin: true },  // Chatbot (Colegul C)
      '/api/auth': { target: 'http://localhost:5000', changeOrigin: true }   // Auth (Colegul C)
    }
  }
})