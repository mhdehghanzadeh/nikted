<template>
  <div>
    <Head :title="`${form.name} ${form.SKU}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/products">Products</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.name }}  
      </h1>
      <img v-if="product.photo" class="block ml-4 w-8 h-8 rounded-full" :src="product.photo" />
    </div>
    <trashed-message v-if="product.deleted_at" class="mb-6" @restore="restore"> This product has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.SKU" :error="form.errors.SKU" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.quantity" :error="form.errors.quantity" class="pb-8 pr-6 w-full lg:w-1/2" label="quantity" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!product.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete product</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update product</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    product: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.product.name,
        SKU: this.product.SKU,
        quantity: this.product.quantity,
       
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/products/${this.product.id}`, {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this product?')) {
        this.$inertia.delete(`/products/${this.product.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this product?')) {
        this.$inertia.put(`/products/${this.product.id}/restore`)
      }
    },
  },
}
</script>
