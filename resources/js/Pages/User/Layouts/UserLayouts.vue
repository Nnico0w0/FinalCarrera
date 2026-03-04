<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Header from './Header.vue';
import Footer from './Footer.vue';

onMounted(() => {
    initFlowbite();
})
</script>
<template>
    <div class="ts-shell flex flex-col min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 opacity-70" aria-hidden="true">
            <div class="absolute -top-32 -left-40 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/40 via-purple-500/30 to-cyan-500/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-[28rem] w-[28rem] translate-x-1/3 translate-y-1/3 rounded-full bg-gradient-to-br from-pink-500/40 via-fuchsia-500/20 to-indigo-600/20 blur-3xl"></div>
        </div>
        <div class="relative z-10 flex flex-col min-h-screen">
            <Header />
            <main class="flex-1">
                <slot />
            </main>
            <Footer />
        </div>
    </div>
</template>
