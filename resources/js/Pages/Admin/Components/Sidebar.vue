<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <aside
        class="fixed top-0 left-0 z-40 h-screen w-64 -translate-x-full border-r border-white/10 bg-slate-950/80 pt-14 transition-transform backdrop-blur md:translate-x-0"
        aria-label="Sidenav" id="drawer-navigation">
        <div class="h-full overflow-y-auto bg-transparent px-3 py-5">
            <form action="#" method="GET" class="md:hidden mb-2">
                <label for="sidebar-search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="sidebar-search"
                        class="block w-full rounded-lg border border-white/15 bg-slate-900/80 p-2 pl-10 text-sm text-slate-100 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-indigo-400"
                        placeholder="Search" />
                </div>
            </form>
            <ul class="space-y-2">
                <li>
                    <Link :href="route('admin.dashboard')"
                        class="group flex items-center rounded-lg p-2 text-base font-medium text-slate-100 hover:bg-white/10">
                        <svg aria-hidden="true"
                            class="h-6 w-6 text-slate-400 transition duration-75 group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashbaord</span>
                    </Link>
                </li>
                <li>
                    <button type="button"
                        class="group flex w-full items-center rounded-lg p-2 text-base font-medium text-slate-100 transition duration-75 hover:bg-white/10"
                        aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
                        <svg aria-hidden="true"
                            class="h-6 w-6 flex-shrink-0 text-slate-400 transition duration-75 group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Pages</span>
                        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-pages" class="hidden py-2 space-y-2">
                        <li>
                            <Link :href="route('admin.products.index')"
                                class="group flex w-full items-center rounded-lg p-2 pl-11 text-base font-medium text-slate-200 transition duration-75 hover:bg-white/10">Product</Link>
                        </li>
                        <li>
                            <a href="#"
                                class="group flex w-full items-center rounded-lg p-2 pl-11 text-base font-medium text-slate-200 transition duration-75 hover:bg-white/10">Brand</a>
                        </li>
                        <li>
                            <a href="#"
                                class="group flex w-full items-center rounded-lg p-2 pl-11 text-base font-medium text-slate-200 transition duration-75 hover:bg-white/10">Category</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="mt-5 space-y-2 border-t border-white/10 pt-5">
                <li>
                    <a href="#"
                        class="group flex items-center rounded-lg p-2 text-base font-medium text-slate-100 transition duration-75 hover:bg-white/10">
                        <svg aria-hidden="true"
                            class="h-6 w-6 flex-shrink-0 text-slate-400 transition duration-75 group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Sale Report</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</template>