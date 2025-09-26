<template>
  <div>
    <Head title="Create Order" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/orders">Orders</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store" class="flex flex-wrap -mb-8 -mr-6 p-8">
        
        <text-input 
          v-model="form.customer_name" 
          :error="form.errors.customer_name" 
          class="pb-8 pr-6 w-full lg:w-1/2" 
          label="Customer Name" 
        />

        <select-input 
          v-model="form.status" 
          :error="form.errors.status" 
          class="pb-8 pr-6 w-full lg:w-1/2" 
          label="Status"
        >
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="shipped">Shipped</option>
        </select-input>

         
        <div class="pb-8 pr-6 w-full lg:w-1/2">
          <label class="block mb-2 text-sm font-medium text-gray-700">Product</label>
          <select v-model="selectedProduct" class="form-select w-full">
            <option disabled value="">-- Select Product --</option>
            <option v-for="product in products" :key="product.id" :value="product.id">
              {{ product.name }} (existing: {{ product.quantity }})
            </option>
          </select>
        </div>

        <div class="pb-8 pr-6 w-full lg:w-1/4">
          <label class="block mb-2 text-sm font-medium text-gray-700">Quantity</label>
          <input 
            type="number" 
            v-model.number="selectedQuantity" 
            min="1" 
            class="form-input w-full"
          />
        </div>

      
        <div class="pb-8 pr-6 w-full lg:w-1/4 flex items-end">
          <button type="button" @click="addItem" class="btn-indigo">Add</button>
        </div>

      
        <div v-if="form.items.length" class="w-full mt-4">
          <h3 class="text-lg font-medium mb-2">Selected Products:</h3>
          <table class="w-full border">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-2 py-1 text-left">Name</th>
                <th class="px-2 py-1 text-center">Quantity</th>
                <th class="px-2 py-1 text-center">Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.items" :key="index">
                <td class="px-2 py-1">{{ item.name }}</td>
                <td class="px-2 py-1 text-center">{{ item.quantity }}</td>
                <td class="px-2 py-1 text-center">
                  <button type="button" @click="removeItem(index)" class="text-red-500">×</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

         
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100 w-full">
          <loading-button 
            :loading="form.processing" 
            class="btn-indigo" 
            type="submit"
          >
            Submit
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import SelectInput from '@/Shared/SelectInput.vue'

export default {
  props: {
    products: Array, 
  },
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    SelectInput
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        customer_name: '',
        status: 'pending',
        items: [], 
      }),
      selectedProduct: '',
      selectedQuantity: 1,
    }
  },
  methods: {
    addItem() {
      if (!this.selectedProduct || this.selectedQuantity < 1) return

      const product = this.products.find(p => p.id === this.selectedProduct)

      const existing = this.form.items.find(i => i.product_id === product.id)
      if (existing) {
        existing.quantity += this.selectedQuantity
      } else {
        this.form.items.push({
          product_id: product.id,
          name: product.name,
          quantity: this.selectedQuantity,
        })
      }

      this.selectedProduct = ''
      this.selectedQuantity = 1
    },
    removeItem(index) {
      this.form.items.splice(index, 1)
    },
    store() {
      this.form.post('/orders', {
        onSuccess: () => {
          this.form.reset()
        }
      })
    },
  },
}
</script>
