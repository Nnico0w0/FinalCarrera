<script setup>
import { router } from '@inertiajs/vue3';

 defineProps({
    products:Array
 })

 const addToCart = (product) => {
    console.log(product)
    router.post(route('cart.store', product), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        },
    })
}
</script>
<template>
     <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        <article v-for="product in products" :key="product.id"
            class="ts-card group relative overflow-hidden p-4 sm:p-5">
            <div class="flex items-start gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ product.brand?.name || 'Tecno' }}</p>
                    <h3 class="mt-2 text-lg font-semibold text-white sm:text-xl">{{ product.title }}</h3>
                </div>
            </div>
            <p class="mt-3 text-sm text-white/70 line-clamp-2">{{ product.description }}</p>
            <div class="mt-4 h-40 w-full overflow-hidden rounded-2xl bg-white/5 sm:h-44">
                <img v-if="product.product_images.length > 0" :src="`/${product.product_images[0].image}`"
                    :alt="product.imageAlt" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
                <img v-else src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                    :alt="product.imageAlt" class="h-full w-full object-cover object-center" />
            </div>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-2xl font-semibold text-white">${{ product.price }}</p>
                <div class="flex w-full gap-2 sm:w-auto">
                    <button @click="addToCart(product)"
                        class="inline-flex flex-1 items-center justify-center rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/50 sm:flex-none">
                        Añadir
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>
            </div>
        </article>
    </div>
</template>