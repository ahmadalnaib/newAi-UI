<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, nextTick, computed } from "vue";
import Header from "@/Components/Header.vue";
import FeatureSection from "@/Components/FeatureSection.vue";
import LoadingCard from "@/Components/LoadingCard.vue";
import CodeMirror from "@/Components/CodeMirror.vue";
import { CodeBracketIcon, EyeIcon, CheckIcon } from "@heroicons/vue/24/outline";

const prompt = ref("");
const showForm = ref(true);
const loading = ref(false);
const pageShow = ref(false);
const activeTab = ref("preview");

const props = defineProps({
    componentFromAi: String,
    frameworkTypes: Array,
    elementTypes: Array,
    canLogin: Boolean,
    canRegister: Boolean,
    plans: {
        type: Array,
        required: true,
    },
    hasAccess: {
        type: Boolean,
        required: true,
    },
});

const form = useForm({
    prompt: "",
    elementType: "",
    frameworkType: "Tailwind CSS",
});

const showAllTypes = ref(false);

const displayedTypes = computed(() => {
    if (!props.elementTypes) return [];
    return showAllTypes.value
        ? props.elementTypes
        : props.elementTypes.slice(0, 5);
});

const toggleShowAll = () => {
    showAllTypes.value = !showAllTypes.value;
};

const generateWithFreeAI = () => {
    loading.value = true;
    showForm.value = false;
    form.post("/", {
        onSuccess: async () => {
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
                    ${props.componentFromAi}
                </body>
            </html>
        `;
    }
};

watch(activeTab, async (newTab) => {
    if (newTab === "preview") {
        await nextTick();
        updateIframeContent();
    }
});
</script>
<template>
    <Head>
        <title>
            CheapUI - AI-Powered Website Component Generator | Fast & Affordable
        </title>
        <meta
            name="description"
            content="Create stunning website components effortlessly with CheapUI. Generate AI-powered headers, navbars, footers, and more in seconds. Affordable, fast, and perfect for developers and designers."
            head-key="description"
        />
        <meta
            name="keywords"
            content="AI website components, generate website parts, AI navbar generator, AI header generator, cheap UI components, affordable web design tools, AI web development, create website components, fast UI generator, website builder tools"
            head-key="keywords"
        />
    </Head>
    <div class="bg-gray-900">
        <Header :canLogin="canLogin" :canRegister="canRegister" />
        <!-- Hero Section -->
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <!-- Top Gradient -->
            <div
                class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true"
            >
                <div
                    class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="
                        clip-path: polygon(
                            74.1% 44.1%,
                            100% 61.6%,
                            97.5% 26.9%,
                            85.5% 0.1%,
                            80.7% 2%,
                            72.5% 32.5%,
                            60.2% 62.4%,
                            52.4% 68.1%,
                            47.5% 58.3%,
                            45.2% 34.5%,
                            27.5% 76.7%,
                            0.1% 64.9%,
                            17.9% 100%,
                            27.6% 76.8%,
                            76.1% 97.7%,
                            74.1% 44.1%
                        );
                    "
                />
            </div>
            <!-- Main Content -->
            <div class="py-24 sm:py-32 lg:pb-40">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <!-- Hero Text -->
                    <div class="mx-auto max-w-2xl text-center">
                        <h1
                            class="text-balance text-5xl font-semibold tracking-tight text-white sm:text-7xl"
                        >
                            Design Smarter Build Faster
                        </h1>
                        <p
                            class="mt-8 text-pretty text-lg font-medium text-white sm:text-xl/8"
                        >
                            Design smarter, build faster, and let AI elevate
                            your web components to the next level.
                        </p>
                        <div
                            class="mt-10 flex items-center justify-center gap-x-6"
                        >
                            <Link
                                href="/register"
                                class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-dark-900 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
                            >
                                Get started
                            </Link>
                            <a
                                href="#feature"
                                class="text-sm/6 font-semibold text-white"
                            >
                                Learn more <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                    <div class="mt-20" v-if="props.hasAccess">
                        <div
                            class="container mx-auto p-8 bg-white m-5 rounded-lg"
                            v-if="showForm"
                        >
                            <form
                                @submit.prevent="generateWithFreeAI"
                                class="mb-8 space-y-6"
                            >
                                <div class="flex items-center space-x-6 mb-14">
                                    <input
                                        v-model="form.prompt"
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
                                        type="submit"
                                        v-for="type in displayedTypes"
                                        :key="type.label"
                                        @click="form.elementType = type.label"
                                        :class="{
                                            'border border-solid text-dark p-4 rounded-lg transition': true,
                                            'bg-gray-900 text-white':
                                                form.elementType === type.label,
                                        }"
                                    >
                                        <div class="flex">
                                            <span
                                                v-html="type.icon"
                                                class="mr-2"
                                            ></span>
                                            <span> {{ type.label }}</span>
                                        </div>
                                    </button>
                                    <!-- See More / See Less button -->
                                    <button
                                        type="button"
                                        v-if="
                                            props.elementTypes &&
                                            props.elementTypes.length > 5
                                        "
                                        @click.prevent="toggleShowAll"
                                        class="p-4 rounded-lg text-blue-500 cursor-pointer"
                                    >
                                        {{ showAllTypes ? "Less" : "More" }}
                                    </button>
                                </div>
                            </form>
                            <div></div>
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
                    <div v-if="pageShow" class="bg-white p-8 rounded-lg">
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
                        </div>
                        <!-- Tab Panels -->
                        <div class="transition-all duration-300">
                            <div v-if="activeTab === 'code'" class="space-y-8">
                                <div>
                                    <CodeMirror
                                        id="tailwind"
                                        v-model="props.componentFromAi"
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
                            <div
                                v-if="activeTab === 'preview'"
                                class="space-y-8"
                            >
                                <iframe
                                    ref="previewIframe"
                                    class="w-full editor border border-gray-300 rounded-lg overflow-auto"
                                ></iframe>
                                <div class="flex justify-end"></div>
                            </div>
                        </div>
                    </div>
                 
       <div v-if="!pageShow && !props.hasAccess">
                        <div class="mt-16 flow-root sm:mt-24">
                            <div
                                class="-m-2 rounded-xl bg-gray-900/5 p-2 ring-1 ring-inset ring-gray-900/10 lg:-m-4 lg:rounded-2xl lg:p-4"
                            >
                                <video
                                    src="/video/showcase.mp4"
                                    autoplay
                                    muted
                                    loop
                                    width="2432"
                                    height="1442"
                                    class="rounded-md shadow-2xl ring-1 ring-gray-900/10"
                                ></video>
                            </div>
                        </div>
                    </div>





                </div>
            </div>
        </div>
    </div>

    <feature-section id="feature" />

    <div
        id="plans"
        class="relative isolate bg-gray-900 px-6 py-24 sm:py-32 lg:px-8"
    >
        <div
            class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl"
            aria-hidden="true"
        >
            <div
                class="mx-auto aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
                style="
                    clip-path: polygon(
                        74.1% 44.1%,
                        100% 61.6%,
                        97.5% 26.9%,
                        85.5% 0.1%,
                        80.7% 2%,
                        72.5% 32.5%,
                        60.2% 62.4%,
                        52.4% 68.1%,
                        47.5% 58.3%,
                        45.2% 34.5%,
                        27.5% 76.7%,
                        0.1% 64.9%,
                        17.9% 100%,
                        27.6% 76.8%,
                        76.1% 97.7%,
                        74.1% 44.1%
                    );
                "
            />
        </div>
        <div class="mx-auto max-w-7xl text-center">
            <h2 class="text-base font-semibold text-white">Pricing</h2>
            <p
                class="mt-2 text-5xl font-semibold tracking-tight text-white sm:text-6xl"
            >
                Take flight with CheapUI
            </p>
        </div>
        <div class="mx-auto mt-16 flex flex-col items-center ">
            <div
                class="relative max-w-md w-full rounded-lg p-6 ring-1 ring-gray-200 sm:p-8 bg-white shadow-md hover:shadow-xl transition-shadow duration-300 "
            >
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    class="relative rounded-lg p-6 ring-1 ring-gray-200 sm:p-8 bg-white shadow-md hover:shadow-xl transition-shadow duration-300 "
                >
                    <h3 class="text-dark text-2xl font-bold text-center">
                        {{ plan.name }}
                    </h3>
                    <p
                        class="mt-4 text-4xl font-extrabold text-gray-900 text-center"
                    >
                        {{ plan.price }} €
                    </p>
                    <ul
                        class="mt-6 space-y-2 text-gray-900 flex flex-col justify-center "
                    >
                        <li class="flex items-center gap-x-2 ">
                            <CheckIcon
                                class="text-indigo-600 h-5 w-5 flex-none"
                                aria-hidden="true"
                            />
                            <span>Monthly Request Unlimited</span>
                        </li>
                        <li class="flex items-center gap-x-2">
                            <CheckIcon
                                class="text-indigo-600 h-5 w-5 flex-none"
                                aria-hidden="true"
                            />
                            <span>New Feature Early Access</span>
                        </li>
                        <li class="flex items-center gap-x-2">
                            <CheckIcon
                                class="text-indigo-600 h-5 w-5 flex-none"
                                aria-hidden="true"
                            />
                            <span>Customer Support</span>
                        </li>
                        <li class="flex items-center gap-x-2">
                            <CheckIcon
                                class="text-indigo-600 h-5 w-5 flex-none"
                                aria-hidden="true"
                            />
                            <span>Priority Bug Fixes</span>
                        </li>
                        <li class="flex items-center gap-x-2">
                            <CheckIcon
                                class="text-indigo-600 h-5 w-5 flex-none"
                                aria-hidden="true"
                            />
                            <span>Access to Beta Features</span>
                        </li>
                    </ul>
                    <a
                        :href="`/checkout?plan_id=${plan.id}`"
                        class="mt-6 block w-full rounded-md bg-gray-900 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
                        >Get started today</a
                    >
                </div>
            </div>
        </div>
    </div>
    <section class="py-24 bg-gray-900">
        <div class="max-w-3xl px-8 mx-auto lg:px-16">
            <h2 class="mb-2 text-xl font-bold text-white md:text-3xl">
                Frequently Asked Questions
            </h2>
            <div class="relative mt-8">
                <!-- Question 1 -->
                <div class="relative overflow-hidden text-white">
                    <h4
                        class="flex items-center justify-between py-5 text-lg font-medium text-gray-100 sm:text-xl hover:text-white"
                    >
                        <span>What is CheapUI?</span>
                        <svg
                            class="w-6 h-6 text-indigo-500 transition-all duration-200 ease-out transform rotate-0 fill-current"
                            data-primary="green-500"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            ></path>
                        </svg>
                    </h4>
                    <p
                        class="px-1 pt-0 mt-1 text-gray-300 sm:text-lg py-7"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform -translate-y-0"
                        x-transition:leave="transition-all ease-out hidden duration-200"
                        x-transition:leave-start="opacity-100 transform -translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak=""
                    >
                        CheapUI is an AI-powered platform that helps you design
                        modern, professional UI components quickly and
                        affordably. Whether you need responsive elements or
                        custom designs, CheapUI provides tailored solutions to
                        elevate your projects.
                    </p>
                </div>
                <!-- Question 2 -->
                <div class="relative overflow-hidden text-white">
                    <h4
                        class="flex items-center justify-between py-5 text-lg font-medium text-gray-100 sm:text-xl hover:text-white"
                    >
                        <span>How does CheapUI work?</span>
                        <svg
                            class="w-6 h-6 text-indigo-500 transition-all duration-200 ease-out transform rotate-0 fill-current"
                            data-primary="green-500"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            ></path>
                        </svg>
                    </h4>
                    <p
                        class="px-1 pt-0 mt-1 text-gray-300 sm:text-lg py-7"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform -translate-y-0"
                        x-transition:leave="transition-all ease-out hidden duration-200"
                        x-transition:leave-start="opacity-100 transform -translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak=""
                    >
                        Simply provide your design requirements, such as
                        component type, framework preference, and specific
                        styles. CheapUI’s AI generates clean, responsive, and
                        professional code that’s ready to integrate into your
                        project.
                    </p>
                </div>
                <!-- Question 3 -->
                <div class="relative overflow-hidden text-white">
                    <h4
                        class="flex items-center justify-between py-5 text-lg font-medium text-gray-100 sm:text-xl hover:text-white"
                    >
                        <span>Who is CheapUI for</span>
                        <svg
                            class="w-6 h-6 text-indigo-500 transition-all duration-200 ease-out transform rotate-0 fill-current"
                            data-primary="green-500"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            ></path>
                        </svg>
                    </h4>
                    <p
                        class="px-1 pt-0 mt-1 text-gray-300 sm:text-lg py-7"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform -translate-y-0"
                        x-transition:leave="transition-all ease-out hidden duration-200"
                        x-transition:leave-start="opacity-100 transform -translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak=""
                    >
                        CheapUI is perfect for developers, designers, and
                        startups looking to save time and cost on building
                        modern UI components. Whether you're a seasoned
                        professional or just starting out, CheapUI simplifies
                        the design process for everyone.
                    </p>
                </div>
                <!-- Question 4 -->
                <div class="relative overflow-hidden text-white">
                    <h4
                        class="flex items-center justify-between py-5 text-lg font-medium text-gray-100 sm:text-xl hover:text-white"
                    >
                        <span>Why choose CheapUI?</span>
                        <svg
                            class="w-6 h-6 text-indigo-500 transition-all duration-200 ease-out transform rotate-0 fill-current"
                            data-primary="green-500"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            ></path>
                        </svg>
                    </h4>
                    <p
                        class="px-1 pt-0 mt-1 text-gray-300 sm:text-lg py-7"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform -translate-y-0"
                        x-transition:leave="transition-all ease-out hidden duration-200"
                        x-transition:leave-start="opacity-100 transform -translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        x-cloak=""
                    >
                        CheapUI combines AI precision with design best practices
                        to deliver top-quality components that are responsive,
                        customizable, and aligned with modern UI trends—all at
                        an affordable price.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-gray-900">
        <div
            class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8"
        >
            <nav
                class="-mb-6 flex flex-wrap justify-center gap-x-12 gap-y-3 text-sm/6"
                aria-label="Footer"
            >
                <Link href="/privacy" class="text-white hover:text-white">
                    Privacy Policy
                </Link>
                <Link href="/terms" class="text-white hover:text-white">
                    Terms of Use
                </Link>
                <Link href="/refund" class="text-white hover:text-white">
                    Refund Policy</Link
                >
            </nav>
            <div class="mt-16 flex justify-center gap-x-10">
                <a
                    target="_blank"
                    href="https://x.com/Ahmad_Al_Naib"
                    class="text-gray-400 hover:text-gray-300"
                >
                    <span class="sr-only">X</span>
                    <svg
                        class="size-6"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z"
                        />
                    </svg>
                </a>
                <a
                    target="_blank"
                    href="https://github.com/ahmadalnaib"
                    class="text-gray-400 hover:text-gray-300"
                >
                    <span class="sr-only">GitHub</span>
                    <svg
                        class="size-6"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </a>
            </div>
            <p class="mt-10 text-center text-sm/6 text-gray-400">
                &copy; 2025 CheapUI, Inc. All rights reserved.
            </p>
        </div>
    </footer>
</template>
<style scoped>
.container {
    max-width: 1400px;
}
.editor {
    height: 800px;
}
</style>
