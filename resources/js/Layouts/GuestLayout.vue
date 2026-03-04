<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="ts-shell flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <div class="relative z-10 w-full max-w-md">
            <div class="mb-8 flex items-center gap-4 text-white/80">
                <Link href="/" class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10">
                    <ApplicationLogo class="h-10 w-10 fill-current text-white" />
                </Link>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-white/50">TecnoSector</p>
                    <p class="text-lg font-semibold">Portal seguro</p>
                </div>
            </div>
            <div class="ts-card px-8 py-10">
                <slot />
            </div>
        </div>
    </div>
</template>
