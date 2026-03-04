<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '../Components/AdminLayout.vue'

defineProps({
    categories: Array,
})

const dialogVisible = ref(false)
const editMode = ref(false)
const id = ref('')
const name = ref('')

const resetForm = () => {
    id.value = ''
    name.value = ''
}

const openAddModal = () => {
    editMode.value = false
    resetForm()
    dialogVisible.value = true
}

const openEditModal = (category) => {
    editMode.value = true
    id.value = category.id
    name.value = category.name
    dialogVisible.value = true
}

const submit = async () => {
    if (editMode.value) {
        await router.put(`/admin/categories/update/${id.value}`, { name: name.value }, {
            onSuccess: (page) => {
                dialogVisible.value = false
                resetForm()
                Swal.fire({ toast: true, icon: 'success', position: 'top-end', showConfirmButton: false, title: page.props.flash.success })
            },
        })
        return
    }

    await router.post('/admin/categories/store', { name: name.value }, {
        onSuccess: (page) => {
            dialogVisible.value = false
            resetForm()
            Swal.fire({ toast: true, icon: 'success', position: 'top-end', showConfirmButton: false, title: page.props.flash.success })
        },
    })
}

const deleteCategory = (category) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
    }).then(async (result) => {
        if (!result.isConfirmed) {
            return
        }

        await router.delete(`/admin/categories/destroy/${category.id}`, {
            onSuccess: (page) => {
                Swal.fire({ toast: true, icon: 'success', position: 'top-end', showConfirmButton: false, title: page.props.flash.success })
            },
        })
    })
}
</script>

<template>
    <AdminLayout>
        <section class="px-2 py-6 text-slate-100">
            <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                <div class="ts-card relative overflow-hidden p-4">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Catálogo</p>
                            <h1 class="mt-2 text-3xl font-semibold">Categories</h1>
                        </div>
                        <button @click="openAddModal" class="ts-btn-primary px-4 py-2 text-sm font-medium">Add category</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-white/5 text-xs uppercase text-slate-300">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Products</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categories" :key="category.id" class="border-b border-white/10">
                                    <td class="px-4 py-3 font-medium text-slate-100">{{ category.name }}</td>
                                    <td class="px-4 py-3">{{ category.products_count }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openEditModal(category)" class="rounded-lg border border-white/20 px-3 py-1 text-xs hover:bg-white/10">Edit</button>
                                            <button @click="deleteCategory(category)" class="rounded-lg border border-red-400/40 px-3 py-1 text-xs text-red-200 hover:bg-red-500/10">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit category' : 'Add category'" width="30%">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="category_name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input id="category_name" v-model="name" type="text" required class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Submit</button>
            </form>
        </el-dialog>
    </AdminLayout>
</template>
