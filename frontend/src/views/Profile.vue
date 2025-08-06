<template>
  <div class="max-w-4xl mx-auto py-6 px-4">
    <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
      {{ error }}
    </div>
    <div v-else-if="user" class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center mb-6">
        <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center">
          <img
            v-if="user?.profile?.photo"
            :src="`${apiBaseUrl}/storage/${user.profile.photo}`"
            alt="Profile Photo"
            class="w-full h-full object-cover rounded-full"
            @error="handleImageError"
          />
          <span v-else class="text-2xl font-bold">{{ user?.name?.charAt(0).toUpperCase() }}</span>
        </div>
        <div class="ml-6">
          <h1 class="text-2xl font-bold">{{ user?.name }}</h1>
          <p class="text-gray-600">{{ user?.email }}</p>
          <p v-if="user?.college_id" class="text-sm text-gray-500">College ID: {{ user.college_id }}</p>
        </div>
      </div>

      <div v-if="isOwnProfile" class="mb-6">
        <button
          @click="editMode = !editMode"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
        >
          {{ editMode ? 'Cancel' : 'Edit Profile' }}
        </button>
      </div>

      <div v-if="editMode" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input
            v-model="profileForm.name"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            v-model="profileForm.email"
            type="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
          <input
            v-model="profileForm.batch"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
          <input
            v-model="profileForm.course"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
          <textarea
            v-model="profileForm.bio"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
          <input
            v-model="profileForm.current_status"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
          <input
            v-model="profileForm.location"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
          <input
            v-model="profileForm.phone"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
          <input
            type="file"
            ref="photoInput"
            accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            @change="handlePhotoChange"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Social Links</label>
          <div class="flex gap-4">
  <input
    v-model="profileForm.socials.twitter"
    type="text"
    placeholder="Twitter URL"
    class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
  />
  <input
    v-model="profileForm.socials.linkedin"
    type="text"
    placeholder="LinkedIn URL"
    class="w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
  />
</div>

        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Skills (comma-separated)</label>
          <input
            v-model="skillsInput"
            type="text"
            placeholder="e.g., PHP, Laravel, Vue"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <button
          @click="updateProfile"
          :disabled="loading"
          class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:opacity-50"
        >
          {{ loading ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>

      <div v-else class="space-y-4">
        <div v-if="user?.profile?.batch">
          <h3 class="font-semibold text-gray-900">Batch</h3>
          <p class="text-gray-700">{{ user.profile.batch }}</p>
        </div>

        <div v-if="user?.profile?.course">
          <h3 class="font-semibold text-gray-900">Course</h3>
          <p class="text-gray-700">{{ user.profile.course }}</p>
        </div>

        <div v-if="user?.profile?.bio">
          <h3 class="font-semibold text-gray-900">Bio</h3>
          <p class="text-gray-700">{{ user.profile.bio }}</p>
        </div>

        <div v-if="user?.profile?.current_status">
          <h3 class="font-semibold text-gray-900">Current Status</h3>
          <p class="text-gray-700">{{ user.profile.current_status }}</p>
        </div>

        <div v-if="user?.profile?.location">
          <h3 class="font-semibold text-gray-900">Location</h3>
          <p class="text-gray-700">{{ user.profile.location }}</p>
        </div>

        <div v-if="user?.profile?.phone">
          <h3 class="font-semibold text-gray-900">Phone</h3>
          <p class="text-gray-700">{{ user.profile.phone }}</p>
        </div>

        <div v-if="user?.profile?.socials">
          <h3 class="font-semibold text-gray-900">Social Links</h3>
          <div class="space-y-2">
            <a v-if="user.profile.socials.twitter" :href="user.profile.socials.twitter" target="_blank" class="text-blue-600">Twitter</a>
            <a v-if="user.profile.socials.linkedin" :href="user.profile.socials.linkedin" target="_blank" class="text-blue-600">LinkedIn</a>
          </div>
        </div>

        <div v-if="user?.profile?.skills?.length">
          <h3 class="font-semibold text-gray-900">Skills</h3>
          <div class="flex flex-wrap gap-2 mt-2">
            <span
              v-for="skill in user.profile.skills"
              :key="skill"
              class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"
            >
              {{ skill }}
            </span>
          </div>
        </div>
      </div>

      <div v-if="!isOwnProfile" class="mt-6">
        <button
          @click="sendMessage"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
        >
          Send Message
        </button>
      </div>
    </div>
    <div v-else class="text-center">
      <p>Loading profile...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import api from '../api/axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

const apiBaseUrl = 'http://127.0.0.1:8000';
const user = ref(null);
const error = ref(null);
const editMode = ref(false);
const loading = ref(false);
const skillsInput = ref('');
const photoInput = ref(null);

const profileForm = reactive({
  name: '',
  email: '',
  batch: '',
  course: '',
  bio: '',
  current_status: '',
  location: '',
  phone: '',
  socials: { twitter: '', linkedin: '' },
  skills: [],
  photo: null,
});

const isOwnProfile = computed(() => {
  const profileId = route.params.id;
  return !profileId || parseInt(profileId) === authStore.user?.id;
});

const handlePhotoChange = (event) => {
  profileForm.photo = event.target.files[0];
};

const handleImageError = () => {
  console.error('Failed to load profile photo');
};

const fetchProfile = async () => {
  try {
    error.value = null;
    if (!authStore.user && authStore.token) {
      await authStore.fetchUser();
    }

    const userId = route.params.id || authStore.user?.id;
    if (!userId) {
      throw new Error('User ID not available');
    }

    const response = await api.get(`/profile/${userId}`);
    user.value = response.data.user;

    if (user.value) {
      profileForm.name = user.value.name || '';
      profileForm.email = user.value.email || '';
      if (user.value.profile) {
        Object.assign(profileForm, {
          batch: user.value.profile.batch || '',
          course: user.value.profile.course || '',
          bio: user.value.profile.bio || '',
          current_status: user.value.profile.current_status || '',
          location: user.value.profile.location || '',
          phone: user.value.profile.phone || '',
          socials: user.value.profile.socials || { twitter: '', linkedin: '' },
          skills: Array.isArray(user.value.profile.skills) ? user.value.profile.skills : [],
        });
        skillsInput.value = Array.isArray(user.value.profile.skills) ? user.value.profile.skills.join(', ') : '';
      }
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to fetch profile';
    toast.error(error.value);
    console.error('Fetch profile error:', err);
  }
};

const updateProfile = async () => {
  loading.value = true;
  try {
    profileForm.skills = skillsInput.value.split(',').map(skill => skill.trim()).filter(Boolean);

    const formData = new FormData();
    formData.append('name', profileForm.name || '');
    formData.append('email', profileForm.email || '');
    formData.append('batch', profileForm.batch || '');
    formData.append('course', profileForm.course || '');
    formData.append('bio', profileForm.bio || '');
    formData.append('current_status', profileForm.current_status || '');
    formData.append('location', profileForm.location || '');
    formData.append('phone', profileForm.phone || '');

    // ✅ Append skills as array
    profileForm.skills.forEach((skill, index) => {
      formData.append(`skills[${index}]`, skill);
    });

    // ✅ Append socials as object
    Object.entries(profileForm.socials).forEach(([key, value]) => {
      if (value) formData.append(`socials[${key}]`, value);
    });

    // ✅ Append photo
    if (profileForm.photo) {
      formData.append('photo', profileForm.photo);
    }

    const response = await api.post('/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    user.value = response.data.user;
    editMode.value = false;
    profileForm.photo = null;
    if (photoInput.value) photoInput.value.value = '';
    toast.success('Profile updated successfully');
  } catch (error) {
    const message = error.response?.data?.message || 'Failed to update profile';
    toast.error(message);
    console.error('Update profile error:', error);
  } finally {
    loading.value = false;
  }
};


const sendMessage = () => {
  router.push(`/messages?user=${user.value.id}`);
};

onMounted(async () => {
  await fetchProfile();
});
</script>