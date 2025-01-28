<template>
    <div class="mt-5">
        <div class="container mx-auto p-8 bg-white m-5 rounded-lg">
            <div class="text-red-400">{{ test }}</div>
            <form @submit.prevent="generateWithFreeAI" class="mb-8 space-y-6">
                <div class="flex items-center space-x-6 mb-14">
                    <input
                      v-model="form.promptFree"
                        placeholder="Describe what you want to generate"
                        class="border-0 p-4 w-full text-gray-900 shadow-sm ring-1 ring-inset rounded-lg ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-gray-900"
                    />
                    <select
                      v-model="form.frameworkType"
                        class="block w-full rounded-md border-0 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:max-w-xs sm:text-sm/6"
                    >
                        <option
                            v-for="framework in props.frameworkTypes"
                            :key="framework"
                            :value="framework"
                        >
                            {{ framework }}
                        </option>

                        <!-- Add more options as needed -->
                    </select>
                    <button
                        :class="{
                            'bg-slate-600 text-white p-4 rounded-lg hover:bg-slate-700 transition': true,
                        }"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center space-x-4 mt-8">
                    <button
                        type="button"
                        v-for="type in displayedTypes"
                        :key="type"
                        @click="form.elementType = type"
                        :class="{
                            'border border-solid text-dark p-4 rounded-lg transition': true,
                            'bg-gray-900 text-white': form.elementType === type,
                        }"
                    >
                        {{ type }}
                    </button>
                    <!-- See More / See Less button -->
                    <button
                        type="button"
                        v-if="props.elementTypes && props.elementTypes.length > 5"
                        @click.prevent="toggleShowAll"
                        class="p-4 rounded-lg text-blue-500 cursor-pointer"
                    >
                        {{ showAllTypes ? "Less" : "More" }}
                    </button>
                </div>
            </form>
            <div>
                {{ compoentFromAi }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
const prompt = ref("");

const props = defineProps({
    compoentFromAi:String,
    frameworkTypes: Array,
    elementTypes: Array,
});

import { ref, computed } from 'vue';

const form = useForm({
    prompt: "",
    elementType: "",
    frameworkType: "Tailwind CSS",
});

const showAllTypes = ref(false);

const displayedTypes = computed(() => {
    if (!props.elementTypes) return [];
    return showAllTypes.value ? props.elementTypes : props.elementTypes.slice(0, 5);
});

const toggleShowAll = () => {
    showAllTypes.value = !showAllTypes.value;
};


const generateWithFreeAI = () => {
    form.post("/generateWithFreeAI", {
        onSuccess: async () => {
            
        },
        onError: () => {
            alert("Failed to generate code.");
            loading.value = false;
        },
    });
};
</script>
