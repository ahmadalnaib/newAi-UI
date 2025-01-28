<template>
  <Codemirror
    v-model:value="code"
    :options="cmOptions"
    :placeholder="placeholder"
    :height="height"
    :width="width"
    :readonly="readonly"
    @change="change"
  />
</template>

<script setup>
import Codemirror from "codemirror-editor-vue3";

// Addons
import "codemirror/addon/display/placeholder.js";
import "codemirror/addon/edit/closebrackets.js";
import "codemirror/addon/edit/closetag.js";
import "codemirror/addon/fold/xml-fold.js";
import "codemirror/addon/hint/show-hint.js";
import "codemirror/addon/hint/xml-hint.js";
import "codemirror/addon/selection/active-line.js";

// Languages
import "codemirror/mode/javascript/javascript.js";
import "codemirror/mode/xml/xml.js";
import "codemirror/mode/css/css.js";
import "codemirror/mode/htmlmixed/htmlmixed.js";

// Theme
import "codemirror/theme/material.css";
import "codemirror/lib/codemirror.css";
import "codemirror/addon/hint/show-hint.css";

import { ref, watch, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  modelValue: {
    type: String,
    default: ""
  },
  language: {
    type: String,
    default: "htmlmixed"
  },
  readonly: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: "Enter code here..."
  },
  fontSize: {
    type: Number,
    default: 18
  },
  height: {
    type: Number,
    default: 600
  },
  width: {
    type: String,
    default: "100%"
  }
});

const emit = defineEmits(["update:modelValue", "change"]);
const code = ref(props.modelValue);

const cmOptions = ref({
  mode: props.language,
  theme: "material",
  lineNumbers: true,
  tabSize: 2,
  indentWithTabs: true,
  readOnly: props.readonly,
  lint: true,
  autoCloseBrackets: true,
  autoCloseTags: true,
  matchBrackets: true,
  foldGutter: true,
  styleActiveLine: true,
  lineWrapping: true,
  gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
  extraKeys: {
    "Ctrl-Space": "autocomplete"
  }
});

// Watch for prop changes
watch(() => props.modelValue, (newValue) => {
  if (code.value !== newValue) {
    code.value = newValue;
  }
});

watch(() => props.readonly, (newValue) => {
  cmOptions.value.readOnly = newValue;
});

watch(() => props.language, (newValue) => {
  cmOptions.value.mode = newValue;
});

// Emit changes
watch(code, (newValue) => {
  emit("update:modelValue", newValue);
});

const change = (instance, changes) => {
  emit("change", instance.getValue());
  emit("update:modelValue", instance.getValue());
};

// Handle font size
const applyFontSize = () => {
  const editorElement = document.querySelector('.CodeMirror');
  if (editorElement) {
    editorElement.style.fontSize = `${props.fontSize}px`;
  }
};

onMounted(() => {
  code.value = props.modelValue;
  applyFontSize();
});

watch(() => props.fontSize, applyFontSize);

onBeforeUnmount(() => {
  // Cleanup if needed
});
</script>

<style>
.CodeMirror {
  height: 100%;
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

.CodeMirror-scroll {
  min-height: 100px;
}

.CodeMirror-focused {
  border-color: #2563eb;
}
</style>