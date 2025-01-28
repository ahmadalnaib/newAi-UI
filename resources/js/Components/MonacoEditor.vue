<template>
    <div ref="editorContainer" class="monaco-editor"></div>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import * as monaco from "monaco-editor";
import debounce from "lodash/debounce";

if (typeof MonacoEnvironment === "undefined") {
  window.MonacoEnvironment = {
    getWorkerUrl: function (moduleId, label) {
      return `data:text/javascript;charset=utf-8,${encodeURIComponent(`
        self.onmessage = function(e) {
          import('https://unpkg.com/monaco-editor@0.36.1/min/vs/base/worker/workerMain.js')
            .then(workerModule => {
              workerModule.default();
              postMessage("worker loaded");
            })
            .catch(error => {
              console.error("Failed to load Monaco worker", error);
            });
        }
      `)}`;
    }
  };
}


const props = defineProps({
    modelValue: String,
    language: String,
    theme: {
        type: String,
        default: "vs-dark",
    },
    fontSize: {
        type: Number,
        default: 16,
    },
});

const emit = defineEmits(["update:modelValue"]);
const editorContainer = ref(null);
let editor = null;

onMounted(() => {
    editor = monaco.editor.create(editorContainer.value, {
        value: props.modelValue,
        language: props.language,
        theme: props.theme,
        fontSize: props.fontSize,
        automaticLayout: true,
    });

    const debouncedUpdate = debounce(() => {
        emit("update:modelValue", editor.getValue());
    }, 300);

    editor.onDidChangeModelContent(debouncedUpdate);
});

watch(() => props.modelValue, (newValue) => {
    if (editor && editor.getValue() !== newValue) {
        editor.setValue(newValue);
    }
});

watch(() => props.theme, (newTheme) => {
    monaco.editor.setTheme(newTheme);
});

onBeforeUnmount(() => {
    if (editor) {
        editor.dispose();
    }
});
</script>



<style scoped>

.monaco-editor {
    /* height: 300px;
    border: 1px solid #ccc;
    border-radius: 4px; */
}
</style>
