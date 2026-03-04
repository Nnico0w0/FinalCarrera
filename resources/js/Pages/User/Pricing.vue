<script setup>
import { Link } from '@inertiajs/vue3';
import UserLayouts from './Layouts/UserLayouts.vue';

defineProps({
    topProducts: Array
})
</script>

<template>
    <UserLayouts>
        <section class="px-6 py-20">
            <div class="mx-auto max-w-6xl">
                <div class="text-center">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/50">Bundles TecnoSector</p>
                    <h1 class="mt-4 text-4xl font-semibold">Combos calibrados para cada flujo de trabajo</h1>
                    <p class="mx-auto mt-4 max-w-2xl text-white/70">
                        Seleccionamos laptops, wearables y accesorios con garantía y soporte premium. Elegí el set que mejor se adapte a tu ritmo profesional o creativo.
                    </p>
                </div>

                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    <template v-if="topProducts && topProducts.length">
                        <article v-for="(product, index) in topProducts" :key="product.id"
                            class="ts-card flex flex-col gap-5 p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">
                                        {{ product.brand ? product.brand.name : 'Colección' }}
                                    </p>
                                    <h2 class="mt-2 text-2xl font-semibold">{{ product.title }}</h2>
                                </div>
                                <span v-if="index === 1"
                                    class="rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-100">
                                    Más vendido
                                </span>
                            </div>
                            <p class="text-sm text-white/70">{{ product.description }}</p>
                            <div class="h-40 w-full overflow-hidden rounded-2xl bg-white/5">
                                <img v-if="product.product_images && product.product_images.length > 0"
                                    :src="product.product_images[0].image" :alt="product.title"
                                    class="h-full w-full object-cover object-center" />
                                <div v-else class="flex h-full items-center justify-center text-white/40">
                                    Sin imagen
                                </div>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-semibold text-white">${{ product.price }}</span>
                                <span class="text-sm text-white/50">precio miembro</span>
                            </div>
                            <ul class="space-y-3 text-sm text-white/75">
                                <li>• {{ product.quantity }} unidades disponibles en bodega.</li>
                                <li>• Garantía extendida + cobertura accidental.</li>
                                <li>• Envío express y onboarding remoto.</li>
                            </ul>
                            <Link :href="route('products.index')"
                                class="ts-btn-primary text-center text-sm">
                                Reservar bundle
                            </Link>
                        </article>
                    </template>
                    <div v-else class="ts-card flex items-center justify-center p-12 text-white/60">
                        No hay bundles disponibles por el momento.
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <Link :href="route('products.index')"
                        class="inline-flex items-center text-sm font-semibold text-white/70 transition hover:text-white">
                        Ver catálogo completo
                        <span class="ml-2" aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>
