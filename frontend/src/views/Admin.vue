<template>
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
    <div class="mb-10">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Admin Dashboard</h1>
      <p class="mt-2 text-base sm:text-lg text-gray-500 font-medium">Effortlessly manage users and monitor platform activity</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10">
      <!-- Total Users -->
      <div class="card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border border-gray-100">
        <div class="flex items-center">
          <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-50 rounded-full flex items-center justify-center">
            <Users class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" />
          </div>
          <div class="ml-4 sm:ml-5">
            <p class="text-xl sm:text-2xl font-bold text-blue-900">{{ stats.total_users }}</p>
            <p class="text-xs sm:text-sm text-gray-500 font-medium">Total Users</p>
          </div>
        </div>
        <div class="border-t border-gray-100 mt-3 sm:mt-4 mb-2 sm:mb-3"></div>
        <div
          class="text-xs sm:text-sm text-blue-600 font-semibold flex items-center justify-between cursor-pointer hover:text-blue-700 group transition-colors"
          @click="setView('all')">
          <span class="tracking-wide">View Details</span>
          <ArrowRight class="ml-2 w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transform transition-transform" />
        </div>
      </div>

      <!-- Pending Approval -->
      <div class="card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border border-gray-100">
        <div class="flex items-center">
          <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-50 rounded-full flex items-center justify-center">
            <Clock class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-600" />
          </div>
          <div class="ml-4 sm:ml-5">
            <p class="text-xl sm:text-2xl font-bold text-yellow-900">{{ stats.pending_users }}</p>
            <p class="text-xs sm:text-sm text-gray-500 font-medium">Pending Approval</p>
          </div>
        </div>
        <div class="border-t border-gray-100 mt-3 sm:mt-4 mb-2 sm:mb-3"></div>
        <div
          class="text-xs sm:text-sm text-blue-600 font-semibold flex items-center justify-between cursor-pointer hover:text-blue-700 group transition-colors"
          @click="setView('pending')">
          <span class="tracking-wide">View Details</span>
          <ArrowRight class="ml-2 w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transform transition-transform" />
        </div>
      </div>

      <!-- Approved Users -->
      <div class="card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border border-gray-100">
        <div class="flex items-center">
          <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-50 rounded-full flex items-center justify-center">
            <CheckCircle class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" />
          </div>
          <div class="ml-4 sm:ml-5">
            <p class="text-xl sm:text-2xl font-bold text-green-900">{{ stats.approved_users }}</p>
            <p class="text-xs sm:text-sm text-gray-500 font-medium">Approved Users</p>
          </div>
        </div>
        <div class="border-t border-gray-100 mt-3 sm:mt-4 mb-2 sm:mb-3"></div>
        <div
          class="text-xs sm:text-sm text-blue-600 font-semibold flex items-center justify-between cursor-pointer hover:text-blue-700 group transition-colors"
          @click="setView('approved')">
          <span class="tracking-wide">View Details</span>
          <ArrowRight class="ml-2 w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transform transition-transform" />
        </div>
      </div>

      <!-- Rejected Users -->
      <div class="card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border border-gray-100">
        <div class="flex items-center">
          <div class="w-12 h-12 sm:w-14 sm:h-14 bg-red-50 rounded-full flex items-center justify-center">
            <XCircle class="w-6 h-6 sm:w-7 sm:h-7 text-red-600" />
          </div>
          <div class="ml-4 sm:ml-5">
            <p class="text-xl sm:text-2xl font-bold text-red-900">{{ stats.rejected_users }}</p>
            <p class="text-xs sm:text-sm text-gray-500 font-medium">Rejected Users</p>
          </div>
        </div>
        <div class="border-t border-gray-100 mt-3 sm:mt-4 mb-2 sm:mb-3"></div>
        <div
          class="text-xs sm:text-sm text-blue-600 font-semibold flex items-center justify-between cursor-pointer hover:text-blue-700 group transition-colors"
          @click="setView('rejected')">
          <span class="tracking-wide">View Details</span>
          <ArrowRight class="ml-2 w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transform transition-transform" />
        </div>
      </div>
    </div>

    <!-- Dynamic User List -->
    <div class="card bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
      <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 capitalize">
          {{ currentView }} User{{ currentView === 'all' ? 's' : '' }}
        </h2>
        <div class="flex items-center space-x-3 mt-2 sm:mt-0">
          <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
          <span class="text-xs sm:text-sm text-gray-500 font-medium">
            {{
              currentView === 'pending'
                ? pendingUsers.length
                : currentView === 'approved'
                  ? approvedUsers.length
                  : currentView === 'rejected'
                    ? rejectedUsers.length
                    : allUsers.length
            }} total
          </span>
        </div>
      </div>

      <LoadingSpinner v-if="loading" :text="`Loading ${currentView} users...`" />

      <div
        v-else-if="(currentView === 'pending' ? pendingUsers.length : currentView === 'approved' ? approvedUsers.length : currentView === 'rejected' ? rejectedUsers.length : allUsers.length) === 0"
        class="p-8 sm:p-12 lg:p-16 text-center">
        <XCircle class="w-16 h-16 sm:w-20 sm:h-20 text-red-500 mx-auto mb-4 sm:mb-6 animate-bounce" />
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">No users found!</h3>
        <p class="text-sm sm:text-base text-gray-500">There are no users in this category right now.</p>
      </div>

      <div v-else class="divide-y divide-gray-200">
        <div
          v-for="user in currentView === 'pending' ? pendingUsers : currentView === 'approved' ? approvedUsers : currentView === 'rejected' ? rejectedUsers : allUsers"
          :key="user.id" class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200 animate-slide-up">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
              <div
                class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-gray-600 to-gray-800 rounded-full flex items-center justify-center text-white font-semibold text-base sm:text-lg">
                {{ getInitials(user.name) }}
              </div>
              <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ user.name }}</h3>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-xs sm:text-sm text-gray-600 mt-1 space-y-2 sm:space-y-0">
                  <div class="flex items-center">
                    <Mail class="w-4 h-4 mr-2 text-gray-500" />{{ user.email }}
                  </div>
                  <div class="flex items-center">
                    <GraduationCap class="w-4 h-4 mr-2 text-gray-500" />{{ user.college_id }}
                  </div>
                  <div class="flex items-center">
                    <Calendar class="w-4 h-4 mr-2 text-gray-500" />{{ formatDate(user.created_at) }}
                  </div>
                </div>
                <div v-if="user.profile?.course || user.profile?.batch" class="mt-2 sm:mt-3">
                  <div class="flex items-center space-x-2 sm:space-x-3 text-xs sm:text-sm">
                    <span v-if="user.profile.course" class="badge bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full">{{ user.profile.course }}</span>
                    <span v-if="user.profile.batch" class="badge bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full">Batch {{ user.profile.batch }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="currentView === 'pending' || currentView === 'rejected'" class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
              <button
                v-if="currentView === 'pending' || currentView === 'rejected'"
                @click="approveUser(user.id)"
                class="btn bg-green-600 text-white hover:bg-green-700 px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center w-full sm:w-auto">
                <Check class="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                Approve
              </button>
              <button
                v-if="currentView === 'pending'"
                @click="rejectUser(user.id)"
                class="btn bg-red-600 text-white hover:bg-red-700 px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center w-full sm:w-auto">
                <X class="w-4 h-4 sm:w-5 sm:h-5 mr-2" />
                Reject
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import { useToast } from 'vue-toastification'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import {
  Users,
  Clock,
  CheckCircle,
  XCircle,
  Mail,
  GraduationCap,
  Calendar,
  Check,
  X,
  ArrowRight
} from 'lucide-vue-next'

const toast = useToast()

const currentView = ref('pending')
const pendingUsers = ref([])
const approvedUsers = ref([])
const rejectedUsers = ref([])
const allUsers = ref([])

const loading = ref(false)
const stats = ref({
  total_users: 0,
  pending_users: 0,
  approved_users: 0,
  rejected_users: 0,
})

const fetchStats = async () => {
  try {
    const res = await api.get('/admin/stats')
    if (res.data.success) {
      stats.value = res.data.stats
    }
  } catch (err) {
    console.error(err)
  }
}

const fetchPendingUsers = async () => {
  loading.value = true
  try {
    const res = await api.get('/admin/pending-users')
    if (res.data.success) {
      pendingUsers.value = res.data.users
    }
  } finally {
    loading.value = false
  }
}

const setView = async (view) => {
  currentView.value = view
  loading.value = true
  try {
    if (view === 'all') {
      const res = await api.get('/admin/users')
      if (res.data.success) allUsers.value = res.data.users
    } else if (view === 'pending') {
      await fetchPendingUsers()
    } else if (view === 'approved') {
      const res = await api.get('/admin/approved-users')
      if (res.data.success) approvedUsers.value = res.data.users
    } else if (view === 'rejected') {
      const res = await api.get('/admin/rejected-users')
      if (res.data.success) rejectedUsers.value = res.data.users
    }
  } catch (err) {
    toast.error(`Failed to fetch ${view} users`)
  } finally {
    loading.value = false
  }
}

const approveUser = async (id) => {
  try {
    const res = await api.post(`/admin/approve-user/${id}`)
    if (res.data.success) {
      if (currentView.value === 'pending') {
        pendingUsers.value = pendingUsers.value.filter((u) => u.id !== id)
        stats.value.pending_users--
      } else if (currentView.value === 'rejected') {
        rejectedUsers.value = rejectedUsers.value.filter((u) => u.id !== id)
        stats.value.rejected_users--
      }
      stats.value.approved_users++
      toast.success('User approved successfully!')
    }
  } catch {
    toast.error('Approval failed')
  }
}

const rejectUser = async (id) => {
  if (confirm('Reject this user?')) {
    try {
      const res = await api.post(`/admin/reject-user/${id}`)
      if (res.data.success) {
        pendingUsers.value = pendingUsers.value.filter((u) => u.id !== id)
        stats.value.pending_users--
        stats.value.rejected_users++
        toast.success('User rejected.')
      }
    } catch {
      toast.error('Rejection failed')
    }
  }
}

const getInitials = (name) =>
  name.split(' ').map((n) => n[0]).join('').toUpperCase().slice(0, 2)

const formatDate = (d) =>
  new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })

onMounted(() => {
  fetchStats()
  fetchPendingUsers()
})
</script>

<style scoped>
@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-up {
  animation: slide-up 0.3s ease-out;
}

/* Ensure buttons are touch-friendly on mobile */
.btn {
  min-height: 44px; /* Minimum touch target size */
  min-width: 44px;
}

/* Adjust user list layout for very small screens */
@media (max-width: 640px) {
  .card {
    padding: 1rem;
  }
  .btn {
    width: 100%;
    justify-content: center;
  }
  .badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
  }
}
</style>