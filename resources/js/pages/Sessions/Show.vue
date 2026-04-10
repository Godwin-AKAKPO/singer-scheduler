<template>
    <AppLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('sessions.index')" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ getNomMois(session.mois) }} {{ session.annee }}</h1>
                        <p class="text-sm text-gray-500 mt-0.5">{{ dimanches.length }} dimanches — {{ dimanches.length * 2 }} cultes</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a v-if="session.programmation"
                        :href="route('programmation.pdf', session.id)"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter PDF
                    </a>
                    <button @click="generer"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ session.programmation ? 'Regénérer' : 'Générer la programmation' }}
                    </button>
                </div>
            </div>

            <!-- Pas encore générée -->
            <div v-if="!session.programmation"
                class="flex flex-col items-center justify-center py-16 bg-white rounded-lg border border-gray-200 shadow-sm text-center px-4">
                <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-1">Programmation non générée</h3>
                <p class="text-sm text-gray-500 mb-4">Cliquez sur "Générer" pour créer automatiquement la programmation du mois.</p>
                <button @click="generer"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Générer la programmation
                </button>
            </div>

            <!-- Tableau de programmation -->
            <div v-else class="space-y-8">
                <div v-for="dimanche in dimanches" :key="dimanche">
                    <!-- Date header -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white text-xs font-bold">
                            {{ new Date(...dimanche.split('-').map((v,i) => i === 1 ? v-1 : +v)).getDate() }}
                        </div>
                        <h2 class="text-base font-semibold text-gray-800 capitalize">{{ formatDate(dimanche) }}</h2>
                    </div>

                    <!-- Deux cultes côte à côte -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="culte in ['C1', 'C2']" :key="culte"
                            class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <!-- En-tête culte -->
                            <div :class="culte === 'C1' ? 'bg-blue-600' : 'bg-blue-500'" class="px-4 py-3 flex items-center justify-between">
                                <span class="text-white text-sm font-semibold">
                                    {{ culte === 'C1' ? '1er Culte' : '2ème Culte' }}
                                </span>
                                <span class="text-blue-200 text-xs">{{ formatDate(dimanche) }}</span>
                            </div>

                            <!-- Contenu -->
                            <div v-if="getCulte(dimanche, culte)" class="divide-y divide-gray-100">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span class="text-xs font-medium text-gray-500 w-24">Lead</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ getCulte(dimanche, culte).lead[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50">
                                    <span class="text-xs font-medium text-gray-500 w-24">Choeur P1</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).choeur.p1.join(', ') || '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span class="text-xs font-medium text-gray-500 w-24">Choeur P2</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).choeur.p2[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50">
                                    <span class="text-xs font-medium text-gray-500 w-24">Choeur P3</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).choeur.p3[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span class="text-xs font-medium text-gray-500 w-24">Piano 1</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).piano1[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50">
                                    <span class="text-xs font-medium text-gray-500 w-24">Piano 2</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).piano2[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span class="text-xs font-medium text-gray-500 w-24">Solo</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).solo[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50">
                                    <span class="text-xs font-medium text-gray-500 w-24">Basse</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).basse[0] ?? '—' }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span class="text-xs font-medium text-gray-500 w-24">Batterie</span>
                                    <span class="text-sm text-gray-900">{{ getCulte(dimanche, culte).batterie[0] ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ session: Object });

const moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
const getNomMois = (m) => moisNoms[m - 1];

const dimanches = computed(() => {
    const result = [];
    const date = new Date(props.session.annee, props.session.mois - 1, 1);
    while (date.getMonth() === props.session.mois - 1) {
        if (date.getDay() === 0) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            result.push(`${y}-${m}-${d}`);
        }
        date.setDate(date.getDate() + 1);
    }
    return result;
});

const formatDate = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
};

const getCulte = (dimanche, culte) => {
    return props.session.programmation?.find(p => p.date === dimanche && p.culte === culte);
};

const generer = () => router.post(route('programmation.generer', props.session.id));
</script>