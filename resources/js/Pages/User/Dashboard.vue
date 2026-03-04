<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import UserLayouts from './Layouts/UserLayouts.vue'

const props = defineProps({
    orders: {
        type: Array,
        default: () => []
    },
    profile: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    recentProducts: {
        type: Array,
        default: () => []
    }
})

const page = usePage()

const ordersList = computed(() => props.orders ?? [])
const statsData = computed(() => props.stats ?? {})
const recentProductsList = computed(() => props.recentProducts ?? [])

const profileCard = computed(() => {
    const fallback = page.props?.auth?.user ?? {}
    return {
        name: props.profile?.name ?? fallback.name ?? 'Tu cuenta',
        email: props.profile?.email ?? fallback.email ?? '',
        member_since: props.profile?.member_since ?? fallback.created_at ?? null
    }
})

const hasOrders = computed(() => ordersList.value.length > 0)
const timelineOrders = computed(() => ordersList.value.slice(0, 8))

const formatCurrency = (value = 0) =>
    new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'USD' }).format(value || 0)

const formatNumber = (value = 0) => new Intl.NumberFormat('es-AR').format(value || 0)

const formatDate = (value) => {
    if (!value) {
        return 'Sin registros'
    }
    return new Intl.DateTimeFormat('es-AR', { dateStyle: 'medium' }).format(new Date(value))
}

const formattedMemberSince = computed(() => formatDate(profileCard.value.member_since))

const statusBadgeClass = (status = '') => {
    const normalized = status.toLowerCase()
    if (normalized === 'paid') {
        return 'bg-emerald-400/15 text-emerald-200 border border-emerald-400/40'
    }
    if (normalized === 'unpaid' || normalized === 'pending') {
        return 'bg-amber-400/15 text-amber-200 border border-amber-400/40'
    }
    return 'bg-slate-400/15 text-slate-200 border border-slate-400/40'
}

const dotClass = (status = '') => {
    const normalized = status.toLowerCase()
    if (normalized === 'paid') {
        return 'bg-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.7)]'
    }
    if (normalized === 'unpaid' || normalized === 'pending') {
        return 'bg-amber-400 shadow-[0_0_12px_rgba(251,191,36,0.6)]'
    }
    return 'bg-slate-400 shadow-[0_0_12px_rgba(148,163,184,0.6)]'
}

const statCards = computed(() => {
    const stats = statsData.value
    return [
        {
            id: 'orders',
            label: 'Pedidos totales',
            value: formatNumber(stats?.total_orders ?? 0),
            note: 'Todos los pedidos registrados',
            accent: 'from-sky-500/40 via-indigo-500/10 to-slate-900/40'
        },
        {
            id: 'spent',
            label: 'Total gastado',
            value: formatCurrency(stats?.total_spent ?? 0),
            note: 'Pedidos pagados',
            accent: 'from-amber-300/40 via-orange-500/10 to-rose-500/20'
        },
        {
            id: 'pending',
            label: 'Pedidos en proceso',
            value: formatNumber(stats?.pending_orders ?? 0),
            note: 'A la espera de pago/confirmación',
            accent: 'from-emerald-400/20 via-lime-400/10 to-slate-900/40'
        },
        {
            id: 'items',
            label: 'Artículos recibidos',
            value: formatNumber(stats?.items_count ?? 0),
            note: 'Total de unidades compradas',
            accent: 'from-pink-400/30 via-purple-500/20 to-indigo-900/30'
        }
    ]
})

const lastPurchaseLabel = computed(() => formatDate(statsData.value?.last_order_at ?? null))
</script>

<template>
    <UserLayouts>
        <section class="dashboard-surface">
            <div class="max-w-6xl px-6 py-16 mx-auto space-y-12">
                <div class="rounded-[32px] bg-gradient-to-br from-indigo-500 via-purple-500 to-slate-900 p-[1px] shadow-[0_20px_80px_rgba(15,23,42,0.65)]">
                    <div class="rounded-[30px] bg-slate-950/85 px-8 py-10 sm:px-12 grid gap-10 lg:grid-cols-[2fr,1fr] items-center">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Panel personal</p>
                            <h1 class="mt-4 text-4xl sm:text-5xl text-white font-semibold font-grotesk leading-tight">
                                Hola, {{ profileCard.name }}
                            </h1>
                            <p class="mt-4 text-slate-300 text-lg">{{ profileCard.email }}</p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <Link
                                    href="/profile"
                                    class="inline-flex items-center gap-2 rounded-full bg-white/90 text-slate-900 px-6 py-3 text-sm font-semibold transition hover:bg-white"
                                >
                                    Actualizar información
                                </Link>
                                <Link
                                    href="/profile#password"
                                    class="inline-flex items-center gap-2 rounded-full border border-white/40 text-white px-6 py-3 text-sm font-semibold hover:bg-white/10"
                                >
                                    Cambiar contraseña
                                </Link>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-white/5 border border-white/10 p-6 text-white space-y-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Miembro desde</p>
                                <p class="mt-2 text-2xl font-semibold">{{ formattedMemberSince }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm text-slate-200">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase">Pedidos</p>
                                    <p class="text-xl font-semibold">{{ formatNumber(statsData.total_orders ?? 0) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 uppercase">Artículos</p>
                                    <p class="text-xl font-semibold">{{ formatNumber(statsData.items_count ?? 0) }}</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-white/10">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Última compra</p>
                                <p class="mt-2 text-lg font-semibold">{{ lastPurchaseLabel }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <article
                        v-for="card in statCards"
                        :key="card.id"
                        class="rounded-3xl border border-white/10 p-5 text-white bg-gradient-to-br"
                        :class="card.accent"
                    >
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">{{ card.label }}</p>
                        <p class="mt-4 text-3xl font-semibold font-grotesk">{{ card.value }}</p>
                        <p class="mt-2 text-sm text-white/80">{{ card.note }}</p>
                    </article>
                </div>

                <div class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
                    <section class="rounded-3xl bg-slate-900/70 border border-white/5 p-8 h-fit">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Historial</p>
                                <h2 class="mt-2 text-2xl text-white font-semibold font-grotesk">Pedidos recientes</h2>
                            </div>
                            <span class="text-sm text-slate-300">{{ formatNumber(statsData.total_orders ?? 0) }} en total</span>
                        </div>
                        <div v-if="hasOrders" class="relative mt-8 pl-6">
                            <div class="absolute left-3 top-0 bottom-0 w-px bg-gradient-to-b from-white/40 via-white/10 to-transparent"></div>
                            <ul class="space-y-10">
                                <li v-for="order in timelineOrders" :key="order.id" class="relative">
                                    <span class="absolute -left-[27px] top-2 h-4 w-4 rounded-full border border-white/30" :class="dotClass(order.status)"></span>
                                    <div class="bg-white/5 rounded-2xl p-5 border border-white/5">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <p class="text-lg font-semibold text-white">Pedido #{{ order.id }}</p>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full uppercase" :class="statusBadgeClass(order.status)">
                                                {{ order.status || 'Sin estado' }}
                                            </span>
                                        </div>
                                        <p class="mt-2 text-sm text-slate-300">
                                            {{ formatDate(order.created_at) }} · {{ formatCurrency(order.total_price) }}
                                        </p>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span
                                                v-for="item in order.order_items"
                                                :key="item.id"
                                                class="px-3 py-1 rounded-full bg-white/10 text-xs text-slate-100"
                                            >
                                                {{ item.product ? item.product.title : 'Producto' }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <p v-else class="mt-10 text-slate-300">
                            Aún no registras compras. Explora el catálogo y realiza tu primer pedido.
                        </p>
                    </section>

                    <section class="rounded-3xl bg-white/5 border border-white/10 p-8">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-white/70">Productos</p>
                                <h2 class="mt-2 text-2xl text-white font-semibold font-grotesk">Compras destacadas</h2>
                            </div>
                        </div>
                        <div v-if="recentProductsList.length" class="mt-6 space-y-4">
                            <article
                                v-for="product in recentProductsList"
                                :key="product.id"
                                class="rounded-2xl bg-slate-950/60 border border-white/5 p-5"
                            >
                                <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Pedido #{{ product.order_id }}</p>
                                <h3 class="mt-2 text-lg text-white font-semibold">{{ product.title }}</h3>
                                <p class="text-sm text-slate-300">
                                    {{ product.brand || 'Marca desconocida' }} · {{ product.category || 'Sin categoría' }}
                                </p>
                                <p class="mt-4 text-xl font-semibold text-white">{{ formatCurrency(product.price) }}</p>
                                <p class="text-xs text-slate-400 mt-1">Comprado el {{ formatDate(product.purchased_at) }}</p>
                            </article>
                        </div>
                        <p v-else class="mt-6 text-slate-300">
                            Tus productos favoritos aparecerán aquí después de tu primera compra.
                        </p>
                    </section>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

.dashboard-surface {
    min-height: 100vh;
    background-color: #050816;
    background-image:
        radial-gradient(circle at 20% 20%, rgba(79, 70, 229, 0.35), transparent 40%),
        radial-gradient(circle at 80% 0%, rgba(236, 72, 153, 0.25), transparent 45%),
        radial-gradient(circle at 50% 80%, rgba(14, 165, 233, 0.2), transparent 55%);
}

.font-grotesk {
    font-family: 'Space Grotesk', sans-serif;
}
</style>