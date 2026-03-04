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
        ? 'block w-full pl-4 pr-4 py-2 border-l-4 border-violet-400 text-left text-base font-semibold text-white bg-white/10 transition duration-150 ease-in-out'
        : 'block w-full pl-4 pr-4 py-2 border-l-4 border-transparent text-left text-base font-semibold text-white/70 hover:text-white hover:bg-white/5 hover:border-white/30 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
