<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full animate-fade-in">
      <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl flex items-center justify-center mb-4 shadow-lg animate-bounce-in">
          <Users class="w-8 h-8 text-white" />
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
        <p class="text-gray-600">Sign in to your community account</p>
      </div>

      <div class="card p-8 animate-slide-up">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email address
            </label>
            <div class="relative">
              <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="input pl-10"
                placeholder="Enter your email"
              />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="input pl-10"
                placeholder="Enter your password"
              />
            </div>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="btn btn-primary btn-lg w-full"
          >
            <div v-if="authStore.loading" class="loading-spinner mr-2"></div>
            {{ authStore.loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <router-link to="/register" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
              Sign up here
            </router-link>
          </p>
        </div>
      </div>

      <!-- Demo Credentials -->
      <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200 animate-slide-up">
        <h3 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
          <Info class="w-4 h-4 mr-2" />
          Demo Credentials
        </h3>
        <div class="text-xs text-blue-700 space-y-1">
          <p><strong>Admin:</strong> admin@example.com / password</p>
          <p><strong>User:</strong> john@example.com / password</p>
          <p><strong>Pending:</strong> jane@example.com / password</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { Users, Mail, Lock, Info } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    await authStore.login(form)
    router.push('/dashboard')
  } catch (error) {
    console.error('Login error:', error)
  }
}
</script>
