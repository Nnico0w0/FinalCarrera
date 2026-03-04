<template>
    <nav
        class="fixed left-0 right-0 top-0 z-50 border-b border-white/10 bg-slate-950/80 px-4 py-2.5 backdrop-blur">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
                    aria-controls="drawer-navigation"
                    class="mr-2 cursor-pointer rounded-lg p-2 text-slate-300 hover:bg-white/10 hover:text-white focus:bg-white/10 focus:ring-2 focus:ring-white/20 md:hidden">
                    <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Toggle sidebar</span>
                </button>
                <Link :href="route('admin.dashboard')" class="flex items-center justify-between mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6 text-fuchsia-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>

                <span class="self-center whitespace-nowrap text-2xl font-semibold text-white">TecnoSector</span>
                </Link>
            </div>
            <div class="flex items-center lg:order-2">
                <button type="button"
                    class="mx-3 flex rounded-full bg-slate-800 text-sm focus:ring-4 focus:ring-white/20 md:mr-0"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                    <span class="sr-only">Open user menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        class="h-8 w-8 rounded-full bg-white text-slate-900" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 my-4 hidden w-56 list-none divide-y divide-white/10 rounded-xl bg-slate-900 text-base text-slate-200 shadow-lg"
                    id="dropdown">
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold text-white">{{ authUser.name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ authUser.email }}</p>
                    </div>
                    <ul class="py-1" aria-labelledby="dropdown">
                        <li>
                            <Link :href="route('logout')" method="post" as="button"
                                class="block cursor-pointer px-4 py-2 text-left text-sm hover:bg-white/10">
                            Sign
                            out</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage()
const authUser = page.props?.auth?.user ?? {
    name: 'Admin',
    email: 'Sin email',
}

</script>