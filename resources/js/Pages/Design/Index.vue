<template>
        <Head title="UI components" />
    <div class="min-h-screen bg-gray-900 text-white flex flex-col items-center">
        <!-- Include the modified Header component -->
        <HeaderWithoutPlansAndFeature />

        <div class="w-full max-w-7xl p-6 mt-16" style="margin-top: 10rem;">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-4xl font-bold">AI-Powered UI Component Library</h1>
            </div>
            <p class="text-lg mb-4">
                Discover a wide range of AI-generated UI components designed to enhance your web projects.
            </p>
            <p class="text-lg mb-6">
                Explore over 500+ unique, fully responsive, and customizable components created with HTML, CSS, Bootstrap, and Tailwind CSS, seamlessly integrating with your projects.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-8">
                <a v-for="design in designs" :key="design.id" :href="`/design/${design.slug}`" class="relative block border border-gray-700 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105 bg-white text-gray-900">
                    <div v-html="design.tailwind" class="w-full h-96 bg-cover bg-center blur-sm hover:blur-none transition-blur duration-300"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                        <button class="bg-gray-900 text-white px-4 py-4 rounded-md shadow-lg">View</button>
                    </div>
                    <div class="p-4">
                    
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import HeaderWithoutPlansAndFeature from "@/Components/HeaderWithoutPlansAndFeature.vue";
import {Head } from "@inertiajs/vue3";
defineProps({
    designs: Array,
});
</script>

<style scoped>
/* Add any additional custom styles here */
</style>