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
        <!-- here section  -->
        <Hero></Hero>
        <!-- end -->
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">List of products</h2>

                <!-- product list component -->
                <div v-if="loading" class="text-center py-8">
                    <p class="text-gray-500">Loading products...</p>
                </div>
                <Products v-else :products="products"></Products>
                <!-- end -->
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.index')"
                        class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    View All Products</Link>
                </div>


            </div>
        </div>
    </UserLayouts>
</template>

