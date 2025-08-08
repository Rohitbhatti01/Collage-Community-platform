<template>
  <div class="max-w-6xl mx-auto py-4 px-2 sm:px-4 h-[calc(100vh-2rem)] flex flex-col">
    <div class="bg-white rounded-lg shadow flex-1 flex flex-col sm:flex-row overflow-hidden border border-gray-200">
      
      <!-- Conversations List -->
      <div
        v-if="!selectedConversation || isDesktop"
        class="w-full sm:w-1/3 border-r border-gray-200 flex flex-col"
      >
        <div class="p-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold">Messages</h2>
        </div>
        <div class="overflow-y-auto flex-1">
          <div
            v-for="conversation in conversations"
            :key="conversation.id"
            @click="selectConversation(conversation)"
            class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition"
            :class="{ 'bg-blue-50': selectedConversation?.id === conversation.id }"
          >
            <div class="flex items-center">
              <div
                class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold"
              >
                {{ getOtherUser(conversation).name.charAt(0).toUpperCase() }}
              </div>
              <div class="ml-3 flex-1">
                <p class="font-semibold truncate">{{ getOtherUser(conversation).name }}</p>
                <p class="text-sm text-gray-600 truncate">{{ conversation.message }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Chat Area -->
      <div
        v-if="selectedConversation"
        class="flex-1 flex flex-col"
      >
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200 flex items-center space-x-2">
          <!-- Back button for mobile -->
          <button
            v-if="!isDesktop"
            @click="selectedConversation = null"
            class="p-2 rounded-full hover:bg-gray-100"
          >
            ⬅
          </button>
          <h3 class="font-semibold">{{ getOtherUser(selectedConversation).name }}</h3>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <div
            v-for="message in messages"
            :key="message.id"
            class="flex"
            :class="{ 'justify-end': message.sender_id === authStore.user?.id }"
          >
            <div
              class="max-w-xs sm:max-w-md px-4 py-2 rounded-lg"
              :class="{
                'bg-blue-600 text-white': message.sender_id === authStore.user?.id,
                'bg-gray-200 text-gray-900': message.sender_id !== authStore.user?.id
              }"
            >
              <p>{{ message.message }}</p>
              <p class="text-xs mt-1 opacity-75">
                {{ formatTime(message.created_at) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-200">
          <form @submit.prevent="sendMessage" class="flex space-x-2">
            <input
              v-model="newMessage"
              type="text"
              placeholder="Type a message..."
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
            <button
              type="submit"
              :disabled="!newMessage.trim()"
              class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              Send
            </button>
          </form>
        </div>
      </div>

      <!-- Placeholder for no conversation selected -->
      <div
        v-else-if="isDesktop"
        class="flex-1 flex items-center justify-center text-gray-500"
      >
        <p>Select a conversation to start messaging</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import api from '../api/axios'
import { useToast } from 'vue-toastification'

const route = useRoute()
const authStore = useAuthStore()
const toast = useToast()

const conversations = ref([])
const selectedConversation = ref(null)
const messages = ref([])
const newMessage = ref('')

// Detect screen size
const isDesktop = computed(() => window.innerWidth >= 640)

window.addEventListener('resize', () => {
  if (window.innerWidth >= 640) return
})

const fetchConversations = async () => {
  try {
    const response = await api.get('/messages/conversations')
    conversations.value = response.data.conversations
  } catch (error) {
    console.error('Fetch conversations error:', error)
  }
}

const selectConversation = async (conversation) => {
  selectedConversation.value = conversation
  const otherUser = getOtherUser(conversation)
  await fetchMessages(otherUser.id)
}

const fetchMessages = async (userId) => {
  try {
    const response = await api.get(`/messages/${userId}`)
    messages.value = response.data.messages
  } catch (error) {
    console.error('Fetch messages error:', error)
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim()) return
  
  try {
    const otherUser = getOtherUser(selectedConversation.value)
    const response = await api.post('/messages/send', {
      receiver_id: otherUser.id,
      message: newMessage.value
    })
    messages.value.push(response.data.data)
    newMessage.value = ''
  } catch (error) {
    toast.error('Failed to send message')
    console.error('Send message error:', error)
  }
}

const getOtherUser = (conversation) => {
  return conversation.sender_id === authStore.user?.id 
    ? conversation.receiver 
    : conversation.sender
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString()
}

onMounted(async () => {
  await fetchConversations()
  
  const userId = route.query.user
  if (userId) {
    try {
      const response = await api.get(`/profile/${userId}`)
      const user = response.data.user
      selectedConversation.value = {
        id: `new-${userId}`,
        sender: user,
        receiver: authStore.user,
        message: 'Start a conversation...'
      }
      messages.value = []
    } catch (error) {
      console.error('Fetch user error:', error)
    }
  }
})
</script>

<style scoped>
/* Smooth mobile transition */
@media (max-width: 639px) {
  .conversations-list {
    width: 100%;
  }
}
</style>
