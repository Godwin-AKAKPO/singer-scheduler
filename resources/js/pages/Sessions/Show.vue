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
                        {{ session.programmation ? 'Regénérer' : 'Générer' }}
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
                    Générer la programmation
                </button>
            </div>

            <!-- Carousel -->
            <div v-else class="space-y-4">

                <!-- Navigation dots + flèches -->
                <div class="flex items-center justify-between">
                    <!-- Flèche gauche -->
                    <button @click="precedent"
                        :disabled="indexActif === 0"
                        class="flex items-center justify-center w-9 h-9 rounded-lg border transition-colors"
                        :class="indexActif === 0
                            ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                            : 'border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300'">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Dots -->
                    <div class="flex items-center gap-2">
                        <button
                            v-for="(dimanche, i) in dimanches" :key="dimanche"
                            @click="indexActif = i"
                            class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-lg transition-all"
                            :class="i === indexActif ? 'bg-emerald-50' : 'hover:bg-gray-50'"
                        >
                            <span class="text-xs font-semibold transition-colors"
                                :class="i === indexActif ? 'text-emerald-700' : 'text-gray-400'">
                                {{ getJourAbrege(dimanche) }}
                            </span>
                            <span class="text-sm font-bold transition-colors"
                                :class="i === indexActif ? 'text-emerald-700' : 'text-gray-400'">
                                {{ getJour(dimanche) }}
                            </span>
                            <span class="block w-1.5 h-1.5 rounded-full transition-colors"
                                :class="i === indexActif ? 'bg-emerald-500' : 'bg-gray-200'">
                            </span>
                        </button>
                    </div>

                    <!-- Flèche droite -->
                    <button @click="suivant"
                        :disabled="indexActif === dimanches.length - 1"
                        class="flex items-center justify-center w-9 h-9 rounded-lg border transition-colors"
                        :class="indexActif === dimanches.length - 1
                            ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                            : 'border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300'">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Slide actif -->
                <Transition name="slide" mode="out-in">
                    <div :key="indexActif" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Culte 1 -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <!-- En-tête -->
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                                    <span class="text-sm font-semibold text-gray-800">1er Culte</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ formatDateCourt(dimanches[indexActif]) }}</span>
                            </div>

                            <!-- Lead -->
                            <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-50">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">Lead</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ getCulte(dimanches[indexActif], 'C1')?.lead?.[0] ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Rôles -->
                            <div class="px-5 py-4 grid grid-cols-2 gap-x-6 gap-y-4">
                                <div v-for="role in getRoles('C1', dimanches[indexActif])" :key="role.label">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">{{ role.label }}</p>
                                    <p :class="role.valeur !== '—' ? 'text-sm font-medium text-gray-800' : 'text-sm text-gray-300'">
                                        {{ role.valeur }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Culte 2 -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <!-- En-tête -->
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-500 text-white text-xs font-bold flex items-center justify-center">2</span>
                                    <span class="text-sm font-semibold text-gray-800">2ème Culte</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ formatDateCourt(dimanches[indexActif]) }}</span>
                            </div>

                            <!-- Lead -->
                            <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-50">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">Lead</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ getCulte(dimanches[indexActif], 'C2')?.lead?.[0] ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Rôles -->
                            <div class="px-5 py-4 grid grid-cols-2 gap-x-6 gap-y-4">
                                <div v-for="role in getRoles('C2', dimanches[indexActif])" :key="role.label">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">{{ role.label }}</p>
                                    <p :class="role.valeur !== '—' ? 'text-sm font-medium text-gray-800' : 'text-sm text-gray-300'">
                                        {{ role.valeur }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </Transition>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ session: Object });

const indexActif = ref(0);

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

const getJour = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).getDate();
};

const getJourAbrege = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('fr-FR', { weekday: 'short' }).replace('.', '');
};

const formatDateCourt = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' });
};

const getCulte = (dimanche, culte) => {
    return props.session.programmation?.find(p => p.date === dimanche && p.culte === culte);
};

const getRoles = (culte, dimanche) => {
    const c = getCulte(dimanche, culte);
    return [
        { label: 'Choeur P1', valeur: c?.choeur?.p1?.join(', ') || '—' },
        { label: 'Choeur P2', valeur: c?.choeur?.p2?.[0]        ?? '—' },
        { label: 'Choeur P3', valeur: c?.choeur?.p3?.[0]        ?? '—' },
        { label: 'Piano 1',   valeur: c?.piano1?.[0]            ?? '—' },
        { label: 'Piano 2',   valeur: c?.piano2?.[0]            ?? '—' },
        { label: 'Solo',      valeur: c?.solo?.[0]              ?? '—' },
        { label: 'Basse',     valeur: c?.basse?.[0]             ?? '—' },
        { label: 'Batterie',  valeur: c?.batterie?.[0]          ?? '—' },
    ];
};

const precedent = () => { if (indexActif.value > 0) indexActif.value--; };
const suivant   = () => { if (indexActif.value < dimanches.value.length - 1) indexActif.value++; };

const generer = () => router.post(route('programmation.generer', props.session.id));
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.25s ease;
}
.slide-enter-from {
    opacity: 0;
    transform: translateX(24px);
}
.slide-leave-to {
    opacity: 0;
    transform: translateX(-24px);
}
</style>