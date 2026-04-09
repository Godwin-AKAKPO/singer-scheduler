<template>
    <AppLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Membres</h1>
            <Link :href="route('membres.create')"
                  class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
                + Ajouter un membre
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Nom</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Cultes</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Rôles</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Score</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="membres.length === 0">
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            Aucun membre enregistré.
                        </td>
                    </tr>
                    <tr v-for="membre in membres" :key="membre.id"
                        class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ membre.nom }}</td>
                        <td class="px-4 py-3">
                            <span v-for="culte in membre.cultes_autorises" :key="culte"
                                  class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full mr-1">
                                {{ culte }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span v-for="role in getRoles(membre)" :key="role"
                                  class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full mr-1 mb-1">
                                {{ role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-blue-700">{{ membre.score }}</span>
                            <span class="text-gray-400">/10</span>
                        </td>
                        <td class="px-4 py-3 text-right flex justify-end gap-2">
                            <Link :href="route('membres.edit', membre.id)"
                                  class="text-blue-600 hover:underline text-xs">
                                Modifier
                            </Link>
                            <button @click="supprimer(membre)"
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
    membres: Array,
});

const getRoles = (membre) => {
    const roles = [];
    if (membre.lead)      roles.push('Lead');
    if (membre.choeur_p1) roles.push('Chœur P1');
    if (membre.choeur_p2) roles.push('Chœur P2');
    if (membre.choeur_p3) roles.push('Chœur P3');
    if (membre.piano1)    roles.push('Piano 1');
    if (membre.piano2)    roles.push('Piano 2');
    if (membre.solo)      roles.push('Solo');
    if (membre.basse)     roles.push('Basse');
    if (membre.batterie)  roles.push('Batterie');
    return roles;
};

const supprimer = (membre) => {
    if (confirm(`Supprimer ${membre.nom} ?`)) {
        router.delete(route('membres.destroy', membre.id));
    }
};
</script>