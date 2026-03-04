<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
     <section class="relative mx-auto flex max-w-6xl flex-col items-center gap-10 px-6 pb-20 pt-16 text-center text-white">
        <div class="ts-card relative overflow-hidden px-8 py-12">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 via-purple-500/15 to-sky-500/10"></div>
            <div class="relative">
                <div class="ts-pill inline-flex items-center gap-2 px-6 py-2 text-xs tracking-[0.35em]">
                    LÍNEA PRO 2026
                </div>
                <h1 class="mt-6 text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    Tecnología envolvente para crear, jugar y trabajar sin límites.
                </h1>
                <p class="mx-auto mt-6 max-w-3xl text-lg text-white/70">
                    Curamos hardware y accesorios de alto desempeño para que construyas ecosistemas digitales impecables: laptops, wearables y móviles con garantía local y soporte boutique.
                </p>
                <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <Link :href="route('pricing')" class="ts-btn-primary">
                        Ver planes y combos
                    </Link>
                    <Link :href="route('products.index')"
                        class="inline-flex items-center rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/50">
                        Explorar catálogo
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12l-3.75 3.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
