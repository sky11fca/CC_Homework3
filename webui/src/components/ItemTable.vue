<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getItems } from '@/services/items/getItems'
import {markAsRotten} from "@/services/items/markAsRotten";
import {clearRotten} from "@/services/items/clearRotten";
import {deleteItem} from "@/services/items/deleteItem";
import {addItem} from "@/services/items/addItem";
import {updateItem} from "@/services/items/updateItem";

const items = ref([])
const loading = ref(true)
const error = ref(null)

const showAddForm = ref(false)
const newItem = ref({ name: '', quantity: 0, quality: '', price: 0 })

const editingItemId = ref<string | null>(null)
const editForm = ref({ id: '', name: '', quantity: 0, quality: '', price: 0 })

interface InputItem{
  name: string
  quantity: number
  quality: number
  price: number
}

onMounted(async () => {
  try {
    items.value = await getItems()
  } catch (err) {
    console.error('Error fetching items:', err)
    error.value = 'Failed to load items. Please try again later.'
  } finally {
    loading.value = false
  }
})

async function clearBad()
{
  try{
    await clearRotten();
    alert("Cleared Bad Items");
    items.value = await getItems()
  } catch (e){
    console.error(e);
    alert("Could not clear rotten items")
  }
}

function toggleAddForm() {
  showAddForm.value = !showAddForm.value;
  newItem.value = { name: '', quantity: 0, quality: '', price: 0 };
}

async function submitAdd() {
  try {
    await addItem(newItem.value as any);
    alert("Item added successfully");
    items.value = await getItems();
    showAddForm.value = false;
  } catch (e) {
    console.error(e);
    alert("Could not add item");
  }
}

async function delItem(id: string)
{
  try
  {
    await deleteItem(id)
    alert("Deleted Item")
    items.value = await getItems()
  }
  catch(e)
  {
    console.error(e)
    alert("Could not delete item")
  }
}

async function markRotten(id)
{
  try{
    await markAsRotten(id);
    alert("Marked as Rotten")
    items.value = await getItems();
  } catch(e){
    console.error(e);
    alert("Could not mark as rotten")
  }

}

function startUpdate(item: any) {
  editingItemId.value = item.id;
  editForm.value = { ...item };
}

function cancelUpdate() {
  editingItemId.value = null;
}

async function submitUpdate() {
  try {
    await updateItem(editForm.value as any);
    alert("Item updated successfully");
    items.value = await getItems();
    editingItemId.value = null;
  } catch (e) {
    console.error(e);
    alert("Could not update item");
  }
}

</script>

<template>
  <div>
    <p v-if="loading">Loading items...</p>
    <p v-else-if="error" class="error">{{ error }}</p>

    <table v-else-if="items.length > 0">
      <thead>
      <tr>
        <!-- Update these headers based on your actual Item type -->
        <th>ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Quality</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(item, index) in items" :key="index">
        <td>{{ item.id || index }}</td>
        
        <td v-if="editingItemId === item.id"><input v-model="editForm.name" /></td>
        <td v-else>{{ item.name || 'N/A' }}</td>
        
        <td v-if="editingItemId === item.id"><input type="number" v-model.number="editForm.quantity" /></td>
        <td v-else>{{ item.quantity || 'N/A'}}</td>
        
        <td v-if="editingItemId === item.id"><input v-model="editForm.quality" /></td>
        <td v-else>{{ item.quality || 'N/A'}}</td>
        
        <td v-if="editingItemId === item.id"><input type="number" step="0.01" v-model.number="editForm.price" /></td>
        <td v-else>{{ item.price || 'N/A'}}</td>
        
        <td>
          <template v-if="editingItemId === item.id">
            <button @click="submitUpdate">Save</button>
            <button @click="cancelUpdate">Cancel</button>
          </template>
          <template v-else>
            <button @click="markRotten(item.id)">Mark as Rotten</button>
            <button @click="delItem(item.id)">Delete</button>
            <button @click="startUpdate(item)">Update</button>
          </template>
        </td>
      </tr>
      </tbody>
    </table>
    <p v-else>No items found.</p>
    
    <div class="actions">
      <button @click="clearBad">Clear Rotten Items</button>
      <button v-if="!showAddForm" @click="toggleAddForm">Add Item</button>
    </div>

    <div v-if="showAddForm" class="add-form">
      <h3>Add New Item</h3>
      <div class="form-group">
        <label>Name:</label>
        <input v-model="newItem.name" />
      </div>
      <div class="form-group">
        <label>Quantity:</label>
        <input type="number" v-model.number="newItem.quantity" />
      </div>
      <div class="form-group">
        <label>Quality:</label>
        <input v-model="newItem.quality" />
      </div>
      <div class="form-group">
        <label>Price:</label>
        <input type="number" step="0.01" v-model.number="newItem.price" />
      </div>
      <div class="actions">
        <button @click="submitAdd">Submit</button>
        <button @click="toggleAddForm">Cancel</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
  font-family: Arial, sans-serif;
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  font-family: Arial, sans-serif;
}
th {
  background-color: #f2f2f2;
  font-family: Arial, sans-serif;
}
.error {
  color: red;
  font-family: Arial, sans-serif;
}
.actions {
  margin-top: 15px;
  display: flex;
  gap: 10px;
  font-family: Arial, sans-serif;
}
.add-form {
  margin-top: 20px;
  padding: 15px;
  border: 1px solid #ddd;
  background-color: #f9f9f9;
  max-width: 400px;
  font-family: Arial, sans-serif;
}
.form-group {
  margin-bottom: 10px;
  display: flex;
  flex-direction: column;
  font-family: Arial, sans-serif;
}
.form-group label {
  font-weight: bold;
  margin-bottom: 5px;
  font-family: Arial, sans-serif;
}

button {
  padding: 6px 12px;
  border: 1px solid transparent;
  border-radius: 4px;
  background-color: #007bff;
  color: #fff;
  cursor: pointer;
  font-family: Arial, sans-serif;
  transition: background-color 0.2s ease-in-out;
}
button:hover {
  background-color: #0056b3;
}
</style>