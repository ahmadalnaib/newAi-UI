<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/20/solid";
import Pagination from "@/Components/Pagination.vue";
import { computed } from "vue";

const pros=defineProps({
    tailwindCode: {
        type: Object,
        required: true,
    },
    isSubscribed: {
        type: Boolean,
        required: true,
    },
});

const form = useForm({
    tailwind: "",
});

const confirmDelete = (slug) => {
    if (confirm("Are you sure you want to delete this code?")) {
        form.delete(route("ui.destroy", slug), {
            onSuccess: () => alert("Code deleted successfully!"),
        });
    }
};

const formatDateTime = (dateTime) => {
    const date = new Date(dateTime);
    const formattedDate = date.toLocaleDateString();
    const formattedTime = date.toLocaleTimeString();
    return `${formattedDate} ${formattedTime}`;
};

const groupByDate = computed(() => {
    return pros.tailwindCode.data.reduce((acc, code) => {
        const date = new Date(code.created_at).toLocaleDateString();
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(code);
        return acc;
    }, {});
});
</script>

<template>
    <Head title="My UI" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold leading-tight text-gray-800">
                    My UI Componets 
                </h2>
                <Link
                   v-if="isSubscribed"
                
                    class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
                    href="/ui/create"
                >
                    Create New Component
                </Link>
            
                <Link
                    v-else
                    class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900"
                    :href="route('membership.index')"
                >
                    Subscribe to Create UI
                </Link>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Check if there are codes -->
                <div v-if="tailwindCode.data.length === 0" class="flex flex-col items-center justify-center h-full py-12">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">No Components Available</h2>
        <p class="text-gray-500 mb-6">It looks like you don't have any Components yet. Start creating your first Components now!</p>
        <Link href="/ui/create" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition-all">
            Create New Component
        </Link>
    </div>
</div>
                <div v-else>
                    <div v-for="(codes, date) in groupByDate" :key="date" class="mb-6">
                        <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/20">{{ date }}</span>
                        <ul role="list" class="divide-y divide-gray-100 bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl">
                            <li
                                v-for="code in codes"
                                :key="code.id"
                                class="flex items-center justify-between gap-x-6 px-5 py-5"
                            >
                                <div class="min-w-0">
                                    <div class="flex items-start gap-x-3">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ code.title }}
                                        </p>
                                    </div>
                                    <div class="mt-1 flex items-center gap-x-2 text-xs text-gray-500">
                                        <p class="whitespace-nowrap">
                                            Created on {{ formatDateTime(code.created_at) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-none items-center gap-x-4">
                                    <Link
                                        :href="`/ui/${code.slug}`"
                                        class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block"
                                    >
                                        View Component
                                    </Link>
                                    <Menu as="div" class="relative flex-none">
                                        <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
                                            <span class="sr-only">Open options</span>
                                            <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
                                        </MenuButton>
                                        <transition
                                            enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95"
                                        >
                                            <MenuItems class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                                <MenuItem v-slot="{ active }">
                                                    <Link
                                                        :href="`/ui/${code.slug}`"
                                                        :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm text-gray-900']"
                                                    >
                                                        View
                                                    </Link>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button
                                                        @click="confirmDelete(code.slug)"
                                                        :class="[active ? 'bg-gray-50' : '', 'block w-full text-left px-3 py-1 text-sm text-gray-900']"
                                                    >
                                                        Delete
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Pagination -->
                <div v-if="tailwindCode.links.length > 1">
                    <Pagination :links="tailwindCode.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>