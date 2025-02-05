<template>
    <div class="min-h-screen bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-4xl font-bold mb-4">Components</h1>
            <p class="text-lg mb-8">
                Beautifully crafted UI components, ready for your next project.
            </p>
            <p class="text-lg mb-8">
                Over 500+ professionally designed, fully responsive, expertly crafted component examples you can drop into your Tailwind projects and customize to your heart’s content.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a v-for="design in designs" :key="design.id" :href="`/design/${design.slug}`" class="block border border-gray-700 rounded overflow-hidden">
                    <div v-html="design.tailwind" class="w-full h-48 bg-cover bg-center"></div>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    designs: Array
})
</script>