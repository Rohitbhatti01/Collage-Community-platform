<template>
  <div class="flex items-center justify-center" :class="containerClass">
    <div class="loading-spinner" :class="spinnerClass"></div>
    <span v-if="text" class="ml-2 text-sm text-gray-600">{{ text }}</span>
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  text: {
    type: String,
    default: ''
  },
  containerClass: {
    type: String,
    default: 'py-4'
  }
})

const spinnerClass = computed(() => {
  const sizes = {
    sm: 'w-4 h-4',
    md: 'w-6 h-6',
    lg: 'w-8 h-8'
  }
  return sizes[props.size] || sizes.md
})
</script>
