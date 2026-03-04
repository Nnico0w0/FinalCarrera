<script setup>
import { computed, onMounted, reactive, ref } from 'vue'

const resourceOrder = [
    {
        id: 'users',
        label: 'Usuarios',
        accent: 'from-sky-500/40 via-indigo-500/10 to-slate-900/40',
    },
    {
        id: 'products',
        label: 'Productos',
        accent: 'from-amber-300/40 via-orange-500/10 to-rose-500/20',
    },
    {
        id: 'categories',
        label: 'Categorías',
        accent: 'from-emerald-400/20 via-lime-400/10 to-slate-900/40',
    },
    {
        id: 'brands',
        label: 'Marcas',
        accent: 'from-pink-400/30 via-purple-500/20 to-indigo-900/30',
    },
]

const endpoints = {
    users: '/api/v1/users',
    products: '/api/v1/products',
    categories: '/api/v1/categories',
    brands: '/api/v1/brands',
}

const lists = reactive({
    users: [],
    products: [],
    categories: [],
    brands: [],
})

const listLoading = reactive({
    users: false,
    products: false,
    categories: false,
    brands: false,
})

const editingId = reactive({
    users: null,
    products: null,
    categories: null,
    brands: null,
})

const createBlankForm = (resourceId) => {
    switch (resourceId) {
        case 'users':
            return { name: '', email: '', password: '' }
        case 'products':
            return {
                title: '',
                price: '',
                quantity: '',
                category_id: '',
                brand_id: '',
                description: '',
                published: true,
                inStock: true,
            }
        case 'categories':
            return { name: '' }
        case 'brands':
            return { name: '' }
        default:
            return {}
    }
}

const forms = reactive({
    users: createBlankForm('users'),
    products: createBlankForm('products'),
    categories: createBlankForm('categories'),
    brands: createBlankForm('brands'),
})

const activeResource = ref('users')
const saving = ref(false)
const deletingId = ref(null)

const feedback = reactive({ type: null, message: null })
let feedbackTimer = null

const currencyFormatter = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'USD' })
const formatCurrency = (value = 0) => currencyFormatter.format(Number(value) || 0)

const resourceConfigs = {
    users: {
        label: 'Usuarios',
        singular: 'usuario',
        plural: 'usuarios',
        listTitle: 'Últimos registros',
        description: 'Gestioná clientes, staff y accesos.',
        emptyCopy: 'No hay usuarios registrados todavía.',
        itemTitle: (item) => item.name ?? 'Sin nombre',
        itemSubtitle: (item) => item.email ?? 'Sin correo asignado',
        fields: [
            {
                key: 'name',
                label: 'Nombre completo',
                type: 'text',
                required: true,
                placeholder: 'Ej: Carla Díaz',
            },
            {
                key: 'email',
                label: 'Correo electrónico',
                type: 'email',
                required: true,
                placeholder: 'cliente@tecnosector.com',
            },
            {
                key: 'password',
                label: 'Contraseña',
                type: 'password',
                required: true,
                optionalOnEdit: true,
                placeholder: 'Mínimo 8 caracteres',
                hint: 'Dejala vacía al editar para mantener la actual.',
            },
        ],
    },
    products: {
        label: 'Productos',
        singular: 'producto',
        plural: 'productos',
        listTitle: 'Catálogo activo',
        description: 'Actualizá precios, stock y publicación.',
        emptyCopy: 'Todavía no hay productos para mostrar.',
        itemTitle: (item) => item.title ?? 'Sin título',
        itemSubtitle: (item) => {
            const brandLabel = item.brand?.name ?? 'Marca general'
            return `${formatCurrency(item.price)} · ${item.quantity ?? 0} uds · ${brandLabel}`
        },
        fields: [
            {
                key: 'title',
                label: 'Nombre del producto',
                type: 'text',
                required: true,
                placeholder: 'Notebook Creator 14',
            },
            {
                key: 'price',
                label: 'Precio (USD)',
                type: 'number',
                step: '0.01',
                required: true,
                placeholder: '1500',
            },
            {
                key: 'quantity',
                label: 'Stock disponible',
                type: 'number',
                required: true,
                placeholder: '25',
            },
            {
                key: 'category_id',
                label: 'Categoría',
                type: 'select',
                optionsSource: 'categories',
                placeholder: 'Seleccioná una categoría',
            },
            {
                key: 'brand_id',
                label: 'Marca',
                type: 'select',
                optionsSource: 'brands',
                placeholder: 'Seleccioná una marca',
            },
            {
                key: 'description',
                label: 'Descripción',
                type: 'textarea',
                placeholder: 'Especificaciones clave, procesador, pantalla, etc.',
            },
            {
                key: 'published',
                label: 'Publicado',
                type: 'toggle',
                helper: 'Visible en el catálogo público.',
            },
            {
                key: 'inStock',
                label: 'En stock',
                type: 'toggle',
                helper: 'Disponible para la venta inmediata.',
            },
        ],
    },
    categories: {
        label: 'Categorías',
        singular: 'categoría',
        plural: 'categorías',
        listTitle: 'Segmentos del catálogo',
        description: 'Organizá los productos por línea o uso.',
        emptyCopy: 'Todavía no definiste categorías.',
        itemTitle: (item) => item.name ?? 'Sin nombre',
        itemSubtitle: (item) => `${item.products?.length ?? 0} productos asociados`,
        fields: [
            {
                key: 'name',
                label: 'Nombre de la categoría',
                type: 'text',
                required: true,
                placeholder: 'Workstations, Accesorios, etc.',
            },
        ],
    },
    brands: {
        label: 'Marcas',
        singular: 'marca',
        plural: 'marcas',
        listTitle: 'Partners y fabricantes',
        description: 'Mantené actualizado el portfolio comercial.',
        emptyCopy: 'Agregá las marcas con las que trabajás.',
        itemTitle: (item) => item.name ?? 'Sin nombre',
        itemSubtitle: (item) => `${item.products?.length ?? 0} productos vinculados`,
        fields: [
            {
                key: 'name',
                label: 'Nombre de la marca',
                type: 'text',
                required: true,
                placeholder: 'TecnoSector Labs',
            },
        ],
    },
}

const currentConfig = computed(() => resourceConfigs[activeResource.value])
const currentList = computed(() => {
    const list = lists[activeResource.value] ?? []
    return list.slice(0, 6)
})
const isEditing = computed(() => Boolean(editingId[activeResource.value]))
const resourceCounts = computed(() => ({
    users: (lists.users ?? []).length,
    products: (lists.products ?? []).length,
    categories: (lists.categories ?? []).length,
    brands: (lists.brands ?? []).length,
}))

const extractRows = (payload) => {
    if (!payload) {
        return []
    }
    if (Array.isArray(payload)) {
        return payload
    }
    const dataset = payload.data ?? []
    if (Array.isArray(dataset)) {
        return dataset
    }
    if (Array.isArray(dataset?.data)) {
        return dataset.data
    }
    return []
}

const setFeedback = (type, message) => {
    feedback.type = type
    feedback.message = message
    if (feedbackTimer) {
        clearTimeout(feedbackTimer)
    }
    if (message) {
        feedbackTimer = setTimeout(() => {
            feedback.type = null
            feedback.message = null
        }, 5000)
    }
}

const parseErrorMessage = (error, fallback) => {
    const validationErrors = error?.response?.data?.errors
    if (validationErrors) {
        const firstKey = Object.keys(validationErrors)[0]
        if (firstKey) {
            return validationErrors[firstKey][0]
        }
    }
    return error?.response?.data?.message ?? fallback
}

const fetchResource = async (resourceId) => {
    if (!endpoints[resourceId]) {
        return
    }
    listLoading[resourceId] = true
    try {
        const params = resourceId === 'products' ? { params: { per_page: 25 } } : {}
        const response = await window.axios.get(endpoints[resourceId], params)
        lists[resourceId] = extractRows(response.data)
    } catch (error) {
        setFeedback('error', parseErrorMessage(error, `No pudimos cargar ${resourceConfigs[resourceId].plural}.`))
    } finally {
        listLoading[resourceId] = false
    }
}

const fetchAllResources = () => Promise.all(resourceOrder.map((resource) => fetchResource(resource.id)))

const resetForm = (resourceId) => {
    editingId[resourceId] = null
    Object.assign(forms[resourceId], createBlankForm(resourceId))
}

const mapItemToForm = (resourceId, item) => {
    switch (resourceId) {
        case 'users':
            return {
                name: item.name ?? '',
                email: item.email ?? '',
                password: '',
            }
        case 'products':
            return {
                title: item.title ?? '',
                price: item.price ?? '',
                quantity: item.quantity ?? '',
                category_id: item.category_id ?? item.category?.id ?? '',
                brand_id: item.brand_id ?? item.brand?.id ?? '',
                description: item.description ?? '',
                published: item.published ?? true,
                inStock: item.inStock ?? true,
            }
        case 'categories':
            return { name: item.name ?? '' }
        case 'brands':
            return { name: item.name ?? '' }
        default:
            return {}
    }
}

const handleEdit = (resourceId, item) => {
    editingId[resourceId] = item.id
    Object.assign(forms[resourceId], mapItemToForm(resourceId, item))
}

const buildPayload = (resourceId) => {
    const form = forms[resourceId]
    switch (resourceId) {
        case 'users': {
            const payload = {
                name: form.name,
                email: form.email,
            }
            if (form.password) {
                payload.password = form.password
            }
            return payload
        }
        case 'products':
            return {
                title: form.title,
                price: Number(form.price) || 0,
                quantity: Number(form.quantity) || 0,
                category_id: form.category_id ? Number(form.category_id) : null,
                brand_id: form.brand_id ? Number(form.brand_id) : null,
                description: form.description,
                published: Boolean(form.published),
                inStock: Boolean(form.inStock),
            }
        case 'categories':
        case 'brands':
            return {
                name: form.name,
            }
        default:
            return {}
    }
}

const submitResource = async (resourceId) => {
    if (!endpoints[resourceId]) {
        return
    }
    saving.value = true
    try {
        const payload = buildPayload(resourceId)
        const isUpdate = Boolean(editingId[resourceId])
        const url = isUpdate ? `${endpoints[resourceId]}/${editingId[resourceId]}` : endpoints[resourceId]
        const method = isUpdate ? 'put' : 'post'
        await window.axios[method](url, payload)
        setFeedback('success', `${currentConfig.value.singular} ${isUpdate ? 'actualizado' : 'creado'} correctamente.`)
        await fetchResource(resourceId)
        if (['categories', 'brands'].includes(resourceId)) {
            await fetchResource('products')
        }
        resetForm(resourceId)
    } catch (error) {
        setFeedback('error', parseErrorMessage(error, 'Revisá los campos e intentá nuevamente.'))
    } finally {
        saving.value = false
    }
}

const submitActive = () => submitResource(activeResource.value)

const handleDelete = async (resourceId, itemId) => {
    if (!endpoints[resourceId]) {
        return
    }
    const confirmed = window.confirm(`¿Eliminar este ${resourceConfigs[resourceId].singular}?`)
    if (!confirmed) {
        return
    }
    deletingId.value = `${resourceId}-${itemId}`
    try {
        await window.axios.delete(`${endpoints[resourceId]}/${itemId}`)
        setFeedback('success', `${resourceConfigs[resourceId].singular} eliminado correctamente.`)
        await fetchResource(resourceId)
        if (['categories', 'brands'].includes(resourceId)) {
            await fetchResource('products')
        }
        if (editingId[resourceId] === itemId) {
            resetForm(resourceId)
        }
    } catch (error) {
        setFeedback('error', parseErrorMessage(error, 'No pudimos eliminar el registro.'))
    } finally {
        deletingId.value = null
    }
}

const isDeleting = (resourceId, item) => deletingId.value === `${resourceId}-${item.id}`

const getFieldOptions = (field) => {
    if (field.optionsSource) {
        return lists[field.optionsSource] ?? []
    }
    return field.options ?? []
}

const refreshActive = () => fetchResource(activeResource.value)

onMounted(() => {
    fetchAllResources()
})
</script>

<template>
    <section class="rounded-[32px] border border-white/10 bg-slate-950/85 p-8 text-white shadow-[0_20px_80px_rgba(15,23,42,0.65)]">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Panel administrativo</p>
                <h2 class="mt-2 text-3xl font-semibold">Control del catálogo y usuarios</h2>
                <p class="mt-2 text-white/70">
                    Gestioná usuarios, productos, categorías y marcas sin salir del dashboard.
                </p>
            </div>
            <button
                class="rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white/80 transition hover:border-white/40"
                @click="refreshActive"
                :disabled="listLoading[activeResource]"
            >
                {{ listLoading[activeResource] ? 'Actualizando...' : `Actualizar ${currentConfig.singular}` }}
            </button>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <button
                v-for="resource in resourceOrder"
                :key="resource.id"
                @click="activeResource = resource.id"
                :class="[
                    'rounded-full px-5 py-2 text-sm font-semibold transition',
                    activeResource === resource.id
                        ? 'bg-white text-slate-900'
                        : 'border border-white/20 text-white/70 hover:text-white'
                ]"
            >
                {{ resource.label }}
                <span class="ml-2 text-xs text-white/60">
                    ({{ resourceCounts[resource.id] ?? 0 }})
                </span>
            </button>
        </div>

        <div
            v-if="feedback.message"
            class="mt-6 rounded-2xl border px-4 py-3 text-sm"
            :class="feedback.type === 'error'
                ? 'border-rose-400/40 bg-rose-400/10 text-rose-100'
                : 'border-emerald-400/40 bg-emerald-400/10 text-emerald-100'"
        >
            {{ feedback.message }}
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.35fr,0.65fr]">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ currentConfig.listTitle }}</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">{{ currentConfig.label }}</h3>
                        <p class="text-sm text-white/60">{{ currentConfig.description }}</p>
                    </div>
                    <span class="rounded-full border border-white/15 px-3 py-1 text-xs text-white/70">
                        {{ resourceCounts[activeResource] ?? 0 }} registros
                    </span>
                </div>

                <div
                    v-if="listLoading[activeResource]"
                    class="mt-6 flex h-40 items-center justify-center text-white/60"
                >
                    Cargando {{ currentConfig.plural }}...
                </div>

                <div v-else-if="currentList.length" class="mt-6 space-y-4">
                    <article
                        v-for="item in currentList"
                        :key="item.id"
                        class="rounded-2xl border border-white/10 bg-slate-950/60 p-5"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold">{{ currentConfig.itemTitle(item) }}</p>
                                <p class="text-sm text-white/60">{{ currentConfig.itemSubtitle(item) }}</p>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    @click="handleEdit(activeResource, item)"
                                    class="rounded-full border border-white/20 px-4 py-1.5 text-xs font-semibold text-white/80 transition hover:border-white/50"
                                >
                                    Editar
                                </button>
                                <button
                                    type="button"
                                    @click="handleDelete(activeResource, item.id)"
                                    :disabled="isDeleting(activeResource, item)"
                                    class="rounded-full border border-rose-400/30 px-4 py-1.5 text-xs font-semibold text-rose-200 transition hover:border-rose-400"
                                >
                                    <span v-if="isDeleting(activeResource, item)">Eliminando...</span>
                                    <span v-else>Eliminar</span>
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <p v-else class="mt-6 text-white/60">{{ currentConfig.emptyCopy }}</p>
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-950/70 p-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Formulario</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">
                            {{ isEditing ? 'Editar' : 'Crear' }} {{ currentConfig.singular }}
                        </h3>
                    </div>
                    <button
                        type="button"
                        @click="resetForm(activeResource)"
                        class="rounded-full border border-white/20 px-4 py-1.5 text-xs font-semibold text-white/70 transition hover:border-white/50"
                    >
                        Limpiar
                    </button>
                </div>

                <form class="mt-6 space-y-4" @submit.prevent="submitActive">
                    <div v-for="field in currentConfig.fields" :key="field.key">
                        <label class="text-xs uppercase tracking-[0.3em] text-white/50">{{ field.label }}</label>

                        <template v-if="field.type === 'textarea'">
                            <textarea
                                v-model="forms[activeResource][field.key]"
                                rows="3"
                                class="ts-input mt-2"
                                :placeholder="field.placeholder"
                            ></textarea>
                        </template>

                        <template v-else-if="field.type === 'select'">
                            <select
                                v-model="forms[activeResource][field.key]"
                                class="ts-input mt-2"
                            >
                                <option value="">{{ field.placeholder ?? 'Sin asignar' }}</option>
                                <option
                                    v-for="option in getFieldOptions(field)"
                                    :key="option.id"
                                    :value="option.id"
                                >
                                    {{ option.name ?? option.title ?? 'Sin nombre' }}
                                </option>
                            </select>
                        </template>

                        <template v-else-if="field.type === 'toggle'">
                            <label class="mt-2 flex cursor-pointer items-center gap-3 text-sm text-white/80">
                                <span class="relative inline-flex h-6 w-11 items-center rounded-full"
                                    :class="forms[activeResource][field.key] ? 'bg-emerald-400/90' : 'bg-white/30'"
                                >
                                    <span
                                        class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                                        :class="{ 'translate-x-5': forms[activeResource][field.key] }"
                                    ></span>
                                </span>
                                {{ field.helper }}
                            </label>
                        </template>

                        <template v-else>
                            <input
                                :type="field.type"
                                v-model="forms[activeResource][field.key]"
                                class="ts-input mt-2"
                                :step="field.step"
                                :placeholder="field.placeholder"
                                :required="field.required && !(field.optionalOnEdit && isEditing)"
                                :min="field.type === 'number' ? '0' : null"
                            />
                            <p v-if="field.hint" class="mt-1 text-xs text-white/50">{{ field.hint }}</p>
                        </template>
                    </div>

                    <button
                        type="submit"
                        class="ts-btn-primary w-full text-center text-sm"
                        :disabled="saving"
                    >
                        {{ saving ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Crear') }} {{ currentConfig.singular }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
