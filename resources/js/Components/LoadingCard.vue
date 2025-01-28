<template>
  <div class="p-4 w-full">
    <div class="bg-black rounded-lg shadow-lg overflow-hidden transform transition-transform hover:scale-105 flex flex-col h-full">
      <div ref="scrollContainer" class="p-4 flex-grow flex flex-col text-indigo-500 font-mono overflow-hidden">
        <div v-for="(line, index) in lines" :key="index" class="h-4 mb-2 animate-line">{{ line }}</div>
        <div ref="finalMessage" class="h-4 mb-2 animate-line" style="opacity: 0; transform: translateY(10px);">Loading complete. Thank you for your patience!</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const lines = [
'[1/20] Initializing UI components...',
  '[2/20] Fetching templates...',
  '[3/20] Analyzing requirements...',
  '[4/20] Generating component structure...',
  '[5/20] Applying styles and themes...',
  '[6/20] Optimizing for performance...',
  '[7/20] Integrating with backend...',
  '[8/20] Running tests...',
  '[9/20] Verifying results...',
  '[10/20] Finalizing components...',
  '[11/20] Preparing assets...',
  '[12/20] Compiling resources...',
  '[13/20] Reviewing code...',
  '[14/20] Monitoring performance...',
  '[15/20] Gathering feedback...',
  '[16/20] Refining design...',
  '[17/20] Updating documentation...',
  '[18/20] Conducting final review...',
  '[19/20] Preparing for deployment...',
  '[20/20] Deployment complete!',
  'UI components created successfully in 09.45s.'
];

const scrollContainer = ref(null);
const finalMessage = ref(null);

onMounted(() => {
  let delay = 0;
  const interval = 1000; // 0.5 second interval
  lines.forEach((line, index) => {
    setTimeout(() => {
      const lineElement = scrollContainer.value.children[index];
      lineElement.style.opacity = 1;
      lineElement.style.transform = 'translateY(0)';
      // Auto-scroll to the bottom
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }, delay);
    delay += interval;
  });

  // Show final message after all lines are displayed
  setTimeout(() => {
    finalMessage.value.style.opacity = 1;
    finalMessage.value.style.transform = 'translateY(0)';
    // Auto-scroll to the bottom
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
  }, delay + 2000); // Additional delay for final message
});
</script>

<style scoped>
@keyframes showLine {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-line {
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}

.scroll-container {
  overflow-y: auto;
  scroll-behavior: smooth;
}
</style>