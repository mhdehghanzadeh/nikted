<template>
  <div>
    <div class="mb-4">
      <Link v-if="$page.props.auth.user?.admin || $page.props.auth.user?.can.orders" class="group flex items-center py-3" href="/orders">
        <div :class="isUrl('orders') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Orders</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link v-if="$page.props.auth.user?.admin || $page.props.auth.user?.can.products" class="group flex items-center py-3" href="/products">
        <div :class="isUrl('products') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Products</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link v-if="$page.props.auth.user?.admin || $page.props.auth.user?.can.users" class="group flex items-center py-3" href="/users">
        <div :class="isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Users</div>
      </Link>
    </div>
    
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
