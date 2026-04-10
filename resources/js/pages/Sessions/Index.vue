<template>
    <AppLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sessions mensuelles</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ sessions.length }} session(s) créée(s)</p>
                </div>
                <Link :href="route('sessions.create')"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle session
                </Link>
            </div>

            <!-- Liste -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div v-if="sessions.length === 0" class="flex flex-col items-center justify-center py-16 text-center px-4">
                    <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-base font-medium text-gray-900 mb-1">Aucune session créée</h3>
                    <p class="text-sm text-gray-500 mb-4">Créez une session pour générer la programmation du mois.</p>
                    <Link :href="route('sessions.create')" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Nouvelle session
                    </Link>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimanches</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="session in sessions" :key="session.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                                            {{ session.mois }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ getNomMois(session.mois) }} {{ session.annee }}</div>
                                            <div class="text-xs text-gray-500">{{ getNbDimanches(session.annee, session.mois) }} dimanches</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ getNbDimanches(session.annee, session.mois) }} dimanches
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="session.programmation"
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Générée
                                    </span>
                                    <span v-else
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        En attente
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <Link :href="route('sessions.show', session.id)"
                                            class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </Link>
                                        <button @click="supprimer(session)"
                                            class="inline-flex items-center gap-1 text-sm text-red-500 hover:text-red-700 font-medium transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="js">
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({ sessions: Array });

const moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
const getNomMois = (m) => moisNoms[m - 1];

const getNbDimanches = (annee, mois) => {
    const date = new Date(annee, mois - 1, 1);
    let count = 0;
    while (date.getMonth() === mois - 1) {
        if (date.getDay() === 0) count++;
        date.setDate(date.getDate() + 1);
    }
    return count;
};

const supprimer = (session) => {
    if (confirm(`Supprimer la session ${getNomMois(session.mois)} ${session.annee} ?`)) {
        router.delete(route('sessions.destroy', session.id));
    }
};
</script>