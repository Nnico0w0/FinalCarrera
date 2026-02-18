<script setup>
import { Link } from '@inertiajs/vue3';
import UserLayouts from './Layouts/UserLayouts.vue';

defineProps({
    topProducts: Array
})
</script>

<template>
    <UserLayouts>
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl sm:text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Productos Destacados de TecnoSector</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Descubre nuestros productos más populares en equipos electrónicos. Tecnología de calidad a precios accesibles.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl rounded-3xl ring-1 ring-gray-200 sm:mt-20 lg:mx-0 lg:flex lg:max-w-none">
                    <div v-if="topProducts && topProducts.length > 0" class="grid grid-cols-1 gap-8 lg:grid-cols-3 w-full">
                        <div v-for="(product, index) in topProducts" :key="product.id" 
                            class="rounded-3xl p-8 ring-1 ring-gray-200 xl:p-10"
                            :class="{ 'bg-gray-50': index === 1 }">
                            <div class="flex items-center justify-between gap-x-4">
                                <h3 class="text-lg font-semibold leading-8 text-gray-900">
                                    {{ product.title }}
                                </h3>
                                <p v-if="index === 1" class="rounded-full bg-indigo-600/10 px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600">
                                    Most popular
                                </p>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-gray-600">{{ product.description }}</p>
                            <p class="mt-6 flex items-baseline gap-x-1">
                                <span class="text-4xl font-bold tracking-tight text-gray-900">${{ product.price }}</span>
                                <span class="text-sm font-semibold leading-6 text-gray-600"></span>
                            </p>
                            <div v-if="product.product_images && product.product_images.length > 0" class="mt-6">
                                <img :src="product.product_images[0].image" :alt="product.title" 
                                    class="w-full h-48 object-cover rounded-lg" />
                            </div>
                            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                                <li class="flex gap-x-3">
                                    <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                    {{ product.brand ? product.brand.name : 'Premium' }} Brand
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                    {{ product.quantity }} unidades disponibles
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                    Garantía extendida
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                    Envío gratuito
                                </li>
                            </ul>
                            <Link :href="route('products.index')"
                                :class="[index === 1 ? 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' : 'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300', 'mt-8 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">
                            Comprar ahora
                            </Link>
                        </div>
                    </div>
                    <div v-else class="w-full text-center py-12">
                        <p class="text-gray-500">No products available at the moment.</p>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <Link :href="route('products.index')"
                        class="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                    Ver todos los productos <span aria-hidden="true">→</span>
                    </Link>
                </div>
            </div>
        </div>
    </UserLayouts>
</template>
