<template>
    <AppLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('sessions.index')"
                        class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ getNomMois(session.mois) }} {{ session.annee }}</h1>
                        <p class="text-sm text-gray-500 mt-0.5">{{ dimanches.length }} dimanches · {{ dimanches.length * 2 }} cultes</p>
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
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ session.programmation ? 'Regénérer' : 'Générer la programmation' }}
                    </button>
                </div>
            </div>

            <!-- Pas encore générée -->
            <div v-if="!session.programmation"
                class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm text-center px-4">
                <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="h-7 w-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Programmation non générée</h3>
                <p class="text-sm text-gray-500 mb-5 max-w-sm">Lancez la génération automatique pour répartir équitablement les membres sur les cultes du mois.</p>
                <button @click="generer"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Générer la programmation
                </button>
            </div>

            <!-- Programmation en vue tableau -->
            <div v-else class="space-y-10">
                <div v-for="dimanche in dimanches" :key="dimanche">

                    <!-- En-tête dimanche -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-emerald-600 text-white shadow-sm">
                            <span class="text-xs font-medium leading-none opacity-80">{{ getJourAbrege(dimanche) }}</span>
                            <span class="text-lg font-bold leading-tight">{{ getJour(dimanche) }}</span>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 capitalize">{{ formatDate(dimanche) }}</h2>
                            <p class="text-xs text-gray-400">2 cultes programmés</p>
                        </div>
                    </div>

                    <!-- Tableau horizontal des deux cultes -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-28">Rôle</th>
                                    <th class="px-4 py-3 text-left w-1/2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-600 text-white text-xs font-bold">C1</span>
                                            <span class="text-sm font-semibold text-gray-700">1er Culte</span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left w-1/2 border-l border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-500 text-white text-xs font-bold">C2</span>
                                            <span class="text-sm font-semibold text-gray-700">2ème Culte</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="(ligne, index) in getLignes(dimanche)" :key="ligne.role"
                                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50/60'">
                                    <td class="px-4 py-2.5">
                                        <span class="text-xs font-medium text-gray-400">{{ ligne.label }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span :class="ligne.c1 && ligne.c1 !== '—' ? 'text-gray-900 font-medium' : 'text-gray-300'">
                                            {{ ligne.c1 || '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 border-l border-gray-100">
                                        <span :class="ligne.c2 && ligne.c2 !== '—' ? 'text-gray-900 font-medium' : 'text-gray-300'">
                                            {{ ligne.c2 || '—' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

const getJour = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).getDate();
};

const getJourAbrege = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('fr-FR', { weekday: 'short' }).replace('.', '');
};

const getCulte = (dimanche, culte) => {
    return props.session.programmation?.find(p => p.date === dimanche && p.culte === culte);
};

const getLignes = (dimanche) => {
    const c1 = getCulte(dimanche, 'C1');
    const c2 = getCulte(dimanche, 'C2');

    return [
        { label: 'Lead',      c1: c1?.lead?.[0]              ?? '—', c2: c2?.lead?.[0]              ?? '—' },
        { label: 'Choeur P1', c1: c1?.choeur?.p1?.join(', ') || '—', c2: c2?.choeur?.p1?.join(', ') || '—' },
        { label: 'Choeur P2', c1: c1?.choeur?.p2?.[0]        ?? '—', c2: c2?.choeur?.p2?.[0]        ?? '—' },
        { label: 'Choeur P3', c1: c1?.choeur?.p3?.[0]        ?? '—', c2: c2?.choeur?.p3?.[0]        ?? '—' },
        { label: 'Piano 1',   c1: c1?.piano1?.[0]            ?? '—', c2: c2?.piano1?.[0]            ?? '—' },
        { label: 'Piano 2',   c1: c1?.piano2?.[0]            ?? '—', c2: c2?.piano2?.[0]            ?? '—' },
        { label: 'Solo',      c1: c1?.solo?.[0]              ?? '—', c2: c2?.solo?.[0]              ?? '—' },
        { label: 'Basse',     c1: c1?.basse?.[0]             ?? '—', c2: c2?.basse?.[0]             ?? '—' },
        { label: 'Batterie',  c1: c1?.batterie?.[0]          ?? '—', c2: c2?.batterie?.[0]          ?? '—' },
    ];
};

const generer = () => router.post(route('programmation.generer', props.session.id));
</script>