<script setup lang="ts">
import {ref, computed, onMounted} from 'vue';
import {getItems} from "@/services/items/getItems";
import {purchaseItem} from "@/services/items/purchaseItem";
import ItemList from "@/components/ItemList.vue";
import Cart from "@/components/Cart.vue";

// A specific type for items in the cart, extending Item with cartQuantity

export interface CartItem extends Item
{
  cartQuantity: number;
}

// Dummy product data
const products = ref<Item[]>([]);
const cart = ref<CartItem[]>([]);

onMounted(async() => {
  try{
    products.value = await getItems();
  } catch (err)
  {
    console.error("Error fetching products:", err)
  }
})

// Calculate total price dynamically
const cartTotal = computed(() => {
  return cart.value.reduce((total, item) => total + (item.price * item.cartQuantity), 0);
});

const addToCart = (product : Item) => {
  if (product.quantity > 0) {
    // Decrease available stock in the store list
    product.quantity--;

    // Check if item is already in cart to group them together
    const existingItem = cart.value.find(item => item.id === product.id);
    if (existingItem) {
      existingItem.cartQuantity++;
    } else {
      cart.value.push({ ...product, cartQuantity: 1 });
    }
  }
};

const confirmPayment = async () => {
  if (cart.value.length === 0) return;

  try {
    // Purchase all items in the cart
    await Promise.all(
      cart.value.map(item => purchaseItem(String(item.id), item.cartQuantity))
    );

    alert(`Payment of $${cartTotal.value.toFixed(2)} confirmed! Thank you for your purchase.`);

    // Empty out the cart after payment succeeds
    cart.value = [];
  } catch (error) {
    console.error("Error during checkout:", error);
    alert("There was an issue processing your payment. Please try again.");
  }
};
</script>

<template>
  <div class="shop-container">
    <!-- Item List -->
    <ItemList :products="products" @add-to-cart="addToCart" />

    <!-- Shopping Cart -->
    <Cart :cart="cart" :total="cartTotal" @confirm-payment="confirmPayment" />
  </div>
</template>

<style scoped>
.shop-container {
  display: flex;
  gap: 2rem;
  padding: 2rem;
  font-family: Arial, sans-serif;
}
</style>