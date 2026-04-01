<script setup lang="ts">
defineProps<{
  products: Item[];
}>();

defineEmits<{
  (e: 'add-to-cart', product: Item): void;
}>();
</script>

<template>
  <div class="item-list">
    <h2>Products</h2>
    <div class="grid">
      <div v-for="item in products" :key="item.id" class="card">
        <h3>{{ item.name }}</h3>
        <p><strong>Quality:</strong> {{ item.quality }}</p>
        <p><strong>Price:</strong> ${{ item.price.toFixed(2) }}</p>
        <p><strong>Available:</strong> {{ item.quantity }}</p>
        <button
            @click="$emit('add-to-cart', item)"
            :disabled="item.quantity === 0"
        >
          {{ item.quantity === 0 ? 'Out of Stock' : 'Add to Cart' }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.item-list {
  flex: 2;
  font-family: Arial, sans-serif;
}
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  font-family: Arial, sans-serif;
}
.card {
  border: 1px solid #ddd;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  font-family: Arial, sans-serif;
}
.card h3 {
  margin-top: 0;
  font-family: Arial, sans-serif;
}
button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  cursor: pointer;
  border-radius: 4px;
  font-weight: bold;
  font-family: Arial, sans-serif;
}
button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  font-family: Arial, sans-serif;
}
</style>