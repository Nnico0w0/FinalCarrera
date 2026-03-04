<template>
    <div class="ts-shell min-h-screen antialiased">
        <!-- navbar start -->
        <Navbar />
        <!-- end -->
        <!-- Sidebar -->
        <Sidebar />
        <!-- end -->
        <main class="min-h-screen p-4 pt-20 md:ml-64">

            <slot />
        </main>

    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>