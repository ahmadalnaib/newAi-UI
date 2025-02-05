<template>
    <div class="min-h-screen bg-gray-900 text-white flex flex-col items-center">
        <HeaderWithoutPlansAndFeature />
        <div class="w-full max-w-7xl p-6 mt-16 bg-gray-800 rounded-lg shadow-lg" style="margin-top: 10rem;">
          
            <iframe
                ref="previewIframe"
                class="w-full h-96 border-none rounded-lg shadow-lg"
                sandbox="allow-scripts allow-same-origin"
            ></iframe>
        </div>
    </div>
</template>

<script setup>
import HeaderWithoutPlansAndFeature from "@/Components/HeaderWithoutPlansAndFeature.vue";
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