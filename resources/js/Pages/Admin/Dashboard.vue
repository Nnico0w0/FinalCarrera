<template>
    <AdminLayout>
        <section class="space-y-8 px-2 py-6 text-slate-100">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Resumen ejecutivo</p>
                <h1 class="mt-2 text-3xl font-semibold">Salud del negocio</h1>
            </div>

            <div class="grid gap-4 lg:grid-cols-5">
                <article v-for="card in statCards" :key="card.id"
                    class="rounded-3xl border border-white/10 bg-slate-900/80 p-5 shadow-[0_20px_60px_rgba(15,23,42,0.55)]">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ card.label }}</p>
                    <p class="mt-3 text-3xl font-semibold">{{ card.value }}</p>
                    <p class="text-sm text-white/60">{{ card.hint }}</p>
                </article>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.4fr,0.6fr]">
                <section class="rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/50">Ventas</p>
                            <h2 class="mt-2 text-2xl font-semibold">Tendencia mensual</h2>
                        </div>
                        <span class="rounded-full border border-white/10 px-4 py-1 text-xs text-white/70">Últimos 12 meses</span>
                    </div>
                    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr,220px]">
                        <div class="relative">
                            <svg viewBox="0 0 100 100" preserveAspectRatio="none"
                                class="h-64 w-full rounded-3xl bg-slate-950/60">
                                <defs>
                                    <linearGradient id="trendGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#a855f7" stop-opacity="0.9" />
                                        <stop offset="100%" stop-color="#0f172a" stop-opacity="0.2" />
                                    </linearGradient>
                                </defs>
                                <polyline v-if="chartPoints.points" :points="chartPoints.points"
                                    fill="none" stroke="#a855f7" stroke-width="2" stroke-linecap="round" />
                                <polyline v-if="chartPoints.points"
                                    :points="`${chartPoints.points} 100,100 0,100`"
                                    fill="url(#trendGradient)" stroke="none" opacity="0.35" />
                                <line v-for="tick in 4" :key="tick" :x1="0" :x2="100" :y1="tick * 20" :y2="tick * 20"
                                    stroke="rgba(255,255,255,0.05)" stroke-width="0.5" />
                            </svg>
                            <div class="mt-4 grid grid-cols-4 gap-2 text-xs text-white/60">
                                <span v-for="label in bottomLabels" :key="label.label" class="truncate">
                                    {{ label.label }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <article v-for="point in chartPoints.details" :key="point.label"
                                class="rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                                <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ point.label }}</p>
                                <p class="text-xl font-semibold">{{ point.value }}</p>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="space-y-6">
                    <section class="rounded-3xl border border-white/10 bg-slate-900/80 p-6">
                        <header class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-white/50">Productos</p>
                                <h3 class="mt-1 text-xl font-semibold">Más vendidos</h3>
                            </div>
                            <span class="text-sm text-white/60">Top 5</span>
                        </header>
                        <ul class="mt-4 space-y-3 text-sm text-white/80">
                            <li v-for="product in topProducts" :key="product.product"
                                class="flex items-center justify-between rounded-2xl border border-white/5 bg-white/5 px-4 py-3">
                                <div>
                                    <p class="font-semibold">{{ product.product }}</p>
                                    <p class="text-xs text-white/60">{{ product.units }} uds</p>
                                </div>
                                <span class="text-sm font-semibold">{{ formatCurrency(product.revenue) }}</span>
                            </li>
                            <p v-if="!topProducts.length" class="text-white/60">Sin ventas registradas.</p>
                        </ul>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/80 p-6">
                        <header class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-white/50">Clientes</p>
                                <h3 class="mt-1 text-xl font-semibold">Lealtad</h3>
                            </div>
                            <span class="text-sm text-white/60">Top 5</span>
                        </header>
                        <ul class="mt-4 space-y-4 text-sm text-white/80">
                            <li v-for="client in topCustomers" :key="client.email"
                                class="rounded-2xl border border-white/5 bg-white/5 px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold">{{ client.name }}</p>
                                        <p class="text-xs text-white/60">{{ client.email }}</p>
                                    </div>
                                    <span class="text-xs uppercase tracking-[0.35em] text-white/50">{{ client.orders }} pedidos</span>
                                </div>
                                <p class="mt-2 text-sm">{{ formatCurrency(client.spent) }} gastados</p>
                            </li>
                            <p v-if="!topCustomers.length" class="text-white/60">No hay clientes con compras aún.</p>
                        </ul>
                    </section>
                </aside>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
                <section class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/50">Compras</p>
                            <h2 class="mt-1 text-2xl font-semibold">Órdenes recientes</h2>
                        </div>
                        <span class="text-sm text-white/60">Tabla global · últimos 10 movimientos</span>
                    </div>
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-[0.35em] text-white/50">
                                    <th class="pb-3">ID</th>
                                    <th class="pb-3">Cliente</th>
                                    <th class="pb-3">Estado</th>
                                    <th class="pb-3">Artículos</th>
                                    <th class="pb-3">Total</th>
                                    <th class="pb-3">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="order in recentOrders" :key="order.id" class="text-white/80">
                                    <td class="py-3 font-semibold">#{{ order.id }}</td>
                                    <td class="py-3">
                                        <p class="font-semibold">{{ order.customer }}</p>
                                        <p class="text-xs text-white/50">{{ order.email || '—' }}</p>
                                    </td>
                                    <td class="py-3">
                                        <span :class="statusBadge(order.status)" class="rounded-full px-3 py-1 text-xs font-semibold uppercase">
                                            {{ order.status || 'N/D' }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ order.items }}</td>
                                    <td class="py-3 font-semibold">{{ formatCurrency(order.total) }}</td>
                                    <td class="py-3 text-white/60">{{ formatDate(order.created_at) }}</td>
                                </tr>
                                <tr v-if="!recentOrders.length">
                                    <td colspan="6" class="py-6 text-center text-white/60">Aún no hay órdenes registradas.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-[32px] border border-white/10 bg-slate-900/80 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/50">Inventario</p>
                            <h2 class="mt-1 text-2xl font-semibold">Stock más bajo</h2>
                        </div>
                        <span class="text-sm text-white/60">Ordenado ascendente</span>
                    </div>
                    <ul class="mt-5 space-y-4 text-sm text-white/80">
                        <li v-for="product in lowStockProducts" :key="product.id"
                            class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ product.title }}</p>
                                    <p class="text-xs text-white/50">{{ formatCurrency(product.price) }}</p>
                                </div>
                                <span class="rounded-full border border-white/20 px-3 py-1 text-xs font-semibold">
                                    {{ product.quantity }} uds
                                </span>
                            </div>
                        </li>
                        <p v-if="!lowStockProducts.length" class="text-white/60">Inventario sin alertas de bajo stock.</p>
                    </ul>
                </section>
            </div>

            <section>
                <AdminCrudPanel />
            </section>
        </section>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from './Components/AdminLayout.vue'
import AdminCrudPanel from '../User/Components/AdminCrudPanel.vue'

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({}),
    },
    salesTrend: {
        type: Array,
        default: () => [],
    },
    recentOrders: {
        type: Array,
        default: () => [],
    },
    topProducts: {
        type: Array,
        default: () => [],
    },
    topCustomers: {
        type: Array,
        default: () => [],
    },
    lowStockProducts: {
        type: Array,
        default: () => [],
    },
})

const currencyFormatter = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'USD' })
const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'medium', timeStyle: 'short' })

const formatCurrency = (value) => currencyFormatter.format(value || 0)
const formatDate = (value) => {
    if (!value) {
        return 'Sin fecha'
    }
    return dateFormatter.format(new Date(value))
}

const statCards = computed(() => [
    {
        id: 'revenue',
        label: 'Ingresos',
        value: formatCurrency(props.stats.total_revenue ?? 0),
        hint: 'Pedidos pagados',
    },
    {
        id: 'orders',
        label: 'Pedidos totales',
        value: props.stats.total_orders ?? 0,
        hint: `${props.stats.paid_orders ?? 0} pagados`,
    },
    {
        id: 'ticket',
        label: 'Ticket promedio',
        value: formatCurrency(props.stats.average_ticket ?? 0),
        hint: 'Solo pedidos pagados',
    },
    {
        id: 'items',
        label: 'Artículos vendidos',
        value: props.stats.items_sold ?? 0,
        hint: 'Unidades entregadas',
    },
    {
        id: 'conversion',
        label: 'Tasa pago',
        value: props.stats.total_orders ? `${Math.round(((props.stats.paid_orders ?? 0) / props.stats.total_orders) * 100)}%` : '0%',
        hint: 'Pagados vs totales',
    },
])

const chartPoints = computed(() => {
    const data = props.salesTrend ?? []
    if (!data.length) {
        return { points: '', details: [] }
    }
    const max = Math.max(...data.map((point) => point.total), 1)
    const stepX = data.length > 1 ? 100 / (data.length - 1) : 100
    const points = data
        .map((point, index) => {
            const normalized = point.total / max
            const x = stepX * index
            const y = 90 - normalized * 80
            return `${x},${y}`
        })
        .join(' ')

    const details = data.slice(-4).reverse().map((point) => ({
        label: point.label,
        value: formatCurrency(point.total),
    }))

    return { points, details }
})

const bottomLabels = computed(() => {
    const data = props.salesTrend ?? []
    const chunk = Math.ceil(data.length / 4)
    if (!chunk) {
        return []
    }
    const labels = []
    for (let i = 0; i < data.length; i += chunk) {
        labels.push(data[i])
    }
    return labels.slice(0, 4)
})

const statusBadge = (status = '') => {
    const normalized = status.toLowerCase()
    if (normalized === 'paid') {
        return 'bg-emerald-400/15 text-emerald-200 border border-emerald-400/40'
    }
    if (normalized === 'pending') {
        return 'bg-amber-400/15 text-amber-200 border border-amber-400/40'
    }
    if (normalized === 'unpaid') {
        return 'bg-rose-400/15 text-rose-200 border border-rose-400/40'
    }
    return 'bg-slate-400/15 text-slate-200 border border-slate-400/40'
}

defineExpose({ formatCurrency })
</script>