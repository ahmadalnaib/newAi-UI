<template>
  <div class="bg-gray-900 py-24">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-20 sm:rounded-3xl sm:px-10 sm:py-24 lg:py-24 xl:px-24">
        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-center lg:gap-y-0">
          <div class="lg:row-start-2 lg:max-w-md">
            <h2 class="text-balance text-3xl font-semibold tracking-tight text-white sm:text-4xl">
              Enhance your workflow. Use CheapUI today.
            </h2>
            <p class="mt-6 text-lg/8 text-gray-300">
              CheapUI simplifies your development process with AI-generated components, full customization, and seamless integration into your project. Save time and build smarter.
            </p>
          </div>
          <video
            src="/video/new.mp4"
            autoplay
            muted
            loop
            class="relative -z-20 min-w-full max-w-xl rounded-xl shadow-xl  lg:row-span-4 lg:w-[30rem] lg:max-w-none h-[40rem]" 
          ></video>
          <div class="max-w-xl lg:row-start-3 lg:mt-10 lg:max-w-md lg:border-t lg:border-white/10 lg:pt-10">
            <dl class="max-w-xl space-y-8 text-base/7 text-gray-300 lg:max-w-none">
              <div v-for="feature in features" :key="feature.name" class="relative">
                <dt class="ml-9 inline-block font-semibold text-white">
                  <component :is="feature.icon" class="absolute left-1 top-1 size-5 text-indigo-500" aria-hidden="true" />
                  {{ feature.name }}
                </dt>
                {{ ' ' }}
                <dd class="inline">{{ feature.description }}</dd>
              </div>
            </dl>
          </div>
        </div>
        <div class="pointer-events-none absolute left-12 top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-3xl lg:bottom-[-12rem] lg:top-auto lg:translate-y-0 lg:transform-gpu" aria-hidden="true">
          <div class="aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-25" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { CloudArrowUpIcon, AdjustmentsHorizontalIcon, ClipboardDocumentIcon } from '@heroicons/vue/20/solid'
const features = [
  {
    name: 'Components made by AI',
    description: 'Generate UI components in seconds with the power of AI, tailored to your needs.',
    icon: CloudArrowUpIcon,
  },
  {
    name: 'Fully customizable with AI',
    description: 'Modify and adapt each component to perfectly fit your project using advanced AI tools.',
    icon: AdjustmentsHorizontalIcon,
  },
  {
    name: 'Copy and paste integration',
    description: 'Easily integrate components by copying and pasting directly into your codebase.',
    icon: ClipboardDocumentIcon,
  },
]
</script>