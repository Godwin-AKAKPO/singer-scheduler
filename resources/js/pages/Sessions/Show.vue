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
                    <button v-if="programmationLocale && modifie"
                        @click="sauvegarder" :disabled="sauvegarde"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-60">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Sauvegarder
                    </button>
                    <a v-if="programmationLocale && !modifie"
                        :href="route('programmation.pdf', session.id)" target="_blank"
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
                        {{ programmationLocale ? 'Regénérer' : 'Générer' }}
                    </button>
                </div>
            </div>

            <!-- Pas encore générée -->
            <div v-if="!programmationLocale"
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

                <!-- Bandeau mode édition -->
                <div v-if="modeEdition" class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                    <svg class="h-4 w-4 flex-shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Mode édition — les listes proposent uniquement les membres disponibles et non programmés ce dimanche.</span>
                    <button @click="modeEdition = false" class="ml-auto text-amber-600 hover:text-amber-800 font-medium text-xs">Terminer</button>
                </div>

                <!-- Nav carousel -->
                <div class="flex items-center justify-between">
                    <button @click="precedent" :disabled="indexActif === 0"
                        class="flex items-center justify-center w-9 h-9 rounded-lg border transition-colors"
                        :class="indexActif === 0 ? 'border-gray-100 text-gray-300 cursor-not-allowed' : 'border-gray-200 text-gray-600 hover:bg-gray-50'">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <button v-for="(dim, i) in dimanches" :key="dim" @click="indexActif = i"
                            class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-lg transition-all"
                            :class="i === indexActif ? 'bg-emerald-50' : 'hover:bg-gray-50'">
                            <span class="text-xs font-semibold" :class="i === indexActif ? 'text-emerald-700' : 'text-gray-400'">{{ getJourAbrege(dim) }}</span>
                            <span class="text-sm font-bold" :class="i === indexActif ? 'text-emerald-700' : 'text-gray-400'">{{ getJour(dim) }}</span>
                            <span class="block w-1.5 h-1.5 rounded-full" :class="i === indexActif ? 'bg-emerald-500' : 'bg-gray-200'"></span>
                        </button>
                    </div>

                    <button @click="suivant" :disabled="indexActif === dimanches.length - 1"
                        class="flex items-center justify-center w-9 h-9 rounded-lg border transition-colors"
                        :class="indexActif === dimanches.length - 1 ? 'border-gray-100 text-gray-300 cursor-not-allowed' : 'border-gray-200 text-gray-600 hover:bg-gray-50'">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Slide -->
                <Transition name="slide" mode="out-in">
                    <div :key="indexActif" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div v-for="culte in ['C1', 'C2']" :key="culte"
                            class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                            <!-- En-tête -->
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg text-white text-xs font-bold flex items-center justify-center"
                                        :class="culte === 'C1' ? 'bg-emerald-600' : 'bg-emerald-500'">
                                        {{ culte === 'C1' ? '1' : '2' }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-800">{{ culte === 'C1' ? '1er Culte' : '2ème Culte' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400">{{ formatDateCourt(dimanches[indexActif]) }}</span>
                                    <button @click="toggleEdition(culte)"
                                        class="flex items-center justify-center w-6 h-6 rounded transition-colors"
                                        :class="modeEdition && culteEnEdition === culte ? 'text-amber-500 bg-amber-50' : 'text-gray-400 hover:text-emerald-600 hover:bg-emerald-50'"
                                        title="Modifier">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Lead -->
                            <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-50">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">Lead</p>
                                    <div v-if="!isEditing(culte)">
                                        <span :class="getCulteLocal(dimanches[indexActif], culte)?.lead?.[0] ? 'text-sm font-bold text-gray-900' : 'text-sm text-gray-300'">
                                            {{ getCulteLocal(dimanches[indexActif], culte)?.lead?.[0] ?? '—' }}
                                        </span>
                                    </div>
                                    <SelectMembre v-else
                                        :session-id="session.id"
                                        :dimanche="dimanches[indexActif]"
                                        :culte="culte"
                                        role="lead"
                                        :valeur="getCulteLocal(dimanches[indexActif], culte)?.lead?.[0] ?? ''"
                                        @change="v => setValeur(dimanches[indexActif], culte, 'lead', 0, v)"
                                    />
                                </div>
                            </div>

                            <!-- Autres rôles -->
                            <div class="px-5 py-4 grid grid-cols-2 gap-x-6 gap-y-4">
                                <div v-for="role in getRoles(culte, dimanches[indexActif])" :key="role.key">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide leading-none mb-1">{{ role.label }}</p>
                                    <div v-if="!isEditing(culte)">
                                        <span :class="role.valeur !== '—' ? 'text-sm font-medium text-gray-800' : 'text-sm text-gray-300'">
                                            {{ role.valeur }}
                                        </span>
                                    </div>
                                    <template v-else>
                                        <!-- Sopra : 2 membres, deux selects -->
                                        <div v-if="role.key === 'choeur.sopra'" class="space-y-1">
                                            <SelectMembre
                                                :session-id="session.id"
                                                :dimanche="dimanches[indexActif]"
                                                :culte="culte"
                                                role="choeur_sopra"
                                                :valeur="getCulteLocal(dimanches[indexActif], culte)?.choeur?.sopra?.[0] ?? ''"
                                                @change="v => setSopra(dimanches[indexActif], culte, 0, v)"
                                            />
                                            <SelectMembre
                                                :session-id="session.id"
                                                :dimanche="dimanches[indexActif]"
                                                :culte="culte"
                                                role="choeur_sopra"
                                                :valeur="getCulteLocal(dimanches[indexActif], culte)?.choeur?.sopra?.[1] ?? ''"
                                                @change="v => setSopra(dimanches[indexActif], culte, 1, v)"
                                            />
                                        </div>
                                        <SelectMembre v-else
                                            :session-id="session.id"
                                            :dimanche="dimanches[indexActif]"
                                            :culte="culte"
                                            :role="role.dbKey"
                                            :valeur="role.valeur === '—' ? '' : role.valeur"
                                            @change="v => setValeurRole(dimanches[indexActif], culte, role.key, v)"
                                        />
                                    </template>
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
import SelectMembre from '@/Components/SelectMembre.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({ session: Object });

const programmationLocale = ref(
    props.session.programmation
        ? JSON.parse(JSON.stringify(props.session.programmation))
        : null
);

const indexActif    = ref(0);
const modeEdition   = ref(false);
const culteEnEdition = ref(null);
const modifie       = ref(false);
const sauvegarde    = ref(false);

watch(programmationLocale, () => { modifie.value = true; }, { deep: true });

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

const getJour       = (s) => { const [y,m,d] = s.split('-').map(Number); return new Date(y,m-1,d).getDate(); };
const getJourAbrege = (s) => { const [y,m,d] = s.split('-').map(Number); return new Date(y,m-1,d).toLocaleDateString('fr-FR',{weekday:'short'}).replace('.',''); };
const formatDateCourt = (s) => { const [y,m,d] = s.split('-').map(Number); return new Date(y,m-1,d).toLocaleDateString('fr-FR',{day:'numeric',month:'long'}); };

const getCulteLocal = (dimanche, culte) => programmationLocale.value?.find(p => p.date === dimanche && p.culte === culte);

const getRoles = (culte, dimanche) => {
    const c = getCulteLocal(dimanche, culte);
    return [
        { key: 'choeur.sopra', dbKey: 'choeur_sopra', label: 'Sopra',    valeur: c?.choeur?.sopra?.join(', ') || '—' },
        { key: 'choeur.alto',  dbKey: 'choeur_alto',  label: 'Alto',     valeur: c?.choeur?.alto?.[0]  ?? '—' },
        { key: 'choeur.tenor', dbKey: 'choeur_tenor', label: 'Ténor',    valeur: c?.choeur?.tenor?.[0] ?? '—' },
        { key: 'piano1',       dbKey: 'piano1',       label: 'Piano 1',  valeur: c?.piano1?.[0]        ?? '—' },
        { key: 'piano2',       dbKey: 'piano2',       label: 'Piano 2',  valeur: c?.piano2?.[0]        ?? '—' },
        { key: 'solo',         dbKey: 'solo',         label: 'Solo',     valeur: c?.solo?.[0]          ?? '—' },
        { key: 'basse',        dbKey: 'basse',        label: 'Basse',    valeur: c?.basse?.[0]         ?? '—' },
        { key: 'batterie',     dbKey: 'batterie',     label: 'Batterie', valeur: c?.batterie?.[0]      ?? '—' },
    ];
};

const toggleEdition = (culte) => {
    if (modeEdition.value && culteEnEdition.value === culte) {
        modeEdition.value = false;
        culteEnEdition.value = null;
    } else {
        modeEdition.value = true;
        culteEnEdition.value = culte;
    }
};

const isEditing = (culte) => modeEdition.value && culteEnEdition.value === culte;

const setValeur = (dimanche, culte, champ, index, valeur) => {
    const entry = programmationLocale.value?.find(p => p.date === dimanche && p.culte === culte);
    if (!entry) return;
    if (!entry[champ]) entry[champ] = [];
    entry[champ][index] = valeur || null;
    modifie.value = true;
};

const setSopra = (dimanche, culte, index, valeur) => {
    const entry = programmationLocale.value?.find(p => p.date === dimanche && p.culte === culte);
    if (!entry) return;
    if (!entry.choeur) entry.choeur = {};
    if (!entry.choeur.sopra) entry.choeur.sopra = [];
    entry.choeur.sopra[index] = valeur || null;
    modifie.value = true;
};

const setValeurRole = (dimanche, culte, key, valeur) => {
    const entry = programmationLocale.value?.find(p => p.date === dimanche && p.culte === culte);
    if (!entry) return;
    if (key.startsWith('choeur.')) {
        const sous = key.split('.')[1];
        if (!entry.choeur) entry.choeur = {};
        if (!entry.choeur[sous]) entry.choeur[sous] = [];
        entry.choeur[sous][0] = valeur || null;
    } else {
        if (!entry[key]) entry[key] = [];
        entry[key][0] = valeur || null;
    }
    modifie.value = true;
};

const sauvegarder = () => {
    sauvegarde.value = true;
    router.put(route('programmation.modifier', props.session.id), {
        programmation: programmationLocale.value,
    }, {
        onSuccess: () => { modifie.value = false; sauvegarde.value = false; modeEdition.value = false; culteEnEdition.value = null; },
        onError:   () => { sauvegarde.value = false; }
    });
};

const precedent = () => { if (indexActif.value > 0) indexActif.value--; };
const suivant   = () => { if (indexActif.value < dimanches.value.length - 1) indexActif.value++; };
const generer   = () => router.post(route('programmation.generer', props.session.id));
</script>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.22s ease; }
.slide-enter-from { opacity: 0; transform: translateX(20px); }
.slide-leave-to   { opacity: 0; transform: translateX(-20px); }
</style>