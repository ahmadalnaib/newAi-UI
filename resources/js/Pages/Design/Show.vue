<template>
    <div class="min-h-screen bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto p-6">
            <iframe
                ref="previewIframe"
                class="w-full h-80 border-none"
                sandbox="allow-scripts allow-same-origin"
            ></iframe>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const { tailwindCode } = props;

const form = ref({
    tailwind: tailwindCode.tailwind,
});

const previewIframe = ref(null);

const updateIframeContent = () => {
    const iframe = previewIframe.value;
    if (iframe) {
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
                    ${form.value.tailwind}
                </body>
            </html>
        `;
    }
};

watch(() => form.value.tailwind, updateIframeContent);

onMounted(() => {
    updateIframeContent();
});
</script>

<style scoped>
iframe {
    height: 800px;

}

</style>