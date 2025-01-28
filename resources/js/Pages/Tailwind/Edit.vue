<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage ,Link} from "@inertiajs/vue3";
import { ref, watch, onMounted, nextTick } from "vue";
import CodeMirror from "@/Components/CodeMirror.vue";
import {
    CodeBracketIcon,
    EyeIcon,
    DocumentDuplicateIcon,
    ViewfinderCircleIcon,
} from "@heroicons/vue/24/outline";

const activeTab = ref("preview");

const { props } = usePage();
const { tailwindCode } = props;

const form = useForm({
    tailwind: tailwindCode.tailwind,
});

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

watch(() => form.tailwind, updateIframeContent);

watch(activeTab, async (newTab) => {
    if (newTab === "preview") {
        await nextTick(); // Wait for DOM update
        updateIframeContent();
    }
});

onMounted(() => {
    updateIframeContent();
});

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
</script>

<template>
    <Head title="View UI" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold leading-tight text-gray-800">
                    View UI
                </h2>
                <div>
                    <Link
                 
                    class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
                    href="/ui/create"
                >
                    Create New Component
                </Link>
            
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-full px-6 sm:px-8 lg:px-10 rounded-lg mb-12">
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
                        <CodeBracketIcon class="h-5 w-5" />
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
                        <EyeIcon class="h-5 w-5" />
                        <span>Preview</span>
                    </button>
                    <div class="flex-grow"></div>
                    <!-- Action Buttons -->
                    <button
                        v-if="activeTab === 'preview'"
                        @click="openFullscreen"
                        class="inline-flex items-center gap-x-2 rounded-md bg-gray-100 px-3.5 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700"
                    >
                        Fullscreen Preview
                        <ViewfinderCircleIcon class="h-5 w-5" />
                    </button>
                    <button
                        v-if="activeTab === 'code'"
                        @click="copyCode"
                        class="flex items-center px-4 py-2 text-sm text-gray-500 hover:text-gray-700"
                    >
                        <DocumentDuplicateIcon class="h-5 w-5 mr-1" />
                        Copy
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="transition-all duration-300">
                    <!-- Code Tab -->
                    <div v-if="activeTab === 'code'">
                        <div>
                            <CodeMirror
                                id="tailwind"
                                v-model="form.tailwind"
                                :options="{
                                    mode: 'text/css',
                                    theme: 'material',
                                    lineNumbers: true,
                                    tabSize: 2,
                                    lineWrapping: true,
                                    autoCloseBrackets: true,
                                    matchBrackets: true,
                                    styleActiveLine: true,
                                }"
                                :height="800"
                                placeholder="Enter CSS code here..."
                                class="editor border border-gray-300 rounded-lg h-128 overflow-hidden"
                            />
                        </div>
                    </div>

                    <!-- Preview Tab -->
                    <div v-if="activeTab === 'preview'">
                        <iframe
                            ref="previewIframe"
                            class="w-full editor border border-gray-300 rounded-lg mb-6 overflow-auto"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.container {
    max-width: 1400px;
}
.editor {
    height: 800px;
}
</style>
