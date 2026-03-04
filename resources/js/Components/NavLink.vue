<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-3 py-2 border-b-2 border-violet-400 text-sm font-semibold leading-5 text-white transition duration-150 ease-in-out'
        : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-semibold leading-5 text-white/60 hover:text-white hover:border-white/30 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
