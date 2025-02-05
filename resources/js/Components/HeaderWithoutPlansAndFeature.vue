<template>
    <header class="absolute inset-x-0 top-0 z-50">
      <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
          <Link href="/" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
            <img class="mx-auto h-12 w-auto" src="/img/logo_white.svg" alt="Your Company">
          </Link>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
          <Link href="/" class="text-lg font-semibold text-white">Home</Link>
          <Link href="/design" class="text-lg font-semibold text-white">Components</Link>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <!-- Show login/register links only if the user is not logged in -->
          <template v-if="!$page.props.auth.user">
            <Link href="/login" class="text-lg font-semibold text-white">Log in</Link>
            <Link href="/register" class="ml-6 text-lg font-semibold text-white">Register</Link>
          </template>
          
          <!-- Show Dashboard link if the user is logged in -->
          <template v-else>
            <Link href="/dashboard" class="text-sm font-semibold text-white">Dashboard</Link>
          </template>
        </div>
      </nav>
    </header>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import { Dialog, DialogPanel } from '@headlessui/vue'
  import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
  
  const mobileMenuOpen = ref(false);
  
  defineProps({
    canLogin: {
      type: Boolean,
    },
    canRegister: {
      type: Boolean,
    },
  });
  </script>