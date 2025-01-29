<template>
    <Head title="Create UI" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="text-3xl font-black leading-tight text-gray-800"
                ></h2>
                <Link
                    v-if="showForm === false"
                    href="/ui/create"
                    type="button"
                    class="inline-flex items-center gap-x-2 rounded-md bg-gray-900 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                >
                    New Component
                    <PlusIcon class="size-5" aria-hidden="true" />
                </Link>
            </div>
        </template>
        <div class="mt-5">
            <div
                v-if="showForm"
                class="container mx-auto p-8 bg-white m-5 rounded-lg"
            >
                <form @submit.prevent="generateWithAI" class="mb-8 space-y-6">
                    <div class="flex items-center space-x-6 mb-14">
                        <input
                            v-model="form2.prompt"
                            placeholder="Describe what you want to generate"
                            :disabled="loading"
                            class="border-0 p-4 w-full text-gray-900 shadow-sm ring-1 ring-inset rounded-lg ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-gray-900"
                        />
                        <select
                            v-model="form2.frameworkType"
                            :disabled="loading"
                            class="block w-full rounded-md border-0 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:max-w-xs sm:text-sm/6"
                        >
                            <option
                                v-for="framework in props.frameworkTypes"
                                :key="framework"
                                :value="framework"
                            >
                                {{ framework }}
                            </option>
                        </select>
                        <button
                            :disabled="isGenerateButtonDisabled || loading"
                            :class="{
                                'bg-slate-600 text-white p-4 rounded-lg hover:bg-slate-700 transition':
                                    !isGenerateButtonDisabled && !loading,
                                'bg-gray-400 text-gray-700 p-4 rounded-lg cursor-not-allowed':
                                    isGenerateButtonDisabled || loading,
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
                            type="submit"
                            v-for="type in displayedTypes"
                            :key="type.label"
                            @click="form2.elementType = type.label"
                            :disabled="loading"
                            :class="{
                                'border border-solid text-dark p-4 rounded-lg transition': true,
                                'bg-gray-900 text-white':
                                    form2.elementType === type.label,
                                'bg-gray-400 text-gray-700 cursor-not-allowed':
                                    loading,
                            }"
                        >
                            <div class="flex">
                                <span v-html="type.icon" class="mr-2"></span>
                                <span> {{ type.label }}</span>
                            </div>
                        </button>
                        <!-- See More / See Less button -->
                        <button
                            type="submit"
                            v-if="
                                props.elementTypes &&
                                props.elementTypes.length > 5
                            "
                            @click.prevent="toggleShowAll"
                            :disabled="loading"
                            class="p-4 rounded-lg text-blue-500 cursor-pointer disabled:cursor-not-allowed"
                        >
                            {{ showAllTypes ? "" : "More" }}
                        </button>
                    </div>
                </form>
            </div>
            <div v-else></div>
        </div>

        <div
            v-if="loading"
            class="mx-auto max-w-full px-6 sm:px-8 lg:px-10 rounded-lg mb-12"
        >
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-10">
                <LoadingCard />
            </div>
        </div>
        <div class="relative">
        <div
            v-if="pageShow"
            class="mx-auto max-w-full px-6 sm:px-8 lg:px-10 rounded-lg mb-12"
        >
            <div class="bg-white p-8 rounded-lg">
                <!-- Tab Navigation -->
                <div class="flex border-b border-gray-200 mb-6">
                    <button
                        @click="activeTab = 'code'"
                        :class="[
                            'flex items-center px-6 py-3 text-sm font-medium border-b-2 -mb-px space-x-2',
                            activeTab === 'code'
                                ? 'border-gray-900 text-gray-900 bg-gray-50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                        ]"
                    >
                        <CodeBracketIcon class="size-6" />
                        <span>Code</span>
                    </button>
                    <button
                        @click="activeTab = 'preview'"
                        :class="[
                            'flex items-center px-6 py-3 text-sm font-medium border-b-2 -mb-px space-x-2 ml-4',
                            activeTab === 'preview'
                                ? 'border-gray-900 text-gray-900 bg-gray-50'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                        ]"
                    >
                        <EyeIcon class="size-6" />
                        <span>Preview</span>
                    </button>
                    <div
                        class="flex-grow flex items-center justify-center px-4"
                    >
                        <form
                            @submit.prevent="editAiCode"
                            class="w-full max-w-2xl"
                        >
                            <div class="relative">
                                <input
                                    type="text"
                                    v-model="form3.prompt"
                                    placeholder="Edit your component..."
                                    :class="{
                                        'opacity-50 cursor-not-allowed':
                                            isDisabled,
                                    }"
                                    class="w-full px-4 py-3 text-gray-700 bg-white rounded-xl shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 ease-in-out pr-32"
                                />
                                <button
                                    type="submit"
                                    :class="{
                                        'opacity-50 cursor-not-allowed':
                                            isDisabled,
                                    }"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-x-2 px-4 py-2 rounded-lg bg-gradient-to-r from-gray-800 to-gray-700 text-white text-sm font-medium hover:from-gray-700 hover:to-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span>Apply Edit</span>
                                    <PencilIcon class="size-4" />
                                </button>
                            </div>
                        </form>
                    </div>
                    <button
                        v-if="activeTab === 'preview'"
                        @click="openFullscreen"
                        :disabled="loading"
                        class="inline-flex items-center gap-x-2 rounded-md bg-gray-100 px-3.5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        Fullscreen Preview
                        <ViewfinderCircleIcon class="size-6" />
                    </button>

                    <button
                        v-if="activeTab === 'code'"
                        @click="copyCode"
                        class="flex items-center px-4 py-2 text-sm text-gray-500 hover:text-gray-700"
                    >
                        <DocumentDuplicateIcon class="size-5 mr-1" />
                        Copy
                    </button>
                    <div class="flex justify-end space-x-6">
                        <button
                            @click="saveCode"
                            :disabled="loading"
                            class="inline-flex border items-center gap-x-2 rounded-md bg-gray-100 px-3.5 py-2.5 text-sm font-semibold text-gray-500 focus-visible:outline hover:text-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 disabled:bg-gray-400 disabled:cursor-not-allowed ml-2"
                        >
                            Save Component
                            <CloudArrowUpIcon class="size-5 mr-1" />
                        </button>
                    </div>
                </div>

                <!-- Tab Panels -->
                <div class="transition-all duration-300">
                    <div v-if="activeTab === 'code'" class="space-y-8">
                        <div>
                            <CodeMirror
                                id="tailwind"
                                v-model="form.tailwind"
                                :options="{
                                    mode: 'text/css',
                                    theme: 'material',
                                    lineNumbers: true,
                                    tabSize: 2,
                                    readOnly: true,
                                    lineWrapping: true,
                                    autoCloseBrackets: true,
                                    matchBrackets: true,
                                    styleActiveLine: true,
                                }"
                                :height="800"
                                :readonly="true"
                                class="editor border border-gray-300 rounded-lg h-128 overflow-hidden"
                            />
                        </div>
                    </div>

                    <div v-if="activeTab === 'preview'" class="space-y-8">
                        <iframe
                            ref="previewIframe"
                            class="w-full editor border border-gray-300 rounded-lg overflow-auto"
                        ></iframe>
                        <div class="flex justify-end"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div v-if="isEditing" class="loading-overlay fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <EditingLoading/>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, nextTick, computed } from "vue";
import CodeMirror from "@/Components/CodeMirror.vue";
import LoadingCard from "@/Components/LoadingCard.vue";
import EditingLoading from "@/Components/EditingLoading.vue";
import {
    CodeBracketIcon,
    EyeIcon,
    DocumentDuplicateIcon,
    PlusIcon,
    ViewfinderCircleIcon,
    CloudArrowUpIcon,
    PencilIcon,
} from "@heroicons/vue/24/outline";

const loading = ref(false);
const pageShow = ref(false);
const showForm = ref(true);
const prompt = ref("");
const isDisabled = ref(false);
const isEditing = ref(false);
const showAllTypes = ref(false); // Added
const activeTab = ref("preview");

const props = defineProps({
    generatedTailwind: String,
    elementTypes: Array,
    frameworkTypes: Array,
});

const form = useForm({
    tailwind: "",
});

const form2 = useForm({
    prompt: "",
    elementType: "",
    frameworkType: "Tailwind CSS",
});
const form3 = useForm({
    prompt: "",
});

const displayedTypes = computed(() => {
    if (!props.elementTypes) return [];
    return showAllTypes.value
        ? props.elementTypes
        : props.elementTypes.slice(0, 5);
});

const toggleShowAll = () => {
    // Added
    showAllTypes.value = !showAllTypes.value;
};

const previewIframe = ref(null);

const updateIframeContent = () => {
    const iframe = previewIframe.value;
    if (iframe) {
        // Clear previous content
        iframe.srcdoc = `
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                </head>
                <body>
                    ${form.tailwind}
                </body>
            </html>
        `;
    }
};

watch(
    () => props.generatedTailwind,
    (newValue) => {
        if (newValue) {
            form.tailwind = newValue;
            updateIframeContent();
        }
    }
);

watch(() => form.tailwind, updateIframeContent);

const isGenerateButtonDisabled = computed(() => {
    return !form2.prompt || loading.value;
});

const saveCode = () => {
    loading.value = false;
    form.post(route("ui.store"), {
        onSuccess: () => {
            alert("Code saved successfully!");
            loading.value = false;
        },
        onError: () => {
            alert("Failed to save code.");
            loading.value = false;
        },
    });
};

const generateWithAI = () => {
    loading.value = true;
    form2.post("/codegenerate", {
        onSuccess: async () => {
            form2.reset();
            loading.value = false;
            pageShow.value = true;
            showForm.value = false;
            await nextTick();
            updateIframeContent();
        },
        onError: () => {
            alert("Failed to generate code.");
            loading.value = false;
        },
    });
};

const editAiCode = () => {
    isEditing.value = true;
    isDisabled.value = true;
    form3.post("/editgenerate", {
        onSuccess: async (response) => {
            form3.reset();
            isDisabled.value = false;
            isEditing.value = false;
            await nextTick();
            updateIframeContent();
        },
        onError: () => {
            alert("Failed to generate code.");
            isDisabled.value = false;
            isEditing.value = false;
        },
    });
};

const openFullscreen = () => {
    if (previewIframe.value.requestFullscreen) {
        previewIframe.value.requestFullscreen();
    }
};

const copyCode = () => {
    navigator.clipboard
        .writeText(form.tailwind)
        .then(() => alert("Code copied to clipboard!"))
        .catch(() => alert("Failed to copy code"));
};
watch(activeTab, async (newTab) => {
    if (newTab === "preview") {
        await nextTick();
        updateIframeContent();
    }
});
</script>

<style scoped>
.container {
    max-width: 1400px;
}
.editor {
    height: 800px;
}
</style>
