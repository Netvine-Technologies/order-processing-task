<template>
  <div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-2xl mx-auto">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Order Manager</h1>

      <form
        @submit.prevent="addProduct"
        class="mb-6 bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 border border-gray-200"
      >
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Add a Product</h2>

        <div class="mb-4">
          <label
            class="block text-gray-600 text-sm font-medium mb-1"
            for="productType"
          >
            Product Type
          </label>
          <select
            v-model="productType"
            id="productType"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
          >
            <option value="digital">Digital</option>
            <option value="physical">Physical</option>
            <option value="subscription">Subscription</option>
          </select>
        </div>

        <div class="mb-4">
          <label
            class="block text-gray-600 text-sm font-medium mb-1"
            for="productName"
          >
            Product Name
          </label>
          <input
            v-model="productName"
            id="productName"
            type="text"
            placeholder="e.g. Laptop, E-Book, Streaming Service"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
            required
          />
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label
              class="block text-gray-600 text-sm font-medium mb-1"
              for="productPrice"
            >
              Price ($)
            </label>
            <input
              v-model="productPrice"
              id="productPrice"
              type="number"
              step="0.01"
              placeholder="0.00"
              class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
              required
            />
          </div>
          <div>
            <label
              class="block text-gray-600 text-sm font-medium mb-1"
              for="productQuantity"
            >
              Quantity
            </label>
            <input
              v-model="productQuantity"
              id="productQuantity"
              type="number"
              placeholder="1"
              class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
              required
            />
          </div>
        </div>

        <div v-if="productType === 'digital'" class="mb-4">
          <label
            class="block text-gray-600 text-sm font-medium mb-1"
            for="downloadLink"
          >
            Download Link
          </label>
          <input
            v-model="downloadLink"
            id="downloadLink"
            type="url"
            placeholder="https://..."
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
          />
        </div>

        <div v-if="productType === 'physical'" class="mb-4">
          <label
            class="block text-gray-600 text-sm font-medium mb-1"
            for="weight"
          >
            Weight (kg)
          </label>
          <input
            v-model="weight"
            id="weight"
            type="number"
            step="0.01"
            placeholder="0.00"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
          />
        </div>

        <div v-if="productType === 'subscription'" class="mb-4">
          <label
            class="block text-gray-600 text-sm font-medium mb-1"
            for="subscriptionDuration"
          >
            Duration (months)
          </label>
          <input
            v-model="subscriptionDuration"
            id="subscriptionDuration"
            type="number"
            placeholder="12"
            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition"
        >
          Add Product
        </button>
      </form>

      <div
        v-if="errorMessage"
        class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6"
        role="alert"
      >
        <span>{{ errorMessage }}</span>
      </div>

      <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-8 pt-6 pb-4">
          <h2 class="text-lg font-semibold text-gray-700 mb-4">Current Order</h2>
        </div>

        <ul v-if="order.products.length" class="divide-y divide-gray-100">
          <li
            v-for="(product, index) in order.products"
            :key="`${product.name}-${index}`"
            class="px-8 py-3 hover:bg-gray-50 transition"
          >
            <div class="flex items-center justify-between">
              <span class="font-medium text-gray-800">{{ product.name }}</span>
              <div class="flex items-center gap-3">
                <span class="font-medium text-gray-800">${{ product.calculatePrice().toFixed(2) }}</span>
                <button
                  @click.prevent="order.removeProduct(index)"
                  class="text-red-400 hover:text-red-600 text-sm font-medium transition"
                >
                  Remove
                </button>
              </div>
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
              ${{ product.price.toFixed(2) }} &times; {{ product.quantity }}
              <span v-if="product.weight !== undefined"> &times; {{ product.weight }}kg</span>
              <span v-if="product.subscriptionDuration !== undefined"> &times; {{ product.subscriptionDuration }} mo</span>
            </div>
          </li>
        </ul>
        <p v-else class="px-8 py-6 text-gray-400 text-center">No products added yet.</p>

        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
          <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total</span>
          <span class="text-xl font-bold text-gray-800">${{ orderTotal }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';

import { Order, ProductFactory } from './orderClasses';

const order = reactive(new Order());
const productType = ref('digital');
const productName = ref('');
const productPrice = ref('');
const productQuantity = ref('');
const downloadLink = ref('');
const weight = ref('');
const subscriptionDuration = ref('');
const errorMessage = ref('');

const resetForm = () => {
  productName.value = '';
  productPrice.value = '';
  productQuantity.value = '';
  downloadLink.value = '';
  weight.value = '';
  subscriptionDuration.value = '';
};

const addProduct = () => {
  errorMessage.value = '';

  const name = productName.value.trim();
  const price = Number(productPrice.value);
  const quantity = Number(productQuantity.value);

  if (!name || Number.isNaN(price) || Number.isNaN(quantity)) {
    errorMessage.value = 'Please enter valid product details.';
    return;
  }

  try {
    const newProduct = ProductFactory.create(productType.value, {
      name,
      price,
      quantity,
      downloadLink: downloadLink.value,
      weight: Number(weight.value),
      subscriptionDuration: Number(subscriptionDuration.value),
    });

    order.addProduct(newProduct);
    resetForm();
  } catch (e) {
    errorMessage.value = e.message;
  }
};


const orderTotal = computed(() => order.calculateTotal().toFixed(2));
  
</script>
