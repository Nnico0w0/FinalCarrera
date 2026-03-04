<script setup>
import { computed, reactive } from 'vue'

import UserLayouts from './Layouts/UserLayouts.vue';
import { router, usePage } from '@inertiajs/vue3';

defineProps({
    cart: Object,
    userAddress: Object
})

const page = usePage()
const cartItems = computed(() => page.props.cart?.data?.items ?? [])
const products = computed(() => page.props.cart?.data?.products ?? [])

const cartIndex = computed(() => {
    const source = cartItems.value
    if (Array.isArray(source)) {
        return source.reduce((acc, item) => {
            acc[item.product_id] = item
            return acc
        }, {})
    }
    return source ?? {}
})

const getQuantity = (productId) => cartIndex.value?.[productId]?.quantity ?? 1
const getStockLimit = (product) => {
    const stock = Number(product?.quantity)
    if (!Number.isFinite(stock) || stock <= 0) {
        return 1
    }
    return stock
}

const cartTotal = computed(() => products.value.reduce((carry, product) => {
    const price = Number(product.price) || 0
    const quantity = getQuantity(product.id)
    return carry + price * quantity
}, 0))

const formatCurrency = (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value || 0)
const formattedTotal = computed(() => formatCurrency(cartTotal.value))

const form = reactive({
    address1: null,
    state: null,
    city: null,
    zipcode: null,
    country_code: null,
    type: null,

})
const formFilled = computed(()=>{
   return (form.address1 !== null &&
    form.state !== null &&
    form.city !== null &&
    form.zipcode !== null &&
    form.country_code !== null &&
    form.type !== null )
})



const updateQuantity = (product, quantity) => {
    const max = getStockLimit(product)
    const safeQuantity = Math.max(1, Math.min(Number(quantity) || 1, max))
    if (safeQuantity === getQuantity(product.id)) {
        return
    }
    router.patch(route('cart.update', product), {
        quantity: safeQuantity,
        preserveScroll: true,
    })
}

const decrease = (product) => updateQuantity(product, getQuantity(product.id) - 1)
const increase = (product) => updateQuantity(product, getQuantity(product.id) + 1)
const handleQuantityInput = (product, event) => {
    const value = Number(event.target.value)
    updateQuantity(product, Number.isNaN(value) ? 1 : value)
}
const canDecrease = (product) => getQuantity(product.id) > 1
const canIncrease = (product) => getQuantity(product.id) < getStockLimit(product)
//remove form cart 
const remove = (product) => router.delete(route('cart.delete', product));


//confirm order 

function submit() {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: page.props.cart.data.items,
            products: page.props.cart.data.products,
            total: cartTotal.value,
            address: form
        }
    })
}





</script>
<template>
    <UserLayouts>
        <section class="px-6 py-16">
            <div class="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[1.6fr,0.9fr]">
                <div class="ts-card overflow-hidden p-6">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Carrito</p>
                            <h2 class="text-2xl font-semibold">Revisa tus equipos</h2>
                        </div>
                        <span class="rounded-full border border-white/10 px-4 py-1 text-sm text-white/70">
                            {{ products.length }} productos
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="mt-6 w-full text-left text-sm text-white/80">
                            <thead class="text-xs uppercase tracking-[0.3em] text-white/50">
                                <tr>
                                    <th class="py-3">Producto</th>
                                    <th class="py-3">Cantidad</th>
                                    <th class="py-3">Precio</th>
                                    <th class="py-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="product in products" :key="product.id" class="align-top">
                                    <td class="py-4">
                                        <div class="flex items-center gap-4">
                                            <img v-if="product.product_images.length > 0"
                                                :src="`/${product.product_images[0].image}`" alt="product"
                                                class="h-20 w-20 rounded-2xl object-cover" />
                                            <img v-else
                                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                                alt="placeholder" class="h-20 w-20 rounded-2xl object-cover" />
                                            <div>
                                                <p class="text-base font-semibold text-white">{{ product.title }}</p>
                                                <p class="text-xs text-white/50">{{ product.brand ? product.brand.name : 'TecnoSector' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <button @click.prevent="decrease(product)" :disabled="!canDecrease(product)"
                                                :class="[canDecrease(product) ? 'text-white' : 'text-white/30 cursor-not-allowed', 'inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 bg-white/5']">
                                                −
                                            </button>
                                            <input type="number" :value="getQuantity(product.id)"
                                                @change="handleQuantityInput(product, $event)" min="1"
                                                :max="getStockLimit(product)"
                                                class="quantity-input w-16 rounded-xl border border-white/20 bg-white/5 px-3 py-1 text-center text-sm text-white"
                                                required>
                                            <button @click.prevent="increase(product)" :disabled="!canIncrease(product)"
                                                :class="[canIncrease(product) ? 'text-white' : 'text-white/30 cursor-not-allowed', 'inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 bg-white/5']">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4 text-base font-semibold text-white">{{ formatCurrency(product.price) }}</td>
                                    <td class="py-4">
                                        <button @click="remove(product)" class="text-sm font-semibold text-rose-300 hover:text-rose-200">
                                            Quitar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="ts-card p-6">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Resumen</p>
                    <h2 class="mt-2 text-3xl font-semibold text-white">{{ formattedTotal }}</h2>
                    <p class="text-sm text-white/60">Incluye impuestos y envío local</p>

                    <div v-if="userAddress" class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/80">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/40">Dirección principal</p>
                        <p class="mt-2 font-semibold">{{ userAddress.address1 }}</p>
                        <p>{{ userAddress.city }} · {{ userAddress.zipcode }}</p>
                        <p class="text-white/60">Podés actualizarla aquí abajo.</p>
                    </div>
                    <p v-else class="mt-6 text-sm text-white/70">Añadí una dirección para habilitar la compra.</p>

                    <form @submit.prevent="submit" class="mt-6 space-y-4">
                        <div>
                            <label class="text-xs uppercase tracking-[0.35em] text-white/40">Dirección</label>
                            <input type="text" name="address1" v-model="form.address1" class="ts-input mt-2">
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-[0.35em] text-white/40">Ciudad</label>
                            <input type="text" name="city" v-model="form.city" class="ts-input mt-2">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-xs uppercase tracking-[0.35em] text-white/40">Provincia / Estado</label>
                                <input type="text" name="state" v-model="form.state" class="ts-input mt-2">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-[0.35em] text-white/40">Código postal</label>
                                <input type="text" name="zipcode" v-model="form.zipcode" class="ts-input mt-2">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-xs uppercase tracking-[0.35em] text-white/40">País</label>
                                <input type="text" name="countrycode" v-model="form.country_code" class="ts-input mt-2">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-[0.35em] text-white/40">Tipo de dirección</label>
                                <input type="text" name="type" v-model="form.type" class="ts-input mt-2">
                            </div>
                        </div>

                        <button v-if="formFilled || userAddress" type="submit" class="ts-btn-primary w-full text-center">
                            Confirmar compra
                        </button>
                        <button v-else type="submit"
                            class="w-full rounded-full border border-white/20 px-4 py-3 text-sm font-semibold text-white/50">
                            Añadí tu dirección
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>

<style scoped>
.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}
</style>