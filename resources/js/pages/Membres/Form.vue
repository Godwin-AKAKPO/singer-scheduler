<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto space-y-6">

            <div class="flex items-center gap-4">
                <Link :href="route('membres.index')"
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ membre ? 'Modifier un membre' : 'Ajouter un membre' }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ membre ? 'Mettez à jour les informations du membre' : 'Renseignez les informations du nouveau membre' }}</p>
                </div>
            </div>

            <form @submit.prevent="soumettre" class="space-y-5">

                <!-- Infos générales -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Informations générales</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet</label>
                        <input v-model="form.nom" type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="Ex : Benjamin" />
                        <p v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</p>
                    </div>

                    <!-- Cultes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cultes autorisés</label>
                        <div class="flex gap-3">
                            <label v-for="culte in ['C1', 'C2']" :key="culte"
                                class="flex items-center gap-2.5 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="form.cultes_autorises.includes(culte) ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'">
                                <input type="checkbox" :value="culte" v-model="form.cultes_autorises" class="rounded text-emerald-600" />
                                <span class="text-sm font-medium">{{ culte === 'C1' ? '1er Culte' : '2ème Culte' }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Score -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Score de préférence
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">{{ form.score }} / 10</span>
                        </label>
                        <input type="range" v-model.number="form.score" min="1" max="10" class="w-full accent-emerald-600" />
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>1 — débutant</span><span>10 — expert</span>
                        </div>
                    </div>
                </div>

                <!-- Lead -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-3">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label v-for="item in leadsOptions" :key="item.key"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-colors"
                            :class="form[item.key] ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            <input type="checkbox" v-model="form[item.key]" class="rounded text-emerald-600" />
                            <span class="text-sm font-medium">{{ item.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Choeur -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-3">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Choeur</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <label v-for="item in choeurOptions" :key="item.key"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-colors"
                            :class="form[item.key] ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            <input type="checkbox" v-model="form[item.key]" class="rounded text-emerald-600" />
                            <span class="text-sm font-medium">{{ item.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Instruments -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-3">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Instruments &amp; autres rôles</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label v-for="item in autresRoles" :key="item.key"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-colors"
                            :class="form[item.key] ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            <input type="checkbox" v-model="form[item.key]" class="rounded text-emerald-600" />
                            <span class="text-sm font-medium">{{ item.label }}</span>
                        </label>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3">
                    <Link :href="route('membres.index')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Annuler
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50">
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        {{ membre ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ membre: Object });

const leadsOptions = [
    { key: 'lead_c1', label: 'Lead Culte 1' },
    { key: 'lead_c2', label: 'Lead Culte 2' },
];

const choeurOptions = [
    { key: 'choeur_sopra', label: 'Sopra'  },
    { key: 'choeur_alto',  label: 'Alto'   },
    { key: 'choeur_tenor', label: 'Ténor'  },
];

const autresRoles = [
    { key: 'piano1',   label: 'Piano 1'  },
    { key: 'piano2',   label: 'Piano 2'  },
    { key: 'solo',     label: 'Solo'     },
    { key: 'basse',    label: 'Basse'    },
    { key: 'batterie', label: 'Batterie' },
];

const form = useForm({
    nom:              props.membre?.nom              ?? '',
    cultes_autorises: props.membre?.cultes_autorises ?? [],
    lead_c1:          props.membre?.lead_c1          ?? false,
    lead_c2:          props.membre?.lead_c2          ?? false,
    choeur_sopra:     props.membre?.choeur_sopra     ?? false,
    choeur_alto:      props.membre?.choeur_alto      ?? false,
    choeur_tenor:     props.membre?.choeur_tenor     ?? false,
    piano1:           props.membre?.piano1           ?? false,
    piano2:           props.membre?.piano2           ?? false,
    solo:             props.membre?.solo             ?? false,
    basse:            props.membre?.basse            ?? false,
    batterie:         props.membre?.batterie         ?? false,
    score:            props.membre?.score            ?? 10,
});

const soumettre = () => {
    props.membre
        ? form.put(route('membres.update', props.membre.id))
        : form.post(route('membres.store'));
};
</script>