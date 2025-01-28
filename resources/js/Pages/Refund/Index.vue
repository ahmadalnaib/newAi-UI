<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Refund Policy" />
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-lg space-y-8">
        <Link :href="route('home')" class="text-blue-500 hover:underline mb-4 inline-block">
            <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Website
        </Link>
        <h1 class="text-4xl font-extrabold mb-6 text-gray-800">Refund Policy</h1>
        <div class="prose lg:prose-xl space-y-6">
            <p>We understand the importance of transparency in our pricing and refund policies. To ensure fairness, we offer a full refund if you cancel your subscription within the first 5 days of purchase.</p>
            
            <p>To request a refund within this period, please contact our support team. You can email us at <a href="mailto:info@cheapui.com" class="text-blue-500 hover:underline">info@cheapui.com</a>. Refunds will be processed promptly and issued to the original payment method.</p>
            
            <p>After the initial 5-day period, we regret to inform you that we are unable to offer refunds due to the high operational costs of our business. However, you are free to cancel your subscription at any time. Once canceled, no further charges will be incurred, and the subscription will cease immediately without any future obligations.</p>
            
            <p>We appreciate your trust and investment in our services and strive to provide exceptional value and ongoing support. For any questions or comments regarding the refund policy or any other aspect of our services, please do not hesitate to contact us at <a href="mailto:info@cheapui.com" class="text-blue-500 hover:underline">info@cheapui.com</a>.</p>
        </div>
    </div>
</template>
