<template>
  <div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Community Members</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="user in users"
        :key="user.id"
        class="bg-white rounded-lg shadow p-6"
      >
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
            {{ user.name.charAt(0).toUpperCase() }}
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold">{{ user.name }}</h3>
            <p class="text-gray-600">{{ user.email }}</p>
          </div>
        </div>
        
        <div v-if="user.profile" class="space-y-2 mb-4">
          <div v-if="user.profile.course">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Course:</span> {{ user.profile.course }}
            </p>
          </div>
          <div v-if="user.profile.batch">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Batch:</span> {{ user.profile.batch }}
            </p>
          </div>
          <div v-if="user.profile.current_status">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Status:</span> {{ user.profile.current_status }}
            </p>
          </div>
          <div v-if="user.profile.skills?.length">
            <div class="flex flex-wrap gap-1 mt-2">
              <span
                v-for="skill in user.profile.skills.slice(0, 3)"
                :key="skill"
                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs"
              >
                {{ skill }}
              </span>
              <span
                v-if="user.profile.skills.length > 3"
                class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs"
              >
                +{{ user.profile.skills.length - 3 }}
              </span>
            </div>
          </div>
        </div>
        
        <div class="flex space-x-2">
          <router-link
            :to="`/profile/${user.id}`"
            class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-md hover:bg-blue-700 text-sm"
          >
            View Profile
          </router-link>
          <router-link
            :to="`/messages?user=${user.id}`"
            class="flex-1 bg-gray-600 text-white text-center px-3 py-2 rounded-md hover:bg-gray-700 text-sm"
          >
            Message
          </router-link>
        </div>
      </div>
    </div>
    
    <div v-if="loading" class="text-center py-8">
      <p>Loading users...</p>
    </div>
    
    <div v-if="!loading && users.length === 0" class="text-center py-8 text-gray-500">
      <p>No users found.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const users = ref([])
const loading = ref(false)

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await api.get('/users')
    users.value = response.data.users
  } catch (error) {
    toast.error('Failed to fetch users')
    console.error('Fetch users error:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchUsers()
})
</script>
