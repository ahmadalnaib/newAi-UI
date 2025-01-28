<template>
    <Head title="Plans" />

<AuthenticatedLayout>
    <div class="relative isolate bg-gray-50 px-6 py-24 sm:py-32 lg:px-8">
      <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
        <div class="mx-auto aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
      </div>
      <div class="mx-auto max-w-7xl text-center">
        <h2 class="text-base font-semibold text-indigo-600">Pricing</h2>
        <p class="mt-2 text-5xl font-semibold tracking-tight text-gray-900 sm:text-6xl"> Take flight with CheapUI</p>
      </div>
      <div class="mx-auto mt-16 flex flex-col items-center">
    <div
        class="relative max-w-md w-full rounded-lg p-6 ring-1 ring-gray-200 sm:p-8 bg-white shadow-md hover:shadow-xl transition-shadow duration-300"
    >
        <div v-for="plan in plans" :key="plan.id" class="relative rounded-lg p-6 ring-1 ring-gray-200 sm:p-8 bg-white shadow-md hover:shadow-xl transition-shadow duration-300">
          <h3 class="text-indigo-600 text-2xl font-bold text-center">{{ plan.name }}</h3>
          <p class="mt-4 text-4xl font-extrabold text-gray-900 text-center">{{ plan.price }} €</p>

  
          <a :href="`/checkout?plan_id=${plan.id}`" class="mt-6 block w-full rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started today</a>
        </div>
      </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps({
  plans: {
    type: Array,
    required: true,
  },
});
</script>