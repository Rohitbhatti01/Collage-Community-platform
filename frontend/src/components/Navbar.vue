<template>
  <nav class="bg-white shadow-lg fixed w-full top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/dashboard" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg flex items-center justify-center group-hover:shadow-lg transition-shadow">
              <Users class="w-5 h-5 text-white" />
            </div>
            <span class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors">Community</span>
          </router-link>
        </div>

        <div class="hidden md:flex items-center space-x-1">
          <router-link
            to="/dashboard"
            class="nav-link"
            :class="{ 'nav-link-active': $route.name === 'Dashboard' }"
          >
            <Home class="w-4 h-4" />
            <span>Dashboard</span>
          </router-link>

          <router-link
            to="/users"
            class="nav-link"
            :class="{ 'nav-link-active': $route.name === 'Users' }"
          >
            <Users class="w-4 h-4" />
            <span>Community</span>
          </router-link>

          <router-link
            to="/messages"
            class="nav-link"
            :class="{ 'nav-link-active': $route.name === 'Messages' }"
          >
            <MessageCircle class="w-4 h-4" />
            <span>Messages</span>
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="nav-link"
            :class="{ 'nav-link-active': $route.name === 'Admin' }"
          >
            <Shield class="w-4 h-4" />
            <span>Admin</span>
          </router-link>
        </div>

        <div class="flex items-center space-x-4">
          <!-- Mobile menu button -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors"
          >
            <Menu class="w-6 h-6" />
          </button>

          <!-- Profile dropdown -->
          <div class="relative">
            <button
              @click="profileDropdownOpen = !profileDropdownOpen"
              class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <!-- Profile image or initials -->
              <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 border border-gray-300">
                <img
                  v-if="authStore.user?.profile?.photo"
                  :src="`${apiBaseUrl}/storage/${authStore.user.profile.photo}`"
                  alt="Profile"
                  class="w-full h-full object-cover"
                  @error="handleImageError"
                />
                <div
                  v-else
                  class="w-full h-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-sm font-medium"
                >
                  {{ authStore.userInitials }}
                </div>
              </div>

              <div class="hidden md:block text-left">
                <div class="text-sm font-medium text-gray-900">{{ authStore.user?.name }}</div>
                <div class="text-xs text-gray-500">{{ authStore.user?.email }}</div>
              </div>
              <ChevronDown class="w-4 h-4 text-gray-400" />
            </button>

            <Transition name="fade">
              <div
                v-if="profileDropdownOpen"
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                @click="profileDropdownOpen = false"
              >
                <router-link
                  to="/profile"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                >
                  <User class="w-4 h-4 mr-3" />
                  Profile
                </router-link>
                <hr class="my-1" />
                <button
                  @click="logout"
                  class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                >
                  <LogOut class="w-4 h-4 mr-3" />
                  Logout
                </button>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition name="slide">
      <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <router-link
            to="/dashboard"
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': $route.name === 'Dashboard' }"
            @click="mobileMenuOpen = false"
          >
            <Home class="w-5 h-5" />
            Dashboard
          </router-link>

          <router-link
            to="/users"
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': $route.name === 'Users' }"
            @click="mobileMenuOpen = false"
          >
            <Users class="w-5 h-5" />
            Community
          </router-link>

          <router-link
            to="/messages"
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': $route.name === 'Messages' }"
            @click="mobileMenuOpen = false"
          >
            <MessageCircle class="w-5 h-5" />
            Messages
          </router-link>

          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': $route.name === 'Admin' }"
            @click="mobileMenuOpen = false"
          >
            <Shield class="w-5 h-5" />
            Admin
          </router-link>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  Home,
  Users,
  MessageCircle,
  Shield,
  User,
  LogOut,
  ChevronDown,
  Menu,
} from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const profileDropdownOpen = ref(false)
const mobileMenuOpen = ref(false)
const apiBaseUrl = 'http://127.0.0.1:8000' // or use import.meta.env.VITE_API_BASE_URL if env setup

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}

const handleImageError = () => {
  console.error('Failed to load navbar profile photo')
}

const closeDropdowns = (event) => {
  if (!event.target.closest('.relative')) {
    profileDropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns)
})
</script>
