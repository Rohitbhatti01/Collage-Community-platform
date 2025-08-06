<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <Navbar v-if="isAuthenticated" />
    <main :class="{ 'pt-16': isAuthenticated }">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from './stores/auth'
import Navbar from './components/Navbar.vue'

const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)

onMounted(() => {
  const token = authStore.token
  if (token) {
    authStore.fetchUser()
  }
})
</script>

<style>
.custom-toast {
  @apply rounded-lg shadow-lg border border-gray-200;
}

.custom-toast-body {
  @apply text-sm;
}

.custom-toast-container {
  @apply z-50;
}
</style>
