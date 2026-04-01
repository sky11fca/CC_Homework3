<script setup lang="ts">
import type { CartItem } from '@/pages/ShopPage.vue';

defineProps<{
  cart: CartItem[];
  total: number;
}>();

defineEmits<{
  (e: 'confirm-payment'): void;
}>();
</script>

<template>
  <div class="cart">
    <h2>Shopping Cart</h2>
    <div v-if="cart.length === 0" class="empty-cart">
      Your cart is empty.
    </div>
    <div v-else>
      <ul>
        <li v-for="cartItem in cart" :key="cartItem.id">
          {{ cartItem.name }} (x{{ cartItem.cartQuantity }}) - ${{ (cartItem.price * cartItem.cartQuantity).toFixed(2) }}
        </li>
      </ul>
      <div class="cart-total">
        <strong>Total: ${{ total.toFixed(2) }}</strong>
      </div>
      <button class="pay-button" @click="$emit('confirm-payment')">Confirm Payment</button>
    </div>
  </div>
</template>

<style scoped>
.cart {
  flex: 1;
  border: 1px solid #ddd;
  padding: 1rem;
  border-radius: 8px;
  background-color: #f9f9f9;
  height: fit-content;
  font-family: Arial, sans-serif;
}
.pay-button {
  background-color: #008CBA;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  cursor: pointer;
  border-radius: 4px;
  font-weight: bold;
  width: 100%;
  margin-top: 1rem;
  font-family: Arial, sans-serif;
}
.cart-total {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #ddd;
  font-family: Arial, sans-serif;
}
</style>