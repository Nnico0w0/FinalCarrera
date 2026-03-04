<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-left text-sm font-semibold text-white/80 hover:bg-white/5 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
