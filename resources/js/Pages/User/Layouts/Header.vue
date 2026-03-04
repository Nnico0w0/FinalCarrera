<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
const page = usePage();
const canLogin = page.props.canLogin;
const canRegister = page.props.canRegister;
const auth = page.props.auth;
const cart = computed(() => page.props.cart);

const showUserMenu = ref(false)
const menuRef = ref(null)
const buttonRef = ref(null)

const toggleMenu = () => {
    showUserMenu.value = !showUserMenu.value
}

const closeMenu = () => {
    showUserMenu.value = false
}

const handleClickOutside = (event) => {
    if (!showUserMenu.value) {
        return
    }
    const menuEl = menuRef.value
    const buttonEl = buttonRef.value
    if (menuEl?.contains(event.target) || buttonEl?.contains(event.target)) {
        return
    }
    closeMenu()
}

const handleKeydown = (event) => {
    if (event.key === 'Escape' && showUserMenu.value) {
        closeMenu()
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
    document.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
    document.removeEventListener('keydown', handleKeydown)
})
</script>
<template>
    <header class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/70 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <Link :href="route('home')" class="flex items-center gap-3 text-white">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">TecnoSector</p>
                    <p class="text-xl font-semibold">Laboratorio Retail</p>
                </div>
            </Link>

            <div class="hidden items-center gap-8 md:flex">
                <Link :href="route('dashboard')"
                    class="text-sm font-semibold text-white/70 transition hover:text-white">
                    Dashboard
                </Link>
                <Link :href="route('pricing')"
                    class="text-sm font-semibold text-white/70 transition hover:text-white">
                    Planes y combos
                </Link>
            </div>

            <div v-if="canLogin" class="flex items-center gap-4">
                <Link :href="route('cart.view')"
                    class="relative inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                    <span class="ml-2">Carrito</span>
                    <span
                        class="absolute -right-1 -top-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-xs font-bold">
                        {{ cart.data.count }}
                    </span>
                </Link>

                <button v-if="auth.user" type="button"
                    ref="buttonRef"
                    @click="toggleMenu"
                    class="flex items-center justify-center rounded-full border border-white/15 bg-white/10 p-1 text-white focus:ring-2 focus:ring-white/40"
                    :aria-expanded="showUserMenu"
                    aria-haspopup="true"
                >
                    <span class="sr-only">Open user menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        class="h-9 w-9 rounded-full bg-white text-slate-900" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </button>
                <div v-else class="flex gap-3">
                    <Link :href="route('login')"
                        class="rounded-full border border-white/15 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/40">
                        Ingresar
                    </Link>
                    <Link v-if="canRegister" :href="route('register')"
                        class="ts-btn-primary text-sm">
                        Crear cuenta
                    </Link>
                </div>
            </div>
        </nav>

        <div v-if="auth.user"
            ref="menuRef"
            class="ts-card absolute right-6 mt-2 min-w-[220px] divide-y divide-white/5 bg-slate-950/95 text-white transition duration-150"
            :class="showUserMenu ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 -translate-y-2 pointer-events-none'"
        >
            <div class="px-4 py-3">
                <p class="text-sm font-semibold">{{ auth.user.name }}</p>
                <p class="text-xs text-white/60">{{ auth.user.email }}</p>
            </div>
            <ul class="py-2 text-sm">
                <li>
                    <Link :href="route('dashboard')"
                        class="block px-4 py-2 text-white/80 hover:bg-white/5">
                    Dashboard</Link>
                </li>
                <li>
                    <Link :href="route('logout')" method="post" class="block px-4 py-2 text-rose-300 hover:bg-white/5">
                    Cerrar sesión</Link>
                </li>
            </ul>
        </div>
    </header>
</template>