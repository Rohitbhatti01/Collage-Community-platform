<template>
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
      <p class="text-gray-600">Manage users and monitor platform activity</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
      <div class="card p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <Users class="w-6 h-6 text-blue-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-gray-900">{{ stats.total_users }}</p>
            <p class="text-sm text-gray-600">Total Users</p>
          </div>
        </div>
      </div>
      
      <div class="card p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <Clock class="w-6 h-6 text-yellow-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-yellow-600">{{ stats.pending_users }}</p>
            <p class="text-sm text-gray-600">Pending Approval</p>
          </div>
        </div>
      </div>
      
      <div class="card p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <CheckCircle class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-green-600">{{ stats.approved_users }}</p>
            <p class="text-sm text-gray-600">Approved Users</p>
          </div>
        </div>
      </div>
      
      <div class="card p-6">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <XCircle class="w-6 h-6 text-red-600" />
          </div>
          <div class="ml-4">
            <p class="text-2xl font-bold text-red-600">{{ stats.rejected_users }}</p>
            <p class="text-sm text-gray-600">Rejected Users</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Users -->
    <div class="card">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Pending User Approvals</h2>
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
            <span class="text-sm text-gray-600">{{ pendingUsers.length }} pending</span>
          </div>
        </div>
      </div>
      
      <LoadingSpinner v-if="loading" text="Loading pending users..." />
      
      <div v-else-if="pendingUsers.length === 0" class="p-12 text-center">
        <CheckCircle class="w-16 h-16 text-green-500 mx-auto mb-4" />
        <h3 class="text-lg font-medium text-gray-900 mb-2">All caught up!</h3>
        <p class="text-gray-500">No pending user approvals at the moment.</p>
      </div>
      
      <div v-else class="divide-y divide-gray-200">
        <div
          v-for="user in pendingUsers"
          :key="user.id"
          class="p-6 hover:bg-gray-50 transition-colors animate-slide-up"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center text-white font-medium">
                {{ getInitials(user.name) }}
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ user.name }}</h3>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                  <div class="flex items-center">
                    <Mail class="w-4 h-4 mr-1" />
                    {{ user.email }}
                  </div>
                  <div class="flex items-center">
                    <GraduationCap class="w-4 h-4 mr-1" />
                    {{ user.college_id }}
                  </div>
                  <div class="flex items-center">
                    <Calendar class="w-4 h-4 mr-1" />
                    {{ formatDate(user.created_at) }}
                  </div>
                </div>
                <div v-if="user.profile?.course || user.profile?.batch" class="mt-2">
                  <div class="flex items-center space-x-2 text-sm">
                    <span v-if="user.profile.course" class="badge badge-secondary">
                      {{ user.profile.course }}
                    </span>
                    <span v-if="user.profile.batch" class="badge badge-secondary">
                      Batch {{ user.profile.batch }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <button
                @click="approveUser(user.id)"
                class="btn btn-sm bg-green-600 text-white hover:bg-green-700"
              >
                <Check class="w-4 h-4 mr-1" />
                Approve
              </button>
              <button
                @click="rejectUser(user.id)"
                class="btn btn-sm bg-red-600 text-white hover:bg-red-700"
              >
                <X class="w-4 h-4 mr-1" />
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
  X
} from 'lucide-vue-next'

const toast = useToast()
const pendingUsers = ref([])
const loading = ref(false)
const stats = ref({
  total_users: 0,
  pending_users: 0,
  approved_users: 0,
  rejected_users: 0
})

const fetchPendingUsers = async () => {
  loading.value = true
  try {
    const response = await api.get('/admin/pending-users')
    if (response.data.success) {
      pendingUsers.value = response.data.users
    }
  } catch (error) {
    toast.error('Failed to fetch pending users')
    console.error('Fetch pending users error:', error)
  } finally {
    loading.value = false
  }
}

const fetchStats = async () => {
  try {
    const response = await api.get('/admin/stats')
    if (response.data.success) {
      stats.value = response.data.stats
    }
  } catch (error) {
    console.error('Fetch stats error:', error)
  }
}

const approveUser = async (userId) => {
  try {
    const response = await api.post(`/admin/approve-user/${userId}`)
    if (response.data.success) {
      pendingUsers.value = pendingUsers.value.filter(user => user.id !== userId)
      stats.value.pending_users--
      stats.value.approved_users++
      toast.success('User approved successfully!')
    }
  } catch (error) {
    toast.error('Failed to approve user')
    console.error('Approve user error:', error)
  }
}

const rejectUser = async (userId) => {
  if (confirm('Are you sure you want to reject this user? This action cannot be undone.')) {
    try {
      const response = await api.post(`/admin/reject-user/${userId}`)
      if (response.data.success) {
        pendingUsers.value = pendingUsers.value.filter(user => user.id !== userId)
        stats.value.pending_users--
        stats.value.rejected_users++
        toast.success('User rejected successfully')
      }
    } catch (error) {
      toast.error('Failed to reject user')
      console.error('Reject user error:', error)
    }
  }
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
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

onMounted(() => {
  fetchPendingUsers()
  fetchStats()
})
</script>
