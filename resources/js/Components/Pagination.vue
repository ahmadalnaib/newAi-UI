<template>
  <div class="flex items-center justify-center gap-2 mt-5">
      <Component
          v-for="link in links"
          :key="link.label"
          :is="link.url ? Link : 'span'"
          :href="link.url"
          v-html="link.label"
          :class="[
              'px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150',
              link.url 
                  ? link.active
                      ? 'bg-gray-900 text-white hover:bg-gray-700'
                      : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                  : 'text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed',
              'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500'
          ]"
      />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  links: {
      type: Array,
      required: true,
      default: () => []
  }
});
</script>