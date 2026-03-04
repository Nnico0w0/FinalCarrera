<script setup>
import { computed } from 'vue'
import AdminLayout from './Components/AdminLayout.vue'

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
})

const currencyFormatter = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'USD' })
const formatCurrency = (value = 0) => currencyFormatter.format(Number(value) || 0)

const dateFormatter = new Intl.DateTimeFormat('es-AR', { dateStyle: 'medium', timeStyle: 'short' })
const formatDate = (value) => {
    if (!value) {
        return '—'
    }

    const date = new Date(value)
    if (Number.isNaN(date.getTime())) {
        return '—'
    }

    return dateFormatter.format(date)
}

const trendTotal = computed(() => (props.salesTrend ?? []).reduce((sum, item) => sum + Number(item.total || 0), 0))
</script>

<template>
    <AdminLayout>
        <section class="space-y-6 px-2 py-6 text-slate-100">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Reporte</p>
                <h1 class="mt-2 text-3xl font-semibold">Sale Report</h1>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <article class="ts-card p-5">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">Revenue</p>
                    <p class="mt-2 text-2xl font-semibold">{{ formatCurrency(stats.total_revenue) }}</p>
                </article>
                <article class="ts-card p-5">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">Orders</p>
                    <p class="mt-2 text-2xl font-semibold">{{ stats.total_orders ?? 0 }}</p>
                </article>
                <article class="ts-card p-5">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">Paid Orders</p>
                    <p class="mt-2 text-2xl font-semibold">{{ stats.paid_orders ?? 0 }}</p>
                </article>
                <article class="ts-card p-5">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">Average Ticket</p>
                    <p class="mt-2 text-2xl font-semibold">{{ formatCurrency(stats.average_ticket) }}</p>
                </article>
            </div>

            <section class="ts-card p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Ventas mensuales</h2>
                    <span class="text-sm text-slate-400">Total: {{ formatCurrency(trendTotal) }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-white/5 text-xs uppercase text-slate-300">
                            <tr>
                                <th class="px-4 py-3">Mes</th>
                                <th class="px-4 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in salesTrend" :key="item.label" class="border-b border-white/10">
                                <td class="px-4 py-3">{{ item.label }}</td>
                                <td class="px-4 py-3">{{ formatCurrency(item.total) }}</td>
                            </tr>
                            <tr v-if="!salesTrend.length">
                                <td colspan="2" class="px-4 py-6 text-center text-slate-400">Sin datos de ventas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="ts-card p-6">
                <h2 class="mb-4 text-xl font-semibold">Órdenes recientes</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-white/5 text-xs uppercase text-slate-300">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in recentOrders" :key="order.id" class="border-b border-white/10">
                                <td class="px-4 py-3">#{{ order.id }}</td>
                                <td class="px-4 py-3">{{ order.customer }}</td>
                                <td class="px-4 py-3">{{ order.status }}</td>
                                <td class="px-4 py-3">{{ formatCurrency(order.total) }}</td>
                                <td class="px-4 py-3">{{ formatDate(order.created_at) }}</td>
                            </tr>
                            <tr v-if="!recentOrders.length">
                                <td colspan="5" class="px-4 py-6 text-center text-slate-400">No hay órdenes recientes.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </AdminLayout>
</template>
