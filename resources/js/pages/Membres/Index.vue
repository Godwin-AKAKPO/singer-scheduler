<template>
    <AppLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Membres</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ membres.total }} membre(s) enregistré(s)</p>
                </div>
                <Link
                    :href="route('membres.create')"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter un membre
                </Link>
            </div>

            <!-- Recherche + Filtres -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <div class="flex flex-col sm:flex-row gap-3">

                    <!-- Barre de recherche -->
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            @input="handleSearch"
                            type="text"
                            placeholder="Rechercher un membre..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Filtre par culte -->
                    <select
                        v-model="culteFilter"
                        @change="handleFilter"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    >
                        <option value="">Tous les cultes</option>
                        <option value="C1">Culte 1 uniquement</option>
                        <option value="C2">Culte 2 uniquement</option>
                    </select>

                    <!-- Filtre par rôle -->
                    <select
                        v-model="roleFilter"
                        @change="handleFilter"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    >
                        <option value="">Tous les rôles</option>
                        <option value="lead">Lead</option>
                        <option value="choeur_sopra">Sopra</option>
                        <option value="choeur_alto">Alto</option>
                        <option value="choeur_tenor">Ténor</option>
                        <option value="piano1">Piano 1</option>
                        <option value="piano2">Piano 2</option>
                        <option value="solo">Solo</option>
                        <option value="basse">Basse</option>
                        <option value="batterie">Batterie</option>
                    </select>

                    <!-- Bouton reset -->
                    <button
                        v-if="searchQuery || culteFilter || roleFilter"
                        @click="resetFiltres"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Tableau -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div v-if="membres.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center px-4">
                    <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-base font-medium text-gray-900 mb-1">
                        {{ searchQuery || culteFilter || roleFilter ? 'Aucun résultat trouvé' : 'Aucun membre enregistré' }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        {{ searchQuery || culteFilter || roleFilter
                            ? 'Essayez de modifier vos critères de recherche.'
                            : 'Commencez par ajouter les membres du groupe.' }}
                    </p>
                    <Link v-if="!searchQuery && !culteFilter && !roleFilter"
                        :href="route('membres.create')"
                        class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">
                        Ajouter un membre
                    </Link>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cultes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membre in membres.data" :key="membre.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ membre.nom }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <span v-for="culte in membre.cultes_autorises" :key="culte"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            {{ culte }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="role in getRoles(membre)" :key="role"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            {{ role }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-1.5 w-16">
                                            <div class="bg-emerald-600 h-1.5 rounded-full" :style="{ width: (membre.score * 10) + '%' }"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-emerald-700">{{ membre.score }}</span>
                                        <span class="text-xs text-gray-400">/10</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <Link :href="route('membres.edit', membre.id)"
                                            class="inline-flex items-center gap-1 text-sm text-emerald-600 hover:text-emerald-800 font-medium transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Modifier
                                        </Link>
                                        <button @click="supprimer(membre)"
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

            <!-- Pagination -->
            <div v-if="membres.last_page > 1" class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Affichage de <span class="font-medium">{{ membres.from }}</span> à <span class="font-medium">{{ membres.to }}</span> sur <span class="font-medium">{{ membres.total }}</span> membres
                </p>
                <div class="flex items-center gap-1">
                    <!-- Précédent -->
                    <button
                        @click="goToPage(membres.current_page - 1)"
                        :disabled="membres.current_page === 1"
                        class="flex items-center justify-center w-8 h-8 rounded-lg border text-sm transition-colors"
                        :class="membres.current_page === 1
                            ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                            : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Pages -->
                    <template v-for="page in pagesVisibles" :key="page">
                        <span v-if="page === '...'" class="flex items-center justify-center w-8 h-8 text-sm text-gray-400">...</span>
                        <button
                            v-else
                            @click="goToPage(page)"
                            class="flex items-center justify-center w-8 h-8 rounded-lg border text-sm font-medium transition-colors"
                            :class="page === membres.current_page
                                ? 'bg-emerald-600 border-emerald-600 text-white'
                                : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
                        >
                            {{ page }}
                        </button>
                    </template>

                    <!-- Suivant -->
                    <button
                        @click="goToPage(membres.current_page + 1)"
                        :disabled="membres.current_page === membres.last_page"
                        class="flex items-center justify-center w-8 h-8 rounded-lg border text-sm transition-colors"
                        :class="membres.current_page === membres.last_page
                            ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                            : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    membres: Object, // paginated
    search: String,
    culte: String,
    role: String,
});

const searchQuery = ref(props.search || '');
const culteFilter = ref(props.culte || '');
const roleFilter  = ref(props.role  || '');

const getRoles = (membre) => {
    const roles = [];
    if (membre.lead_c1 || membre.lead_c2)      roles.push('Lead');
    if (membre.choeur_sopra) roles.push('Sopra');
    if (membre.choeur_alto) roles.push('Alto');
    if (membre.choeur_tenor) roles.push('Ténor');
    if (membre.piano1)    roles.push('Piano 1');
    if (membre.piano2)    roles.push('Piano 2');
    if (membre.solo)      roles.push('Solo');
    if (membre.basse)     roles.push('Basse');
    if (membre.batterie)  roles.push('Batterie');
    return roles;
};

const handleSearch = debounce(() => {
    router.get(route('membres.index'), {
        search: searchQuery.value,
        culte:  culteFilter.value,
        role:   roleFilter.value,
    }, { preserveState: true, replace: true });
}, 300);

const handleFilter = () => {
    router.get(route('membres.index'), {
        search: searchQuery.value,
        culte:  culteFilter.value,
        role:   roleFilter.value,
    }, { preserveState: true, replace: true });
};

const resetFiltres = () => {
    searchQuery.value = '';
    culteFilter.value = '';
    roleFilter.value  = '';
    router.get(route('membres.index'), {}, { preserveState: true, replace: true });
};

const goToPage = (page) => {
    if (page < 1 || page > props.membres.last_page) return;
    router.get(route('membres.index'), {
        search: searchQuery.value,
        culte:  culteFilter.value,
        role:   roleFilter.value,
        page,
    }, { preserveState: true, replace: true });
};

const pagesVisibles = computed(() => {
    const current = props.membres.current_page;
    const last    = props.membres.last_page;
    const pages   = [];

    if (last <= 7) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current > 3)       pages.push('...');
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.push(i);
        if (current < last - 2) pages.push('...');
        pages.push(last);
    }
    return pages;
});

const supprimer = (membre) => {
    if (confirm(`Supprimer ${membre.nom} ?`)) {
        router.delete(route('membres.destroy', membre.id));
    }
};
</script>