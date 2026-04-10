<template>
    <div class="min-h-screen bg-gray-50">

        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    <!-- Logo + Nav principale -->
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <Link :href="route('membres.index')" class="flex items-center space-x-3 group">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-sm shadow-sm group-hover:shadow-md transition-all">
                                    SS
                                </div>
                                <div class="hidden sm:block">
                                    <h1 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        Singer Scheduler
                                    </h1>
                                </div>
                            </Link>
                        </div>

                        <div class="hidden lg:block w-px h-6 bg-gray-300"></div>

                        <!-- Liens desktop -->
                        <div class="hidden lg:flex items-center space-x-1">
                            <Link
                                :href="route('membres.index')"
                                :class="[
                                    'inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200',
                                    isActive('membres')
                                        ? 'text-blue-700 bg-blue-50 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                                ]"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Membres</span>
                            </Link>

                            <Link
                                :href="route('sessions.index')"
                                :class="[
                                    'inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200',
                                    isActive('sessions')
                                        ? 'text-blue-700 bg-blue-50 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                                ]"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Sessions</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Menu mobile burger -->
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="lg:hidden flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu mobile -->
            <div v-if="mobileOpen" class="lg:hidden border-t border-gray-200 bg-gray-50">
                <div class="px-4 py-3 space-y-1">
                    <Link
                        :href="route('membres.index')"
                        @click="mobileOpen = false"
                        :class="[
                            'flex items-center space-x-3 w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                            isActive('membres')
                                ? 'text-blue-700 bg-white shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-white'
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Membres</span>
                    </Link>

                    <Link
                        :href="route('sessions.index')"
                        @click="mobileOpen = false"
                        :class="[
                            'flex items-center space-x-3 w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                            isActive('sessions')
                                ? 'text-blue-700 bg-white shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-white'
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Sessions</span>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Flash success -->
        <div v-if="$page.props.flash?.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                <svg class="h-4 w-4 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $page.props.flash.success }}
            </div>
        </div>

        <!-- Contenu -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const mobileOpen = ref(false);
const page = usePage();

const isActive = (section) => {
    return page.url.startsWith('/' + section);
};
</script>