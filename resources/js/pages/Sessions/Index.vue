<template>
    <AppLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Sessions mensuelles</h1>
            <Link :href="route('sessions.create')"
                  class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
                + Nouvelle session
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Mois</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Dimanches</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Statut</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="sessions.length === 0">
                        <td colspan="4" class="text-center py-10 text-gray-400">
                            Aucune session créée.
                        </td>
                    </tr>
                    <tr v-for="session in sessions" :key="session.id"
                        class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ getNomMois(session.mois) }} {{ session.annee }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ getNbDimanches(session.annee, session.mois) }} dimanches
                        </td>
                        <td class="px-4 py-3">
                            <span v-if="session.programmation"
                                  class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">
                                Générée
                            </span>
                            <span v-else
                                  class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">
                                En attente
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right flex justify-end gap-3">
                            <Link :href="route('sessions.show', session.id)"
                                  class="text-blue-600 hover:underline text-xs">
                                Voir
                            </Link>
                            <button @click="supprimer(session)"
                                    class="text-red-500 hover:underline text-xs">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    sessions: Array,
});

const mois = [
    'Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
];

const getNomMois = (m) => mois[m - 1];

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