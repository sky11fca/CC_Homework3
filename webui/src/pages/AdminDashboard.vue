<script setup>
import ItemTable from '../components/ItemTable.vue'
</script>

<template>

  <div class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <section>
      <h2>Items</h2>
      <ItemTable />
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