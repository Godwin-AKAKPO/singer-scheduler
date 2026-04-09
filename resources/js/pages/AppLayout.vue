<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <span class="text-lg font-bold text-blue-700">🎵 Singer Scheduler</span>
            <div class="flex gap-6">
                <Link :href="route('membres.index')"
                      class="text-sm font-medium text-gray-600 hover:text-blue-700">
                    Membres
                </Link>
                <Link :href="route('sessions.index')"
                      class="text-sm font-medium text-gray-600 hover:text-blue-700">
                    Sessions
                </Link>
            </div>
        </nav>

        <div v-if="$page.props.flash?.success"
             class="mx-6 mt-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ $page.props.flash.success }}
        </div>

        <main class="max-w-6xl mx-auto px-6 py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>