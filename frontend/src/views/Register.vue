<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full animate-fade-in">
      <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl flex items-center justify-center mb-4 shadow-lg animate-bounce-in">
          <UserPlus class="w-8 h-8 text-white" />
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Join our community</h2>
        <p class="text-gray-600">Create your account to get started</p>
      </div>

      <div class="card p-8 animate-slide-up">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Full Name
            </label>
            <div class="relative">
              <User class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="input pl-10"
                placeholder="Enter your full name"
              />
            </div>
          </div>

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
            <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">
              College ID
            </label>
            <div class="relative">
              <GraduationCap class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="college_id"
                v-model="form.college_id"
                type="text"
                required
                class="input pl-10"
                placeholder="Enter your college ID"
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
                placeholder="Create a password"
              />
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
              Confirm Password
            </label>
            <div class="relative">
              <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                class="input pl-10"
                placeholder="Confirm your password"
              />
            </div>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="btn btn-primary btn-lg w-full"
          >
            <div v-if="authStore.loading" class="loading-spinner mr-2"></div>
            {{ authStore.loading ? 'Creating account...' : 'Create account' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
              Sign in here
            </router-link>
          </p>
        </div>
      </div>

      <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200 animate-slide-up">
        <div class="flex items-start">
          <AlertCircle class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" />
          <div class="text-sm text-yellow-800">
            <p class="font-medium mb-1">Account Approval Required</p>
            <p>Your account will need admin approval before you can log in. You'll be notified once approved.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { UserPlus, User, Mail, GraduationCap, Lock, AlertCircle } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  college_id: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  try {
    await authStore.register(form)
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (error) {
    console.error('Registration error:', error)
  }
}
</script>
