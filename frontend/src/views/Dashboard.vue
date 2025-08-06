<template>
  <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8 animate-fade-in">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ authStore.user?.name?.split(' ')[0] }}! 👋
          </h1>
          <p class="text-gray-600 mt-1">Here's what's happening in your community today</p>
        </div>
        <div class="hidden md:flex items-center space-x-4">
          <div class="text-right">
            <p class="text-sm text-gray-500">{{ currentDate }}</p>
            <p class="text-xs text-gray-400">{{ currentTime }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-slide-up">
      <div class="card p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <Users class="w-6 h-6 text-blue-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalUsers }}</p>
            <p class="text-sm text-gray-600">Community Members</p>
          </div>
        </div>
      </div>
      
      <div class="card p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <FileText class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-gray-900">{{ postsStore.posts.length }}</p>
            <p class="text-sm text-gray-600">Recent Posts</p>
          </div>
        </div>
      </div>
      
      <div class="card p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <MessageCircle class="w-6 h-6 text-purple-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalMessages }}</p>
            <p class="text-sm text-gray-600">Messages Sent</p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Create Post -->
      <div class="lg:col-span-2">
        <div class="card p-6 mb-6 animate-slide-up">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-sm font-medium shadow-sm">
              {{ authStore.userInitials }}
            </div>
            <div class="ml-3">
              <p class="font-medium text-gray-900">{{ authStore.user?.name }}</p>
              <p class="text-sm text-gray-500">Share something with your community</p>
            </div>
          </div>

          <form @submit.prevent="createPost" class="space-y-4">
            <div>
              <input
                v-model="newPost.title"
                type="text"
                placeholder="What's the title of your post?"
                class="input w-full"
                required
              />
            </div>
            
            <div>
              <textarea
                v-model="newPost.content"
                placeholder="Share your thoughts, ask a question, or post a job opportunity..."
                rows="3"
                class="textarea w-full"
                required
              ></textarea>
            </div>
            
            <div class="flex items-center justify-between">
              <select v-model="newPost.type" class="input w-40">
                <option value="general">💬 General</option>
                <option value="job">💼 Job Info</option>
                <option value="guidance">🎯 Guidance</option>
                <option value="question">❓ Question</option>
              </select>
              
              <button
                type="submit"
                :disabled="postsStore.loading || !newPost.title.trim() || !newPost.content.trim()"
                class="btn btn-primary btn-md"
              >
                <div v-if="postsStore.loading" class="loading-spinner mr-2"></div>
                <Send class="w-4 h-4 mr-2" />
                {{ postsStore.loading ? 'Posting...' : 'Post' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Posts Feed -->
        <div class="space-y-6">
          <div
            v-for="post in postsStore.posts"
            :key="post.id"
            class="card p-6 hover:shadow-lg transition-all duration-300 animate-fade-in"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center text-white text-sm font-medium shadow-sm">
                  {{ getInitials(post.user.name) }}
                </div>
                <div class="ml-3">
                  <div class="flex items-center space-x-2">
                    <p class="font-semibold text-gray-900">{{ post.user.name }}</p>
                    <span class="badge" :class="getTypeClass(post.type)">
                      {{ getTypeIcon(post.type) }} {{ post.type }}
                    </span>
                  </div>
                  <p class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</p>
                </div>
              </div>
              
              <div v-if="post.user.id === authStore.user?.id || authStore.isAdmin" class="relative">
                <button
                  @click="togglePostMenu(post.id)"
                  class="p-1 rounded-full hover:bg-gray-100 transition-colors"
                >
                  <MoreVertical class="w-4 h-4 text-gray-400" />
                </button>
                
                <div
                  v-if="activePostMenu === post.id"
                  class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
                >
                  <button
                    @click="deletePost(post.id)"
                    class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                  >
                    <Trash2 class="w-4 h-4 mr-2" />
                    Delete
                  </button>
                </div>
              </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ post.title }}</h3>
            <p class="text-gray-700 mb-4 leading-relaxed">{{ post.content }}</p>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
              <div class="flex items-center space-x-4">
                <button class="flex items-center space-x-1 text-gray-500 hover:text-red-500 transition-colors">
                  <Heart class="w-4 h-4" />
                  <span class="text-sm">{{ post.likes_count }}</span>
                </button>
                <button class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors">
                  <MessageCircle class="w-4 h-4" />
                  <span class="text-sm">{{ post.comments_count }}</span>
                </button>
              </div>
              
              <router-link
                :to="`/messages?user=${post.user.id}`"
                class="btn btn-ghost btn-sm"
              >
                <Send class="w-4 h-4 mr-1" />
                Message
              </router-link>
            </div>
          </div>
        </div>

        <LoadingSpinner v-if="postsStore.loading" text="Loading posts..." />
        
        <div v-if="!postsStore.loading && postsStore.posts.length === 0" class="text-center py-12 animate-fade-in">
          <FileText class="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
          <p class="text-gray-500">Be the first to share something with the community!</p>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6 animate-slide-up">
        <!-- Quick Actions -->
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
          <div class="space-y-3">
            <router-link to="/users" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
              <Users class="w-5 h-5 text-primary-600 mr-3 group-hover:scale-110 transition-transform" />
              <div>
                <p class="font-medium text-gray-900">Browse Community</p>
                <p class="text-sm text-gray-500">Connect with members</p>
              </div>
            </router-link>
            
            <router-link to="/messages" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
              <MessageCircle class="w-5 h-5 text-green-600 mr-3 group-hover:scale-110 transition-transform" />
              <div>
                <p class="font-medium text-gray-900">Messages</p>
                <p class="text-sm text-gray-500">Chat with peers</p>
              </div>
            </router-link>
            
            <router-link to="/profile" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
              <User class="w-5 h-5 text-purple-600 mr-3 group-hover:scale-110 transition-transform" />
              <div>
                <p class="font-medium text-gray-900">Update Profile</p>
                <p class="text-sm text-gray-500">Keep info current</p>
              </div>
            </router-link>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
          <div class="space-y-3">
            <div class="flex items-center">
              <div class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse-slow"></div>
              <div class="text-sm">
                <p class="text-gray-900">New member joined</p>
                <p class="text-gray-500">2 hours ago</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse-slow"></div>
              <div class="text-sm">
                <p class="text-gray-900">Job posted in CS</p>
                <p class="text-gray-500">4 hours ago</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-2 h-2 bg-purple-500 rounded-full mr-3 animate-pulse-slow"></div>
              <div class="text-sm">
                <p class="text-gray-900">New guidance shared</p>
                <p class="text-gray-500">1 day ago</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import { usePostsStore } from '../stores/posts'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { 
  Users, 
  FileText, 
  MessageCircle, 
  Send, 
  Heart, 
  MoreVertical, 
  Trash2,
  User
} from 'lucide-vue-next'

const authStore = useAuthStore()
const postsStore = usePostsStore()

const newPost = reactive({
  title: '',
  content: '',
  type: 'general'
})

const activePostMenu = ref(null)
const stats = ref({
  totalUsers: 150,
  totalMessages: 1250
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
})

const currentTime = computed(() => {
  return new Date().toLocaleTimeString('en-US', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
})

const createPost = async () => {
  try {
    await postsStore.createPost(newPost)
    newPost.title = ''
    newPost.content = ''
    newPost.type = 'general'
  } catch (error) {
    console.error('Create post error:', error)
  }
}

const deletePost = async (postId) => {
  if (confirm('Are you sure you want to delete this post?')) {
    try {
      await postsStore.deletePost(postId)
      activePostMenu.value = null
    } catch (error) {
      console.error('Delete post error:', error)
    }
  }
}

const togglePostMenu = (postId) => {
  activePostMenu.value = activePostMenu.value === postId ? null : postId
}

const getInitials = (name) => {
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now - date) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Just now'
  if (diffInHours < 24) return `${diffInHours}h ago`
  if (diffInHours < 48) return 'Yesterday'
  return date.toLocaleDateString()
}

const getTypeClass = (type) => {
  const classes = {
    job: 'badge-success',
    guidance: 'badge-primary',
    question: 'badge-warning',
    general: 'badge-secondary'
  }
  return classes[type] || classes.general
}

const getTypeIcon = (type) => {
  const icons = {
    job: '💼',
    guidance: '🎯',
    question: '❓',
    general: '💬'
  }
  return icons[type] || icons.general
}

onMounted(async () => {
  await postsStore.fetchPosts()
})
</script>
