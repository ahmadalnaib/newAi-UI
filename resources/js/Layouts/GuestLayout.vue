<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";


</script>

<template>
   <Head >
        <title>CheapUi</title>
        <meta name="description" content="CheapUi is ..."  head-key="description"/>
   
    </Head>

    <div>
        <!-- <Header :canLogin="canLogin" :canRegister="canRegister" /> -->
        <slot  />
    </div>
</template>
