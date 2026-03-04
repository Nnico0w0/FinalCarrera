<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import UserLayouts from './Layouts/UserLayouts.vue';
import Hero from './Layouts/Hero.vue';
import Products from '../User/Components/Products.vue'

// products list from API
const products = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await window.axios.get('/api/v1/products?per_page=8');
        products.value = response.data.data.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching products:', error);
        loading.value = false;
    }
});

</script>
<template>
    <UserLayouts>
        <Hero />
        <section class="px-6 pb-24">
            <div class="mx-auto flex max-w-6xl flex-col gap-12">
                <div class="grid gap-6 lg:grid-cols-[1.3fr,0.7fr]">
                    <div class="ts-card p-8">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-white/40">Colección destacada</p>
                                <h2 class="mt-3 text-3xl font-semibold">Equipos curados por nuestro laboratorio</h2>
                            </div>
                            <span class="rounded-full border border-emerald-400/40 bg-emerald-400/10 px-4 py-1 text-xs font-semibold text-emerald-200">
                                Stock activo
                            </span>
                        </div>
                        <p class="mt-4 text-white/70">
                            Analizamos rendimiento térmico, autonomía y soporte del fabricante para sugerirte el hardware con mejor relación costo/innovación. Todo listo para entrega inmediata.
                        </p>

                        <div v-if="loading" class="flex h-40 items-center justify-center">
                            <p class="text-white/60">Cargando catálogo...</p>
                        </div>
                        <div v-else>
                            <Products :products="products" />
                        </div>
                        <div class="mt-8 flex flex-wrap justify-between gap-4 text-sm text-white/60">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-white/40">Entrega</p>
                                <p class="text-lg font-semibold">24-72 hrs local</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-white/40">Garantía</p>
                                <p class="text-lg font-semibold">12 meses extendida</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-white/40">Soporte</p>
                                <p class="text-lg font-semibold">Chat 24/7</p>
                            </div>
                        </div>
                    </div>

                    <div class="ts-card flex flex-col gap-6 p-8">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Programas</p>
                            <h3 class="mt-2 text-2xl font-semibold">Planes de actualización anual</h3>
                            <p class="mt-3 text-white/70">Renová tus dispositivos con recompra garantizada y financiación inteligente.</p>
                        </div>
                        <ul class="space-y-4 text-sm text-white/80">
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-rose-400"></span>
                                Plan canje (trade-in) de hasta 40% en créditos TecnoSector.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                                Auditoría de rendimiento y limpieza profunda incluida.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>
                                Acceso prioritario a lanzamientos limitados.
                            </li>
                        </ul>
                        <Link :href="route('pricing')" class="ts-btn-primary text-center text-sm">
                            Ver combos disponibles
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>

