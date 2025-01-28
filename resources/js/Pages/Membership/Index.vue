<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { PaperClipIcon } from '@heroicons/vue/20/solid';

defineProps({
    isSubscribed: {
        type: Boolean,
        required: true,
    },
    plan: {
        type: Object,
        required: true,
    },
    upcoming: {
        type: Object,
        required: true,
    },
    isCancelled: {
        type: Boolean,
        required: true,
    },
    end_at: {
        type: String,
        required: true,
    },
    diffHuman: {
        type: String,
        required: true,
    },
    invoices: {
        type: Array,
        required: true,
    },
    paymentNotSuccessful: {
        type: Boolean,
        required: true,
    },
});

const form = useForm({});


</script>

<template>
    <Head title="Subscription" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                Subscription
            </h2>
            <p class="text-2xl font-semibold text-gray-400">
                These are the details of your subscription
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-6">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-300">
                    <div class="p-8 space-y-8">
                        <!-- Incomplete Payment -->
                        <div v-if="paymentNotSuccessful" class="text-center bg-red-100 p-4 rounded-lg">
                            <p class="text-bold text-2xl text-red-600 mb-5">Your Payment is failing.</p>
                            <a class="text-white bg-red-600 px-4 py-2 rounded" href="/membership/portal">Update Card</a>
                        </div>

                        <!-- Subscription Status Section -->
                        <div v-if="!isSubscribed" class="text-center space-y-4 bg-gray-900 p-4 rounded-lg">
                            <p class="text-2xl font-bold text-white">You are not subscribed yet.</p>
                            <p class="text-lg text-white">Subscribe to unlock premium content and features.</p>
                            <Link href="/plans" class="inline-block bg-white text-gray-900 text-lg py-3 px-8 rounded-full shadow  transition-all">
                                Subscribe Now
                            </Link>
                        </div>

                        <!-- Subscribed Section -->
                        <div v-else>
                            <div class="bg-gray-50 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                <dt class="text-sm font-medium text-gray-900">Plan Name</dt>
                                <dd class="mt-1 text-sm text-gray-700 sm:col-span-2 sm:mt-0">{{ plan.planName }}</dd>
                            </div>
                            <div class="bg-white px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                <dt class="text-sm font-medium text-gray-900">Subscription Status</dt>
                                <dd class="mt-1 text-sm text-gray-700 sm:col-span-2 sm:mt-0">
                                    <span v-if="isCancelled">Cancelled (Ends: {{ end_at }} - {{ diffHuman }})</span>
                                    <span v-else>Active</span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                <dt class="text-sm font-medium text-gray-900">Upcoming Payment</dt>
                                <dd class="mt-1 text-sm text-gray-700 sm:col-span-2 sm:mt-0">
                                    <div v-if="upcoming && !isCancelled">
                                        Renewal Date: {{ upcoming.date }} ({{ upcoming.diff }})<br>
                                        Next charge: {{ upcoming.total }}
                                    </div>
                                    <div v-else>
                                        No upcoming payments.
                                    </div>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                <dt class="text-sm font-medium text-gray-900">Invoices</dt>
                                <dd class="mt-1 text-sm text-gray-700 sm:col-span-2 sm:mt-0">
                                    <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                        <li v-for="invoice in invoices" :key="invoice.id" class="flex items-center justify-between py-4 pl-4 pr-5 text-sm">
                                            <div class="flex w-0 flex-1 items-center">
                                                <PaperClipIcon class="size-5 shrink-0 text-gray-400" aria-hidden="true" />
                                                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                    <span class="truncate font-medium">{{ invoice.date }} - {{ invoice.total }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4 shrink-0">
                                                <a :href="`/membership/invoice?invoice=${invoice.id}`" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                            </div>
                                        </li>
                                    </ul>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                <dt class="text-sm font-medium text-gray-900">Manage Subscription</dt>
                                <dd class="mt-1 text-sm text-gray-700 sm:col-span-2 sm:mt-0">
                                  
                                    <div class="text-center mt-8">
                                        <a href="/membership/portal" class="inline-block bg-gray-900 text-white text-lg py-3 px-8 rounded-full shadow hover:bg-gray-700 transition-all">
                                            Manage Subscription
                                        </a>
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Add any additional styling here */
</style>