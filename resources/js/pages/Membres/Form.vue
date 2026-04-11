<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="route('membres.index')" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ membre ? 'Modifier un membre' : 'Ajouter un membre' }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ membre ? 'Mettez à jour les informations du membre' : 'Renseignez les informations du nouveau membre' }}
                    </p>
                </div>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="soumettre" class="space-y-6">

                <!-- Nom -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Informations générales</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet</label>
                            <input v-model="form.nom" type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                placeholder="Ex : Benjamin" />
                            <p v-if="form.errors.nom" class="text-red-500 text-xs mt-1.5">{{ form.errors.nom }}</p>
                        </div>

                        <!-- Cultes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cultes autorisés</label>
                            <div class="flex gap-4">
                                <label v-for="culte in ['C1', 'C2']" :key="culte"
                                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors"
                                    :class="form.cultes_autorises.includes(culte)
                                        ? 'bg-emerald-50 border-emerald-300 text-emerald-700'
                                        : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'"
                                >
                                    <input type="checkbox" :value="culte" v-model="form.cultes_autorises" class="rounded text-emerald-600" />
                                    <span class="text-sm font-medium">{{ culte === 'C1' ? '1er Culte' : '2ème Culte' }}</span>
                                </label>
                            </div>
                            <p v-if="form.errors.cultes_autorises" class="text-red-500 text-xs mt-1.5">{{ form.errors.cultes_autorises }}</p>
                        </div>

                        <!-- Score -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Score de préférence
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    {{ form.score }} / 10
                                </span>
                            </label>
                            <input type="range" v-model.number="form.score" min="1" max="10" class="w-full accent-emerald-600" />
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>1 — débutant</span>
                                <span>10 — expert</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rôles -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Rôles éligibles</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label v-for="role in roles" :key="role.key"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-colors"
                            :class="form[role.key]
                                ? 'bg-emerald-50 border-emerald-300 text-emerald-700'
                                : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'"
                        >
                            <input type="checkbox" v-model="form[role.key]" class="rounded text-emerald-600" />
                            <span class="text-sm font-medium">{{ role.label }}</span>
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

const roles = [
    { key: 'lead',      label: 'Lead'      },
    { key: 'choeur_p1', label: 'Choeur P1' },
    { key: 'choeur_p2', label: 'Choeur P2' },
    { key: 'choeur_p3', label: 'Choeur P3' },
    { key: 'piano1',    label: 'Piano 1'   },
    { key: 'piano2',    label: 'Piano 2'   },
    { key: 'solo',      label: 'Solo'      },
    { key: 'basse',     label: 'Basse'     },
    { key: 'batterie',  label: 'Batterie'  },
];

const form = useForm({
    nom:              props.membre?.nom              ?? '',
    cultes_autorises: props.membre?.cultes_autorises ?? [],
    lead:             props.membre?.lead             ?? false,
    choeur_p1:        props.membre?.choeur_p1        ?? false,
    choeur_p2:        props.membre?.choeur_p2        ?? false,
    choeur_p3:        props.membre?.choeur_p3        ?? false,
    piano1:           props.membre?.piano1           ?? false,
    piano2:           props.membre?.piano2           ?? false,
    solo:             props.membre?.solo             ?? false,
    basse:            props.membre?.basse            ?? false,
    batterie:         props.membre?.batterie         ?? false,
    score:            props.membre?.score            ?? 5,
});

const soumettre = () => {
    if (props.membre) {
        form.put(route('membres.update', props.membre.id));
    } else {
        form.post(route('membres.store'));
    }
};
</script>