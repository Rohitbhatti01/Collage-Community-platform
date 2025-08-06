<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/dashboard" class="text-xl font-semibold">← Back to Dashboard</router-link>
          </div>
          <div class="flex items-center">
            <h1 class="text-xl font-semibold">Posts</h1>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <!-- Create Post Form -->
        <div class="bg-white shadow rounded-lg mb-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Create New Post</h2>
          </div>
          <form @submit.prevent="createPost" class="p-6 space-y-4">
            <div>
              <input
                v-model="newPost.title"
                type="text"
                placeholder="Post title"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
              />
            </div>
            <div>
              <select
                v-model="newPost.type"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="general">General</option>
                <option value="job">Job Info</option>
                <option value="guidance">Guidance</option>
                <option value="question">Question</option>
              </select>
            </div>
            <div>
              <textarea
                v-model="newPost.content"
                placeholder="What's on your mind?"
                required
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
              ></textarea>
            </div>
            <div>
              <button
                type="submit"
                :disabled="loading"
                class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 disabled:opacity-50"
              >
                {{ loading ? 'Posting...' : 'Post' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Posts List -->
        <div class="space-y-6">
          <div v-for="post in posts" :key="post.id" class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ post.user.name.charAt(0) }}
                  </div>
                  <div class="ml-3">
                    <div class="font-medium">{{ post.user.name }}</div>
                    <div class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</div>
                  </div>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getTypeClass(post.type)">
                  {{ post.type }}
                </span>
              </div>
              <h3 class="text-lg font-semibold mb-2">{{ post.title }}</h3>
              <p class="text-gray-700 mb-4">{{ post.content }}</p>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <button class="text-gray-500 hover:text-primary-600">
                    👍 {{ post.likes_count }}
                  </button>
                  <router-link :to="`/messages?user=${post.user.id}`" class="text-gray-500 hover:text-primary-600">
                    💬 Message
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from '../utils/axios'

export default {
  name: 'Posts',
  setup() {
    const posts = ref([])
    const loading = ref(false)
    const newPost = ref({
      title: '',
      content: '',
      type: 'general'
    })

    const fetchPosts = async () => {
      try {
        const response = await axios.get('/api/posts')
        if (response.data.success) {
          posts.value = response.data.posts.data
        }
      } catch (error) {
        console.error('Error fetching posts:', error)
      }
    }

    const createPost = async () => {
      loading.value = true
      try {
        const response = await axios.post('/api/posts', newPost.value)
        if (response.data.success) {
          posts.value.unshift(response.data.post)
          newPost.value = { title: '', content: '', type: 'general' }
        }
      } catch (error) {
        console.error('Error creating post:', error)
      } finally {
        loading.value = false
      }
    }

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString()
    }

    const getTypeClass = (type) => {
      const classes = {
        job: 'bg-green-100 text-green-800',
        guidance: 'bg-blue-100 text-blue-800',
        question: 'bg-yellow-100 text-yellow-800',
        general: 'bg-gray-100 text-gray-800'
      }
      return classes[type] || classes.general
    }

    onMounted(() => {
      fetchPosts()
    })

    return {
      posts,
      loading,
      newPost,
      createPost,
      formatDate,
      getTypeClass
    }
  }
}
</script>
