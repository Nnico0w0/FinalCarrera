<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Header from './Header.vue';
import Footer from './Footer.vue';
import Hero from './Hero.vue';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})



</script>
<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900">
        <Header />
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>
</template>
